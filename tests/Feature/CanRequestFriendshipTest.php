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
    public function can_request_friend_ship_test()
    {
        $this->withoutExceptionHandling();

        $sender = User::factory()->create();

        $recipient = User::factory()->create();

        $this->actingAs($sender)->postJson(route('friendships.store', $recipient));

        $this->assertDatabaseHas('friendships', [
            'sender_id' => $sender->id,
            'recipient_id' => $recipient->id,
            'accepted' => false,
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
            'accepted' => false,
        ]);

        $this->actingAs($recipient)->postJson(route('request-friendships.store', $sender));

        $this->assertDatabaseHas('friendships', [
            'sender_id' => $sender->id,
            'recipient_id' => $recipient->id,
            'accepted' => true,
        ]);

    }
}
