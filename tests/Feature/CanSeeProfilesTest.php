<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CanSeeProfilesTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function can_see_profiles_test()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create(['name' => 'Jorge']);

        $this->get('@Jorge')->assertSee('Jorge');
    }
}
