<?php

namespace Tests\Feature;

use App\Models\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Nette\Utils\Random;

use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function users_can_register()
    {
        $response = $this->post(route('register'), $this->userValidData());

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

    /** @test */
    public function the_name_is_required()
    {
        $response = $this->post(route('register'), $this->userValidData(['name' => null]));

        $response->assertSessionHasErrors('name');
    }

    /** @test */
    public function the_name_must_be_a_string()
    {
        $response = $this->post(route('register'), $this->userValidData(['name' => 313215]));

        $response->assertSessionHasErrors('name');
    }

    /** @test */
    public function the_name_may_not_be_greater_than_255_characters()
    {
        $response = $this->post(route('register'), $this->userValidData(['name' => Random::generate(256)]));

        $response->assertSessionHasErrors('name');
    }

    /** @test */
    public function the_first_name_is_required()
    {
        $response = $this->post(route('register'), $this->userValidData(['first_name' => null]));

        $response->assertSessionHasErrors('first_name');
    }

    /** @test */
    public function the_first_name_must_be_a_string()
    {
        $response = $this->post(route('register'), $this->userValidData(['first_name' => 313215]));

        $response->assertSessionHasErrors('first_name');

    }

    /** @test */
    public function the_first_name_may_not_be_greater_than_255_characters()
    {
        $response = $this->post(route('register'), $this->userValidData(['first_name' => Random::generate(256)]));

        $response->assertSessionHasErrors('first_name');
    }

    /** @test */
    public function the_last_name_is_required()
    {
        $response = $this->post(route('register'), $this->userValidData(['last_name' => null]));

        $response->assertSessionHasErrors('last_name');
    }

    /** @test */
    public function the_last_name_must_be_a_string()
    {
        $response = $this->post(route('register'), $this->userValidData(['last_name' => 313215]));

        $response->assertSessionHasErrors('last_name');

    }

    /** @test */
    public function the_last_name_may_not_be_greater_than_255_characters()
    {
        $response = $this->post(route('register'), $this->userValidData(['last_name' => Random::generate(256)]));

        $response->assertSessionHasErrors('last_name');
    }

    /** @test */
    public function the_email_is_required()
    {
        $response = $this->post(route('register'), $this->userValidData(['email' => null]));

        $response->assertSessionHasErrors('email');
    }

    /** @test */
    public function the_email_must_be_a_string()
    {
        $response = $this->post(route('register'), $this->userValidData(['email' => 313215]));

        $response->assertSessionHasErrors('email');

    }

    /** @test */
    public function the_email_must_be_a_email()
    {
        $response = $this->post(route('register'), $this->userValidData(['email' => Random::generate(15)]));

        $response->assertSessionHasErrors('email');

    }

    /** @test */
    public function the_email_may_not_be_greater_than_255_characters()
    {
        $response = $this->post(route('register'), $this->userValidData(['email' => Random::generate(256)]));

        $response->assertSessionHasErrors('email');
    }

    /** @test */
    public function the_email_must_be_unique()
    {
        User::factory()->create(['email' => 'Jorge@email.com']);

        $response = $this->post(route('register'), $this->userValidData());

        $response->assertSessionHasErrors('email');
    }

    /** @test */
    public function the_password_is_required()
    {
        $response = $this->post(route('register'), $this->userValidData(['password' => null]));

        $response->assertSessionHasErrors('password');
    }

    /** @test */
    public function the_password_must_be_a_string()
    {
        $response = $this->post(route('register'), $this->userValidData(['password' => 313215]));

        $response->assertSessionHasErrors('password');

    }

    /** @test */
    public function the_password_must_be_at_least_8_characters()
    {
        $response = $this->post(route('register'), $this->userValidData(['password' => '1234567']));

        $response->assertSessionHasErrors('password');
    }

    /** @test */
    public function the_password_must_be_a_confirmed()
    {
        $response = $this->post(route('register'), $this->userValidData([
            'password' => '12345678',
            'password_confirmation' => null,
        ]));

        $response->assertSessionHasErrors('password');

    }

    protected function userValidData($overrides = []): array
    {
        return array_merge([
            'name' => 'JorgeGarcia',
            'first_name' => 'Jorge',
            'last_name' => 'Garcia',
            'email' => 'Jorge@email.com',
            'password' => 'secret_pass',
            'password_confirmation' => 'secret_pass',
        ],$overrides);
    }
}
