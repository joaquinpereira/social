<?php

namespace Tests\Feature;

use App\Models\Status;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ListStatusesTest extends TestCase
{
    use RefreshDatabase;
    /**
     * @test
     *
     * @return void
     */
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
}
