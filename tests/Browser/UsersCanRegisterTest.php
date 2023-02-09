<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class UsersCanRegisterTest extends DuskTestCase
{
    use DatabaseMigrations;
    /** @test */
    public function users_can_register()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/register')
                ->type('name','JoaquinPereira')
                ->type('first_name','Joaquin')
                ->type('last_name','Pereira')
                ->type('email','pereira.joaquin@gmail.com')
                ->type('password','secret_pass')
                ->type('password_confirmation','secret_pass')
                ->press('#register-btn')
                ->assertPathIs('/')
                ->assertAuthenticated()
            ;
        });
    }

    /** @test */
    public function users_can_not_register_with_invalid_information()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/register')
                ->type('name','Joaquin Pereira')
                ->type('first_name','Joaquin1321')
                ->type('last_name','Pereira1231')
                ->type('email','pereira.joaquin@gmail.com')
                ->type('password','secret_pass')
                ->type('password_confirmation','secret_pass')
                ->press('#register-btn')
                ->assertPathIs('/register')
                ->assertPresent('.invalid-feedback')
            ;
        });
    }
}
