<?php

namespace Tests\Feature;

use App\Models\Status;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class CanLikeStatusesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guests_users_can_not_like_statuses(){

        $status = Status::factory()->create();
        $response = $this->postJson(route('statuses.likes.store',$status));

        $response->assertStatus(401);
    }

    /**
     * @test
     * @return void
     */
    public function an_authenticated_user_can_like_and_unlike_statuses()
    {
        $user = User::factory()->create()->first();
        $status = Status::factory()->create()->first();

        $user_auth = Auth::loginUsingId($user->id);

        $this->assertCount(0, $status->likes);

        //like
        $response = $this->actingAs($user_auth)->postJson(route('statuses.likes.store', $status));
        $response->assertJsonFragment([
            'likes_count' => 1
        ]);
        $this->assertCount(1, $status->fresh()->likes);
        $this->assertDatabaseHas('likes', ['user_id' => $user->id,]);

        //unlike
        $response = $this->actingAs($user_auth)->deleteJson(route('statuses.likes.destroy', $status));
        $response->assertJsonFragment([
            'likes_count' => 0
        ]);
        $this->assertCount(0, $status->fresh()->likes);
        $this->assertDatabaseMissing('likes', ['user_id' => $user->id,]);
    }


}
