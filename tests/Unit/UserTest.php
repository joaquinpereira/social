<?php

namespace Tests\Unit;

use App\Models\FriendShip;
use App\Models\Status;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

use function React\Promise\Stream\first;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function route_key_name_is_set_to_name()
    {
        $user = User::factory()->create()->first();

        $this->assertEquals('name', $user->getRouteKeyName(), 'the route key name must be name');
    }

    /** @test */
    public function user_has_a_link_to_their_profile()
    {
        $user = User::factory()->create()->first();

        $this->assertEquals(route('users.show',$user),$user->link());
    }

    /** @test */
    public function user_has_an_avatar()
    {
        $user = User::factory()->create()->first();

        $this->assertEquals('http://social/avatar.png', $user->avatar());
        $this->assertEquals('http://social/avatar.png', $user->avatar);
    }

    /** @test */
    public function a_users_has_many_statuses()
    {
        $user = User::factory()->create()->first();

        Status::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(Status::class, $user->statuses->first());
    }

    /** @test */
    public function a_user_can_send_friend_requests()
    {
        $sender = User::factory()->create()->first();
        $recipient = User::factory()->create()->first();

        $friendship = $sender->sendFriendRequestTo($recipient);

        $this->assertTrue($friendship->sender->is($sender));
        $this->assertTrue($friendship->recipient->is($recipient));
    }

    /** @test */
    public function a_user_can_accept_friend_requests()
    {
        $sender = User::factory()->create()->first();
        $recipient = User::factory()->create()->first();

        $sender->sendFriendRequestTo($recipient);
        $friendship = $recipient->acceptFriendRequestFrom($sender);

        $this->assertEquals('accepted', $friendship->status);

    }

    /** @test */
    public function a_user_can_deny_friend_requests()
    {
        $sender = User::factory()->create()->first();
        $recipient = User::factory()->create()->first();

        $sender->sendFriendRequestTo($recipient);
        $friendship = $recipient->denyFriendRequestFrom($sender);

        $this->assertEquals('denied', $friendship->status);

    }

    /** @test */
    public function a_user_can_get_all_their_friend_requests()
    {
        $sender = User::factory(1)->create()->first();
        $recipient = User::factory(1)->create()->first();

        $sender->sendFriendRequestTo($recipient);

        $this->assertCount(0, $recipient->friendshipRequestsSent);
        $this->assertCount(1, $recipient->friendshipRequestsReceived);
        $this->assertInstanceOf(FriendShip::class, $recipient->friendshipRequestsReceived->first());

        $this->assertCount(1, $sender->friendshipRequestsSent);
        $this->assertCount(0, $sender->friendshipRequestsReceived);
        $this->assertInstanceOf(FriendShip::class, $sender->friendshipRequestsSent->first());
    }

    /** @test */
    public function a_user_can_get_their_friends()
    {
        $sender = User::factory()->create()->first();
        $recipient = User::factory()->create()->first();

        $sender->sendFriendRequestTo($recipient);

        $this->assertCount(0, $recipient->friends());
        $this->assertCount(0, $sender->friends());

        $recipient->acceptFriendRequestFrom($sender);

        $this->assertCount(1, $recipient->friends());
        $this->assertCount(1, $sender->friends());
        $this->assertEquals($recipient->name, $sender->friends()->first()->name);
        $this->assertEquals($sender->name, $recipient->friends()->first()->name);
    }
}
