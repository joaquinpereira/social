<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\FriendShip;
use Illuminate\Http\Request;

class FriendShipsController extends Controller
{
    public function show(Request $request, User $recipient)
    {
        $friendship = FriendShip::betweenUsers($request->user(), $recipient)->first();

        return response()->json([
            'friendship_status' => $friendship->status
        ]);
    }

    public function store(Request $request, User $recipient)
    {
        if(auth()->id() === $recipient->id){
            abort(400);
        }

        $friendship = $request->user()->sendFriendRequestTo($recipient);

        return response()->json([
            'friendship_status' => $friendship->fresh()->status
        ]);
    }

    public function destroy(User $user)
    {
        $friendship = FriendShip::betweenUsers(auth()->user(),$user)->first();

        if($friendship->status === 'denied' && (int)$friendship->sender_id === auth()->id()){
            return response()->json([
                'friendship_status' => 'denied'
            ]);
        }

        return response()->json([
            'friendship_status' => $friendship->delete() ? 'deleted' : ''
        ]);
    }
}
