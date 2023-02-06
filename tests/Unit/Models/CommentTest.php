<?php

namespace Tests\Unit\Models;

use App\Models\Comment;
use App\Models\Like;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function a_comment_belongs_to_a_user()
    {
        $comment = Comment::factory()->create();

        $this->assertInstanceOf(User::class, $comment->user);
    }

     /**
     * @test
     */
    public function a_comment_morph_many_likes(){

        $comment = Comment::factory()->create();

        Like::factory()->create([
            'likeable_id' => $comment->id,
            'likeable_type' => get_class($comment)
        ]);

        $this->assertInstanceOf(Like::class, $comment->likes->first());
    }

    /**
     * @test
     */
    public function a_comment_can_be_liked_and_unlike(){
        $user = User::factory()->create();
        $comment = Comment::factory()->create();
        $this->actingAs($user);
        $comment->like();
        $this->assertEquals(1, $comment->fresh()->likes->count());
        $comment->unlike();
        $this->assertEquals(0, $comment->fresh()->likes->count());
    }

    /**
     * @test
     */
    public function a_comment_can_be_liked_once(){
        $user = User::factory()->create();
        $comment = Comment::factory()->create();
        $this->actingAs($user);
        $comment->like();
        $this->assertEquals(1, $comment->likes->count());
        $comment->like();
        $this->assertEquals(1, $comment->likes->count());
    }

    /**
     * @test
     */
    public function a_comment_knows_if_it_has_been_liked(){

        $comment = Comment::factory()->create();
        $this->assertFalse($comment->isLiked());

        $user = User::factory()->create();
        $this->actingAs($user);
        $this->assertFalse($comment->isLiked());
        $comment->like();
        $this->assertTrue($comment->isLiked());
    }

    /**
     * @test
     */
    public function a_comment_knows_how_many_likes_it_has(){

        $comment = Comment::factory()->create();
        $this->assertEquals(0, $comment->likesCount());

        Like::factory(2)->create([
            'likeable_id' => $comment->id,
            'likeable_type' => get_class($comment)
        ]);

        $this->assertEquals(2, $comment->likesCount());

    }
}
