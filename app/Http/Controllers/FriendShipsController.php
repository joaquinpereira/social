<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\FriendShip;
use Illuminate\Http\Request;

class FriendShipsController extends Controller
{
    public function store(User $recipient)
    {
        $friendship = FriendShip::firstOrCreate([
            'sender_id' => auth()->id(),
            'recipient_id' => $recipient->id
        ]);

        return response()->json([
            'friendship_status' => $friendship->fresh()->status
        ]);
    }

    public function destroy(User $user)
    {
        $deleted = FriendShip::where([
            'sender_id' => auth()->id(),
            'recipient_id' => $user->id
        ])->orWhere([
            'sender_id' => $user->id,
            'recipient_id' => auth()->id()
        ])->delete();

        return response()->json([
            'friendship_status' => $deleted ? 'deleted' : ''
        ]);
    }
}
