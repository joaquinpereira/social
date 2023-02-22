<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FriendsController extends Controller
{
    public function index(Request $request)
    {
        return view('friends.index', [
            'friends' => $request->user()->friends()
        ]);
    }
}
