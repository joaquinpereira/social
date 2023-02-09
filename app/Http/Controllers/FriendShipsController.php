<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\FriendShip;
use Illuminate\Http\Request;

class FriendShipsController extends Controller
{
    public function store(User $recipient)
    {
        FriendShip::create([
            'sender_id' => auth()->id(),
            'recipient_id' => $recipient->id
        ]);
    }

    public function destroy(User $recipient)
    {
        FriendShip::where([
            'sender_id' => auth()->id(),
            'recipient_id' => $recipient->id
        ])->delete();
    }
}
