<?php

namespace Tests\Feature;

use App\Models\FriendShip;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CanRequestFriendshipTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_request_friendship()
    {
        $this->withoutExceptionHandling();

        $sender = User::factory()->create();

        $recipient = User::factory()->create();

        $this->actingAs($sender)->postJson(route('friendships.store', $recipient));

        $this->assertDatabaseHas('friendships', [
            'sender_id' => $sender->id,
            'recipient_id' => $recipient->id,
            'status' => 'pending',
        ]);
    }

    /** @test */
    public function can_delete_friendship_request()
    {
        $this->withoutExceptionHandling();

        $sender = User::factory()->create();

        $recipient = User::factory()->create();

        FriendShip::create([
            'sender_id' => $sender->id,
            'recipient_id' => $recipient->id,
        ]);

        $this->actingAs($sender)->deleteJson(route('friendships.store', $recipient));

        $this->assertDatabaseMissing('friendships', [
            'sender_id' => $sender->id,
            'recipient_id' => $recipient->id,
        ]);
    }

    /** @test */
    public function can_accept_friend_ship_request()
    {
        $this->withoutExceptionHandling();

        $sender = User::factory()->create();

        $recipient = User::factory()->create();

        FriendShip::create([
            'sender_id' => $sender->id,
            'recipient_id' => $recipient->id,
        ]);

        $this->actingAs($recipient)->postJson(route('accept-friendships.store', $sender));

        $this->assertDatabaseHas('friendships', [
            'sender_id' => $sender->id,
            'recipient_id' => $recipient->id,
            'status' => 'accepted',
        ]);
    }

    /** @test */
    public function can_deny_friend_ship_request()
    {
        $this->withoutExceptionHandling();

        $sender = User::factory()->create();

        $recipient = User::factory()->create();

        FriendShip::create([
            'sender_id' => $sender->id,
            'recipient_id' => $recipient->id
        ]);

        $this->actingAs($recipient)->deleteJson(route('accept-friendships.destroy', $sender));

        $this->assertDatabaseHas('friendships', [
            'sender_id' => $sender->id,
            'recipient_id' => $recipient->id,
            'status' => 'denied',
        ]);
    }
}
