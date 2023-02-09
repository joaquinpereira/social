<?php

namespace Tests\Feature;

use App\Models\Status;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ListStatusesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_get_all_statuses()
    {
        $this->withoutExceptionHandling();

        $statuses = [];
        $j = 1;
        for ($i = 4; $i > 0; $i--){
            $statuses[$j] = Status::factory()->create(['created_at' => now()->subDays($i)]);
            $j++;
        }

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
            $statuses[4]->body,
            $response->json('data.0.body')
        );
    }

    /** @test */
    public function can_get_statuses_for_a_specific_user()
    {
        $user = User::factory()->create();

        Status::factory()->create(['user_id' => $user->id, 'created_at' => now()->subDays(2)]);
        $status = Status::factory()->create(['user_id' => $user->id, 'created_at' => now()->subDays()]);

        Status::factory(2)->create();

        $response = $this->actingAs($user)
            ->getJson(route('users.statuses.index', $user));

        $response->assertSuccessful();

        $response->assertJson([
            'meta' => ['total' => 2]
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
}
