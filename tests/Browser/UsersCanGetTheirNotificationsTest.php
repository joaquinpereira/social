<?php

namespace Tests\Browser;

use App\Models\DatabaseNotification;
use App\Models\Status;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class UsersCanGetTheirNotificationsTest extends DuskTestCase
{
    use DatabaseMigrations;

    /** @test */
    public function user_can_see_their_notifications_in_the_navbar()
    {
        $user = User::factory()->create();
        $status = Status::factory()->create();

        $notification = DatabaseNotification::factory()->create([
            'notifiable_id' => $user->id,
            'data' => [
                'message' => 'Haz recibido un like',
                'link' => route('statuses.show', $status)
            ]
        ]);

        $this->browse(function (Browser $browser) use($user, $notification, $status){
            $browser->loginAs($user)
                ->visit('/')
                ->click('@notifications')->pause(1000)
                ->waitForText('Haz recibido un like')
                ->assertSee('Haz recibido un like')
                ->click("@{$notification->id}")
                ->assertUrlIs($status->path())

                ->click('@notifications')->pause(1000)
                ->waitForText('Haz recibido un like')
                ->assertSee('Haz recibido un like')
                ->click("@mark-as-read-{$notification->id}")
                ->waitFor("@mark-as-unread-{$notification->id}")
                ->assertMissing("@mark-as-read-{$notification->id}")

                ->click("@mark-as-unread-{$notification->id}")
                ->waitFor("@mark-as-read-{$notification->id}")
                ->assertMissing("@mark-as-unread-{$notification->id}")
            ;
        });
    }

    /** @test */
    public function user_can_see_their_notifications_in_real_time()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $status = Status::factory()->create(['user_id' => $user1->id]);

        $this->browse(function (Browser $browser1, Browser $browser2) use($user1, $user2, $status){

            $browser1->loginAs($user1)
                ->visit('/');

            $browser2->loginAs($user2)
                ->visit('/')
                ->waitForText($status->body)
                ->press('@like-btn')
                ->pause(1000)
            ;

            $browser1->assertSeeIn('@notifications-count', 1);

        });
    }
}
