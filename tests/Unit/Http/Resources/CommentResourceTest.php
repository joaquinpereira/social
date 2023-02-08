<?php

namespace Tests\Unit\Http\Resources;

use App\Http\Resources\CommentResource;
use App\Http\Resources\UserResource;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentResourceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     *
     * @return void
     */
    public function a_comment_resource_must_have_the_necessary_fields()
    {
        $comment = Comment::factory()->create();

        $commentResource = CommentResource::make($comment)->resolve();

        $this->assertEquals($comment->id, $commentResource['id']);

        $this->assertEquals($comment->body, $commentResource['body']);

        $this->assertEquals(0, $commentResource['likes_count']);

        $this->assertEquals(false, $commentResource['is_liked']);

        $this->assertInstanceOf(UserResource::class, $commentResource['user']);

        $this->assertInstanceOf(User::class, $commentResource['user']->resource);

    }
}
