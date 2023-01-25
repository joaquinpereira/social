<?php

namespace Tests\Feature;

use App\Models\Status;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CanLikeStatusesTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     * @test
     * @return void
     */
    public function an_authenticated_user_can_like_statuses()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $status = Status::factory()->create();

        $this->actingAs($user)->postJson(route('statuses.likes.store', $status));

        $this->assertDatabaseHas('likes', [
            'user_id' => $user->id,
            'status_id' => $status->id,
        ]);
    }

    /** @test */
    public function guests_users_can_not_like_statuses(){

        $status = Status::factory()->create();
        $response = $this->post(route('statuses.likes.store',$status));

        $response->assertRedirect('login');
    }


}
