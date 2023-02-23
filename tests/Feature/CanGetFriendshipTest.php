<?php

namespace Tests\Feature;

use App\Models\FriendShip;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class CanGetFriendshipTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guests_cannot_get_friendships()
    {
        $this->getJson(route('friendships.show', 'jhon doe'))->assertStatus(401);
    }

    /** @test */
    public function can_get_friendship()
    {
        $sender = User::factory()->create()->first();
        $recipient = User::factory()->create()->first();

        $sender_auth = Auth::loginUsingId($sender->id);

        $friendship = FriendShip::create([
            'sender_id' => $sender->id,
            'recipient_id' => $recipient->id,
        ]);

        $response = $this->actingAs($sender_auth)->getJson(route('friendships.show', $recipient));

        $response->assertJsonFragment([
            'friendship_status' => $friendship->fresh()->status
        ]);
    }
}
