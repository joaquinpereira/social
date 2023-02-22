<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class CanLikeCommentsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     * @return void
     */
    public function guests_users_can_not_like_commenst()
    {
        $comment = Comment::factory()->create()->first();
        $response = $this->postJson(route('comments.likes.store', $comment));

        $response->assertStatus(401);
    }

    /**
     * @test
     * @return void
     */
    public function an_authenticated_user_can_like_and_unlike_comments()
    {
        Notification::fake();

        $this->withoutExceptionHandling();

        $user = User::factory()->create()->first();
        $comment = Comment::factory()->create()->first();

        $this->assertCount(0, $comment->likes);

        //like
        $response = $this->actingAs($user)->postJson(route('comments.likes.store', $comment));
        $response->assertJsonFragment([
            'likes_count' => 1
        ]);
        $this->assertCount(1, $comment->fresh()->likes);
        $this->assertDatabaseHas('likes', ['user_id' => $user->id,]);

        //unlike
        $response = $this->actingAs($user)->deleteJson(route('comments.likes.destroy', $comment));
        $this->assertCount(0, $comment->fresh()->likes);
        $response->assertJsonFragment([
            'likes_count' => 0
        ]);
        $this->assertDatabaseMissing('likes', ['user_id' => $user->id,]);
    }


}
