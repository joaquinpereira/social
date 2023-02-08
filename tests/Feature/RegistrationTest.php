<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function users_can_register()
    {
        $userData = [
            'name' => 'JorgeGarcia',
            'first_name' => 'Jorge',
            'last_name' => 'Garcia',
            'email' => 'Jorge@email.com',
            'password' => 'secret_pass',
            'password_confirmation' => 'secret_pass',
        ];

        $response = $this->post(route('register'), $userData);

        $response->assertRedirect('/');

        $this->assertDatabaseHas('users', [
            'name' => 'JorgeGarcia',
            'first_name' => 'Jorge',
            'last_name' => 'Garcia',
            'email' => 'Jorge@email.com',
        ]);

        $this->assertTrue(
            Hash::check('secret_pass', User::first()->password),
            'The password needs to be hashed'
        );
    }

}
