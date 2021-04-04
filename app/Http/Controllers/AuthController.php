<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\ForumRegistrationApi;
use Illuminate\Auth\Events\Registered;
use Auth;
use Illuminate\Auth\Events\Login;

class AuthController extends Controller 
{   
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }

    public function postSignin(Request $request)
    {
        $this->validate($request, [
          'email' => 'required',
          'password' => 'required',
        ]);
        
        $login_email = $request->input('email');
        $login_password = $request->input('password');

        $user = User::where('email', $login_email)->first();

        if($user){
          event(new Login($user, true));
    
          $this->guard()->login($user);
        }

        // $informationEngage = new ForumRegistrationApi(null, null, $login_email, $login_password);

        // $informationEngage->get_correct_login_credentials();

        // $correct_login_details = $informationEngage->login_details;
        
        // $hashUser = User::where('hash', $correct_login_details['data']['hash'])->get();

        // if ($hashUser->isNotEmpty()){
        //     foreach ($hashUser as $user){
        //       $id = $user->id;
        //     }
            
        //     $user = User::find($id);
            
        //     event(new Login($user, true));
    
        //     $this->guard()->login($user);
        // } else {
        //   event(new Registered($user = $this->createEngageUser($correct_login_details)));
    
        //   $this->guard()->login($user);
        // }

        return redirect()->back();
      }

      // public function postRegister(Request $request)
      // {
      //   $this->validate($request, [
      //     'first_name' => 'required',
      //     'last_name' => 'required',
      //     'middle_name' => 'required',
      //     'last_name' => 'required',
      //     'email' => 'required',
      //     'password' => 'required',
      //   ]);

      //   event(new Registered($user = $this->createEngageUser($correct_login_details)));
      // }

      protected function createEngageUser(array $data)
      {
          return User::forceCreate([
              'username' => $data['data']['username'],
              'first_name' => $data['data']['FirstName'],
              'middle_name' => $data['data']['MiddleName'],
              'last_name' => $data['data']['LastName'],
              'email' => $data['data']['Email'],
              'password' => $data['data']['Password'],
              'hash' => $data['data']['hash'],
              'state_id' => $data['state'],
          ]);
      }
  
      /**
       * Get the guard to be used upon registering the user coming from engage.
       *
       * @return \Illuminate\Contracts\Auth\StatefulGuard
       */
      protected function guard()
      {
          return Auth::guard();
      }

}
