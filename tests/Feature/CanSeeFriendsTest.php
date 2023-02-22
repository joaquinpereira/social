<?php

namespace Tests\Feature;

use App\Models\FriendShip;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CanSeeFriendsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guesst_cannot_access_the_list_of_friends()
    {
        $this->get(route('friends.index'))->assertRedirect('login');
    }


    /** @test */
    public function a_user_can_see_a_list_of_friends()
    {
        $sender = User::factory()->create();
        $recipient = User::factory()->create();

        FriendShip::factory()->create([
            'recipient_id' => $recipient->id,
            'sender_id' => $sender->id,
            'status' => 'accepted'
        ]);

        $this->actingAs($sender)->get(route('friends.index'))->assertOk()->assertSee($recipient->name);
        $this->actingAs($recipient)->get(route('friends.index'))->assertOk()->assertSee($sender->name);
    }

}
