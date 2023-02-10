<?php

namespace App\Http\Controllers;

use App\Models\FriendShip;
use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function show(User $user)
    {
        $friendshipStatus = optional(FriendShip::where([
            'recipient_id' => $user->id,
            'sender_id' => auth()->id()
        ])->first())->status;
        return view('users.show', compact('user','friendshipStatus'));
    }
}
