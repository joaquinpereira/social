<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\FriendShip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FriendShipsController extends Controller
{
    public function store(User $recipient)
    {
        if(auth()->id() === $recipient->id){
            abort(400);
        }

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
