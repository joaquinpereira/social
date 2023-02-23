<?php

namespace Tests\Unit\Models;

use App\Models\FriendShip;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FriendShipTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function a_frienship_request_belongs_to_a_sender()
    {
        $sender = User::factory()->create()->first();

        $frienship = FriendShip::factory()->create(['sender_id' => $sender->id])->first();

        $this->assertInstanceOf(User::class, $frienship->sender);
    }

    /** @test */
    public function a_frienship_request_belongs_to_a_recipient()
    {
        $recipient = User::factory()->create()->first();

        $frienship = FriendShip::factory()->create(['recipient_id' => $recipient->id])->first();

        $this->assertInstanceOf(User::class, $frienship->recipient);
    }

    /** @test */
    public function can_find_frienships_by_sender_and_recipient()
    {
        $sender = User::factory()->create()->first();
        $recipient = User::factory()->create()->first();

        FriendShip::factory(2)->create(['recipient_id' => $recipient->id]);
        FriendShip::factory(2)->create(['sender_id' => $sender->id]);

        FriendShip::create([
            'sender_id' => $sender->id,
            'recipient_id' => $recipient->id
        ]);

        $foundFriendship = FriendShip::betweenUsers($sender, $recipient)->first();

        $this->assertEquals($sender->id, $foundFriendship->sender_id);
        $this->assertEquals($recipient->id, $foundFriendship->recipient_id);

        $foundFriendship2 = FriendShip::betweenUsers($recipient, $sender)->first();

        $this->assertEquals($sender->id, $foundFriendship2->sender_id);
        $this->assertEquals($recipient->id, $foundFriendship2->recipient_id);
    }
}
