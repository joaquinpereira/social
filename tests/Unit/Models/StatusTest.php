<?php

namespace Tests\Unit\Models;

use App\Models\Like;
use App\Models\Status;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StatusTest extends TestCase
{
    use RefreshDatabase;
    /**
     * @test
     *
     * @return void
     */
    public function a_status_belongs_to_a_user()
    {
        $status = Status::factory()->create();

        $this->assertInstanceOf(User::class, $status->user);
    }

    /**
     * @test
     *
     * @return void
     */
    public function a_status_has_many_likes(){
        $user = User::factory()->create();

        $status = Status::factory()->create();

        Like::factory()->create([
            'status_id' => $status->id,
            'user_id' => $user->id
        ]);

        $this->assertInstanceOf(Like::class, $status->likes->first());
    }

    /**
     * @test
     *
     * @return void
     */
    public function a_status_can_be_liked(){
        $user = User::factory()->create();
        $status = Status::factory()->create();
        $this->actingAs($user);
        $status->like();
        $this->assertEquals(1, $status->likes->count());
    }

        /**
     * @test
     *
     * @return void
     */
    public function a_status_can_be_liked_once(){
        $user = User::factory()->create();
        $status = Status::factory()->create();
        $this->actingAs($user);
        $status->like();
        $this->assertEquals(1, $status->likes->count());
        $status->like();
        $this->assertEquals(1, $status->likes->count());
    }
}
