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
        $sender = User::factory()->create();

        $frienship = FriendShip::factory()->create(['sender_id' => $sender->id]);

        $this->assertInstanceOf(User::class, $frienship->sender);
    }

    /** @test */
    public function a_frienship_request_belongs_to_a_recipient()
    {
        $recipient = User::factory()->create();

        $frienship = FriendShip::factory()->create(['recipient_id' => $recipient->id]);

        $this->assertInstanceOf(User::class, $frienship->recipient);
    }
}
