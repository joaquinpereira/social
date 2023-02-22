<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;

class CommentsLikesController extends Controller
{
    public function store(Comment $comment)
    {
        $comment->like();

        return response()->json([
            'likes_count' => $comment->likesCount()
        ]);
    }

    public function destroy(Comment $comment)
    {
        $comment->unlike();

        return response()->json([
            'likes_count' => $comment->likesCount()
        ]);
    }
}
