<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\ForumRegistrationApi;

class UserController extends Controller
{
    public function postSignin(Request $request){

        $login_email = $request->input('email');
        $login_password = $request->input('password');
        
        $informationEngage = new ForumRegistrationApi(null, null, $login_email, $login_password);
        
        $informationEngage->get_correct_login_credentials();
        
        $correct_login_details = $informationEngage->login_details;
        
        $user = array('email' => $correct_login_details['data']['Email'], 'hash' => $correct_login_details['data']['hash']);

        Auth::login($user, true);
    }


}

