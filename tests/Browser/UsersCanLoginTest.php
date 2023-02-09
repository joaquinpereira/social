<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class UsersCanLoginTest extends DuskTestCase
{
    use DatabaseMigrations;
    /**
     * @test
     *
     * @return void
     */
    public function registered_users_can_login()
    {
        User::factory()->create(['email' => 'jorge@gmail.com']);

        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->type('email', 'jorge@gmail.com')
                ->type('password', 'password')
                ->press('#login-btn')
                ->assertPathIs('/')
                ->assertAuthenticated();
        });
    }

    /** @test */
    public function users_can_not_login_with_invalid_information()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->type('email','')
                ->type('password','secret_pass')
                ->press('#login-btn')
                ->assertPathIs('/login')
                ->assertPresent('.invalid-feedback')
            ;
        });
    }
}
