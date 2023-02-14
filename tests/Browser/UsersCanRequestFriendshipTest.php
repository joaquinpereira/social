<?php

namespace Tests\Browser;

use App\Models\FriendShip;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class UsersCanRequestFriendshipTest extends DuskTestCase
{
    use DatabaseMigrations;

     /** @test */
     public function guests_cannot_create_frienship_requests()
     {
         $recipient = User::factory()->create();

         $this->browse(function (Browser $browser) use ($recipient) {
             $browser->visit(route('users.show', $recipient))
                 ->press('#request-friendship')
                 ->assertPathIs('/login')
             ;
         });
     }

    /** @test */
    public function senders_can_create_and_delete_frienship_requests()
    {
        $sender = User::factory()->create();
        $recipient = User::factory()->create();

        $this->browse(function (Browser $browser) use ($sender, $recipient) {
            $browser->loginAs($sender)
                ->visit(route('users.show', $recipient))
                ->press('#request-friendship')
                ->waitForText('Cancelar solicitud')
                ->assertSee('Cancelar solicitud')
                ->visit(route('users.show', $recipient))
                ->assertSee('Cancelar solicitud')
                ->press('#request-friendship')
                ->waitForText('Solicitar amistad')
                ->assertSee('Solicitar amistad')
            ;
        });
    }

    /** @test */
    public function a_user_cannot_send_friend_request_to_itself()
    {
        $sender = User::factory()->create();

        $this->browse(function (Browser $browser) use ($sender) {
            $browser->loginAs($sender)
                ->visit(route('users.show', $sender))
                ->assertMissing('#request-friendship')
                ->assertSee('Eres tú')
            ;
        });
    }

    /** @test */
    public function senders_can_delete_accepted_frienship_requests()
    {
        $sender = User::factory()->create();
        $recipient = User::factory()->create();

        FriendShip::create([
            'sender_id' => $sender->id,
            'recipient_id' => $recipient->id,
            'status' => 'accepted'
        ]);

        $this->browse(function (Browser $browser) use ($sender, $recipient) {
            $browser->loginAs($sender)
                ->visit(route('users.show', $recipient))
                ->assertSee('Eliminar de mis amigos')
                ->press('#request-friendship')
                ->waitForText('Solicitar amistad')
                ->assertSee('Solicitar amistad')
                ->visit(route('users.show', $recipient))
                ->assertSee('Solicitar amistad')
            ;
        });
    }

     /** @test */
     public function senders_cannot_delete_deny_frienship_requests()
     {
         $sender = User::factory()->create();
         $recipient = User::factory()->create();

         FriendShip::create([
             'sender_id' => $sender->id,
             'recipient_id' => $recipient->id,
             'status' => 'denied'
         ]);

         $this->browse(function (Browser $browser) use ($sender, $recipient) {
             $browser->loginAs($sender)
                 ->visit(route('users.show', $recipient))
                 ->assertSee('Solicitud denegada')
                 ->press('#request-friendship')
                 ->waitForText('Solicitud denegada')
                 ->assertSee('Solicitud denegada')
                 ->visit(route('users.show', $recipient))
                 ->assertSee('Solicitud denegada')
             ;
         });
     }

    /** @test */
    public function recipients_can_accept_frienship_requests()
    {
        $sender = User::factory()->create();
        $recipient = User::factory()->create();

        FriendShip::create([
            'sender_id' => $sender->id,
            'recipient_id' => $recipient->id
        ]);

        $this->browse(function (Browser $browser) use ($sender, $recipient) {
            $browser->loginAs($recipient)
                ->visit(route('accept-friendships.index'))
                ->assertSee($sender->name)
                ->press('#accept-friendship')
                ->waitForText('son amigos')
                ->assertSee('son amigos')
                ->visit(route('accept-friendships.index'))
                ->assertSee('son amigos')
            ;
        });
    }

    /** @test */
    public function recipients_can_deny_frienship_requests()
    {
        $sender = User::factory()->create();
        $recipient = User::factory()->create();

        FriendShip::create([
            'sender_id' => $sender->id,
            'recipient_id' => $recipient->id
        ]);

        $this->browse(function (Browser $browser) use ($sender, $recipient) {
            $browser->loginAs($recipient)
                ->visit(route('accept-friendships.index'))
                ->assertSee($sender->name)
                ->press('#deny-friendship')
                ->waitForText('Solicitud denegada')
                ->assertSee('Solicitud denegada')
                ->visit(route('accept-friendships.index'))
                ->assertSee('Solicitud denegada')
            ;
        });
    }

    /** @test */
    public function recipients_can_delete_frienship_requests()
    {
        $sender = User::factory()->create();
        $recipient = User::factory()->create();

        FriendShip::create([
            'sender_id' => $sender->id,
            'recipient_id' => $recipient->id
        ]);

        $this->browse(function (Browser $browser) use ($sender, $recipient) {
            $browser->loginAs($recipient)
                ->visit(route('accept-friendships.index'))
                ->assertSee($sender->name)
                ->press('#delete-friendship')
                ->waitForText('Solicitud eliminada')
                ->assertSee('Solicitud eliminada')
                ->visit(route('accept-friendships.index'))
                ->assertDontSee('Solicitud eliminada')
                ->assertDontSee($sender->name)
            ;
        });
    }
}
