<?php

namespace Tests\Browser;

use App\Models\User;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class UsersCanCreateStatusesTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * A Dusk test example.
     * @test
     * @return void
     */
    public function users_can_create_statuses()
    {
        User::factory()->create();

        $this->browse(function (Browser $browser) {
            $user = User::find(1);
            $browser->loginAs($user)
                ->visit('/')
                ->type('body','Mi primer status')
                ->press('#create-status')
                ->waitForText('Mi primer status')
                ->assertSee('Mi primer status')
                ->assertSee($user->name);

        });
    }
}
