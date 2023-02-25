<?php

namespace Tests\Unit\Http\Resources;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserResourceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     *
     * @return void
     */
    public function a_user_resource_must_have_the_necessary_fields()
    {
        $user = User::factory()->create()->first();

        $userResource = UserResource::make($user)->resolve();

        $this->assertEquals($user->name, $userResource['name']);

        $this->assertEquals($user->link(), $userResource['link']);

        $this->assertEquals($user->avatar, $userResource['avatar']);

    }
}
