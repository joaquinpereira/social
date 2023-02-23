<?php

namespace Tests\Feature;

use App\Models\Status;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class ListStatusesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_get_all_statuses()
    {
        $this->withoutExceptionHandling();

        $statuses = Status::factory(4)->create(['created_at' => now()->subDays(rand(1,10))]);

        $response = $this->getJson(route('statuses.index'));

        $response->assertSuccessful();

        $response->assertJson([
            'meta' => ['total' => 4]
        ]);

        $response->assertJsonStructure([
            'data',
            'links' =>['prev', 'next']
        ]);

        $this->assertEquals(
            $statuses->get(0)->body,
            $response->json('data.0.body')
        );
    }

    /** @test */
    public function can_get_statuses_for_a_specific_user()
    {
        $user = User::factory()->create()->first();
        $user = Auth::loginUsingId($user->id);

        Status::factory(3)->create();
        Status::factory(3)->create(['user_id' => $user->id, 'created_at' => now()->subDays(rand(2,6))]);
        $status = Status::factory(1)->create(['user_id' => $user->id, 'created_at' => now()->subDays()])->first();

        Status::factory(2)->create();

        $response = $this->actingAs($user)
            ->getJson(route('users.statuses.index', $user));

        $response->assertSuccessful();

        $response->assertJson([
            'meta' => ['total' => 4]
        ]);

        $response->assertJsonStructure([
            'data',
            'links' =>['prev', 'next']
        ]);

        $this->assertEquals(
            $status->body,
            $response->json('data.0.body')
        );
    }

    /** @test */
    public function can_see_individual_status()
    {
        $status = Status::factory()->create()->first();

        $response = $this->get($status->path());
        $response->assertSee($status->body);
    }
}
