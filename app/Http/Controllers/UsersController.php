<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        if($request->has('picture_update') && $user->is(auth()->user()))
        {
            $request->validate(['avatar' => 'required|image|max:2048']);
            $user->savePicture($request->avatar);
        }

        return UserResource::make($user);
    }
}
