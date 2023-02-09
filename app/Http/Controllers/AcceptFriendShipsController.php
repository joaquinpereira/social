<?php

namespace App\Http\Controllers;

use App\Models\FriendShip;
use App\Models\User;
use Illuminate\Http\Request;

class AcceptFriendShipsController extends Controller
{
    public function store(User $sender)
    {
        FriendShip::where([
            'sender_id' => $sender->id,
            'recipient_id' => auth()->id()
        ])->update(['status' => 'accepted']);
    }

    public function destroy(User $sender)
    {
        FriendShip::where([
            'sender_id' => $sender->id,
            'recipient_id' => auth()->id(),
        ])->update(['status' => 'denied']);
    }
}
