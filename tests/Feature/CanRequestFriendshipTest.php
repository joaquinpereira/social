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
    public function guests_users_cannot_create_friendship_request()
    {
        $recipient = User::factory()->create();

        $response = $this->postJson(route('friendships.store', $recipient));

        $response->assertStatus(401);
    }

    /** @test */
    public function sender_a_can_create_friendship_request_once()
    {

        $sender = User::factory()->create();

        $recipient = User::factory()->create();

        $this->actingAs($sender)->postJson(route('friendships.store', $recipient));

        $this->assertDatabaseHas('friendships', [
            'sender_id' => $sender->id,
            'recipient_id' => $recipient->id,
            'status' => 'pending',
        ]);

        $this->actingAs($sender)->postJson(route('friendships.store', $recipient));
        $this->assertCount(1, FriendShip::all());
    }

    /** @test */
    public function sender_can_create_friendship_request()
    {
        $this->withoutExceptionHandling();

        $sender = User::factory()->create();

        $recipient = User::factory()->create();

        $response = $this->actingAs($sender)->postJson(route('friendships.store', $recipient));

        $response->assertJson([
            'friendship_status' => 'pending'
        ]);

        $this->assertDatabaseHas('friendships', [
            'sender_id' => $sender->id,
            'recipient_id' => $recipient->id,
            'status' => 'pending',
        ]);
    }

    /** @test */
    public function sender_cannot_send_friendship_request_to_itself()
    {

        $sender = User::factory()->create();

        $response = $this->actingAs($sender)->postJson(route('friendships.store', $sender));

        $response->assertStatus(400);

        $this->assertDatabaseMissing('friendships', [
            'sender_id' => $sender->id,
            'recipient_id' => $sender->id,
            'status' => 'pending',
        ]);
    }

    /** @test */
    public function senders_can_delete_friendship_request()
    {
        $this->withoutExceptionHandling();

        $sender = User::factory()->create();

        $recipient = User::factory()->create();

        FriendShip::create([
            'sender_id' => $sender->id,
            'recipient_id' => $recipient->id,
        ]);

        $response = $this->actingAs($sender)->deleteJson(route('friendships.destroy', $recipient));

        $response->assertJson([
            'friendship_status' => 'deleted'
        ]);

        $this->assertDatabaseMissing('friendships', [
            'sender_id' => $sender->id,
            'recipient_id' => $recipient->id,
        ]);
    }

    /** @test */
    public function senders_cannot_delete_dennied_friendship_request()
    {
        $this->withoutExceptionHandling();

        $sender = User::factory()->create();

        $recipient = User::factory()->create();

        FriendShip::create([
            'sender_id' => $sender->id,
            'recipient_id' => $recipient->id,
            'status' => 'denied'
        ]);

        $response = $this->actingAs($sender)->deleteJson(route('friendships.destroy', $recipient));

        $response->assertJson([
            'friendship_status' => 'denied'
        ]);

        $this->assertDatabaseHas('friendships', [
            'sender_id' => $sender->id,
            'recipient_id' => $recipient->id,
            'status' => 'denied'
        ]);
    }

    /** @test */
    public function recipients_can_delete_recieved_friendship_request()
    {
        $this->withoutExceptionHandling();

        $sender = User::factory()->create();

        $recipient = User::factory()->create();

        FriendShip::create([
            'sender_id' => $sender->id,
            'recipient_id' => $recipient->id,
        ]);

        $response = $this->actingAs($recipient)->deleteJson(route('friendships.destroy', $sender));

        $response->assertJson([
            'friendship_status' => 'deleted'
        ]);

        $this->assertDatabaseMissing('friendships', [
            'sender_id' => $sender->id,
            'recipient_id' => $recipient->id,
        ]);
    }

    /** @test */
    public function guests_users_cannot_delete_friendship_request()
    {
        $recipient = User::factory()->create();

        $response = $this->deleteJson(route('friendships.destroy', $recipient));

        $response->assertStatus(401);
    }

    /** @test */
    public function recipients_can_accept_friendship_request()
    {
        $this->withoutExceptionHandling();

        $sender = User::factory()->create();

        $recipient = User::factory()->create();

        FriendShip::create([
            'sender_id' => $sender->id,
            'recipient_id' => $recipient->id,
        ]);

        $response = $this->actingAs($recipient)->postJson(route('accept-friendships.store', $sender));

        $response->assertJson([
            'friendship_status' => 'accepted'
        ]);

        $this->assertDatabaseHas('friendships', [
            'sender_id' => $sender->id,
            'recipient_id' => $recipient->id,
            'status' => 'accepted',
        ]);
    }

    /** @test */
    public function guests_users_cannot_visit_friendship_index()
    {
        $response = $this->get(route('accept-friendships.index'));

        $response->assertRedirect('login');
    }

    /** @test */
    public function guests_users_cannot_accept_friendship_request()
    {
        $user = User::factory()->create();

        $response = $this->postJson(route('accept-friendships.store', $user));

        $response->assertStatus(401);
    }

    /** @test */
    public function recipients_can_deny_friendship_request()
    {
        $this->withoutExceptionHandling();

        $sender = User::factory()->create();

        $recipient = User::factory()->create();

        FriendShip::create([
            'sender_id' => $sender->id,
            'recipient_id' => $recipient->id
        ]);

        $response = $this->actingAs($recipient)->deleteJson(route('accept-friendships.destroy', $sender));

        $response->assertJson([
            'friendship_status' => 'denied'
        ]);

        $this->assertDatabaseHas('friendships', [
            'sender_id' => $sender->id,
            'recipient_id' => $recipient->id,
            'status' => 'denied',
        ]);
    }

    /** @test */
    public function guests_users_cannot_deny_friend_ship_request()
    {
        $user = User::factory()->create();

        $response = $this->deleteJson(route('accept-friendships.destroy', $user));

        $response->assertStatus(401);
    }
}
