<?php

namespace App\Http\Controllers;

use App\Thread;
use Illuminate\Http\Request;
use App\User;
use App\Events\SearchEvent;
use App\Http\Resources\UserResource;

class SearchController extends Controller
{
    /**
     * Show the search results.
     *
     * @return mixed
     */
    public function show()
    {
        if (request()->expectsJson()) {
            return Thread::search(request('q'))->paginate(25);
        }

        return view('threads.search');
    }

    public function searchFriends (Request $request)
    {
        $query = $request->get('param');
        
        if (!empty($query)) {
            $searchedFriend = UserResource::collection(User::where('username', 'like', '%' . $query . '%')->get());

            broadcast(new SearchEvent($searchedFriend));

            return response()->json('ok');
        } else {
            $searchedFriend = UserResource::collection(User::where('id', '!=', auth()->id())->get());
            broadcast(new SearchEvent($searchedFriend));

            return response()->json('empty search, but Okay!');
        }
        
    }
}
