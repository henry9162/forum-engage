<?php

namespace App\Http\Controllers\Api;

use App\User;
use App\Http\Controllers\Controller;

class UsersController extends Controller
{
    /**
     * Fetch all relevant username.
     *
     * @return mixed
     */
    // public function index()
    // {
    //     $search = request('name');

    //     return User::where('username', 'LIKE', "$search%")
    //         ->take(5)
    //         ->pluck('username');
    // }

    public function getAllUsers()
    {
        $users = User::all();
        return $users;
    }
}
