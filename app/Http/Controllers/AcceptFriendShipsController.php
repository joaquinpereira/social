<?php

namespace App\Http\Controllers;

use App\Models\FriendShip;
use App\Models\User;
use Illuminate\Http\Request;

class AcceptFriendShipsController extends Controller
{
    public function index()
    {
        $friendshipRequests = FriendShip::with('sender')->where([
            'recipient_id' => auth()->id()
        ])->get();

        return view('friendships.index', compact('friendshipRequests'));
    }

    public function store(User $sender)
    {
        FriendShip::where([
            'sender_id' => $sender->id,
            'recipient_id' => auth()->id()
        ])->update(['status' => 'accepted']);

        return response()->json([
            'friendship_status' => 'accepted'
        ]);
    }

    public function destroy(User $sender)
    {
        FriendShip::where([
            'sender_id' => $sender->id,
            'recipient_id' => auth()->id(),
        ])->update(['status' => 'denied']);

        return response()->json([
            'friendship_status' => 'denied'
        ]);
    }
}
