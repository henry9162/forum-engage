<?php

namespace App\Http\Controllers;

use App\User;
use App\Thread;
use App\Picture;
use App\Channel;
use App\Trending;
use App\Rules\Recaptcha;
use App\Filters\ThreadFilters;
use Illuminate\Validation\Rule;
use App\ForumRegistrationApi;
use App\BlobApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;

class ThreadsController extends Controller
{
    /**
     * Create a new ThreadsController instance.
     */
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }

    /**
     * Display a listing of the resource.
     * Check if user is logged in at information engage
     * If true, save user data in database
     *
     * @param  Channel      $channel
     * @param ThreadFilters $filters
     * @param \App\Trending $trending
     * @return \Illuminate\Http\Response
     */
    public function index(Channel $channel, ThreadFilters $filters, Request $request)
    {
        if ($request->has('emailaddress') && $request->has('hash') ){

            $email = $request->get('emailaddress');
            $hash = $request->get('hash');
            $user = User::where('hash', $hash)->first();
            
            if (!$user){
                $informationEngage = new ForumRegistrationApi($email, $hash);

                $informationEngage->get_loggedin_user_details();
    
                $user_details = $informationEngage->user_data;
    
                event(new Registered($user = $this->createEngageUser($user_details)));
    
                $this->guard()->login($user);
            } else {
                $this->guard()->login($user);
            }
             
        } 

        $threads = $this->getThreads($channel, $filters);

        if (request()->wantsJson()) {
            return $threads;
        }

        return view('threads.index', [
            'threads' => $threads,
            'channel' => $channel
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('threads.create', [
            'channels' => Channel::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Rules\Recaptcha $recaptcha
     * @return \Illuminate\Http\Response
     */
    public function store(Recaptcha $recaptcha)
    {    
        request()->validate([
            'title' => 'required|spamfree',
            'body' => 'required|spamfree',
            'channel_id' => [
                'required',
                Rule::exists('channels', 'id')->where(function ($query) {
                    $query->where('archived', false);
                })
            ],
            //'images.*' => 'required|file|image|mimes:jpeg,png,gif,webp|max:2048'
            //'g-recaptcha-response' => ['required', $recaptcha]
        ]);

        $thread = Thread::create([
            'user_id' => auth()->id(),
            'channel_id' => request('channel_id'),
            'title' => request('title'),
            'body' => request('body'),
            'slug' => request('title')
        ]);

        if(request()->has('images')){
            $this->addImage(request('images'), $thread);
        }

        if (request()->wantsJson()) {
            return response($thread, 201);
        }

        return redirect($thread->path())
            ->with('flash', 'Your thread has been published!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int      $channel
     * @param  \App\Thread  $thread
     * @param \App\Trending $trending
     * @return \Illuminate\Http\Response
     */
    public function show($channel, Thread $thread, Trending $trending)
    {
        if (auth()->check()) {
            auth()->user()->read($thread);
        }

        $trending->push($thread);

        $thread->increment('visits');

        $images = [];

        if (count($thread->pictures)){
            foreach ($thread->pictures as $picture){
                array_push($images, $picture->url);
            }
        }

        return view('threads.show', compact('thread', 'images'));
    }

    /**
     * Update the given thread.
     *
     * @param string $channel
     * @param Thread $thread
     */
    public function update($channel, Thread $thread)
    {
        $this->authorize('update', $thread);

        $thread->update(request()->validate([
            'title' => 'required',
            'body' => 'required'
        ]));

        if(request()->has('images')){
            $this->deleteThreadImages($thread);
            $this->addImage(request('images'), $thread);
        }

        return $thread;
    }

    /**
     * Delete the given thread.
     *
     * @param        $channel
     * @param Thread $thread
     * @return mixed
     */
    public function destroy($channel, Thread $thread)
    {
        $this->authorize('update', $thread);

        
        $this->deleteThreadImages($thread);

        $thread->delete();

        if (request()->wantsJson()) {
            return response([], 204);
        }

        return redirect('/threads');
    }

    /**
     * Fetch all relevant threads.
     *
     * @param Channel       $channel
     * @param ThreadFilters $filters
     * @return mixed
     */
    protected function getThreads(Channel $channel, ThreadFilters $filters)
    {
        $threads = Thread::latest('pinned')->latest()->with('channel')->filter($filters);

        if ($channel->exists) {
            $threads->where('channel_id', $channel->id);
        }

        return $threads->paginate(config('council.pagination.perPage'));
    }

    /**
     * Create a new user instance after a valid login confirmation.
     *
     * @param  array $data
     * @return User
     */
    protected function createEngageUser(array $data)
    {
        return User::forceCreate([
            'username' => $data['data']['username'],
            'first_name' => $data['data']['FirstName'],
            'middle_name' => $data['data']['MiddleName'],
            'last_name' => $data['data']['LastName'],
            'email' => $data['data']['email'],
            'gender' => $data['data']['gender'],
            'phone' => $data['data']['Phone'],
            'password' => $data['data']['Password'],
            'hash' => $data['data']['hash'],
            'state_id' => $data['state'],
        ]);
    }

    protected function addImage(array $images, $thread)
    {
        foreach ($images as $image)
            $blobImage = $this->blobImage($image);
            $blobApiClass = new BlobApi($blobImage);
            $blobApiClass->get_image_url();
            $image_url = $blobApiClass->imageUrl;

            Picture::create([
                'blob_id' => $image_url['data']['remoteImageId'],
                'url' => $image_url['data']['imageURL'],
                'pictureable_id' => $thread->id,
                'pictureable_type' => "APP\Thread"
            ]);
    }

    protected function blobImage($image)
    {
        $blobImage;

        switch ($image['type']){
            case $image['type'] == 'png':
                $blobImage = substr($image['image'], 22);
                break;
            case $image['type'] == 'jpeg':
                $blobImage = substr($image['image'], 23);
                break;
            case $image['type'] == 'svg':
                $blobImage = substr($image['image'], 22);
                break;
            default:
                $blobImage = '';
        }

        return $blobImage;
    }


    protected function deleteThreadImages($thread)
    {
        if (!count($thread->pictures)){
            return;
        } else {
            foreach ($thread->pictures as $picture){
                $remoteImageId = $picture->blob_id;
                $blobApiClass = new BlobApi(null, $remoteImageId);
                $blobApiClass->delete_image_url();
                Picture::where('blob_id', $remoteImageId)->delete();
            }
        }
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
