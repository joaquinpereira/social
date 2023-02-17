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
                ->click('#notifications')->pause(1000)
                ->assertSee('Haz recibido un like')
                ->click("#{$notification->id}")
                ->assertUrlIs($status->path())
            ;
        });
    }
}
