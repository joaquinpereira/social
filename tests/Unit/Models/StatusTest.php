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
    public function a_status_can_be_liked_and_unlike(){
        $user = User::factory()->create();
        $status = Status::factory()->create();
        $this->actingAs($user);
        $status->like();
        $this->assertEquals(1, $status->fresh()->likes->count());
        $status->unlike();
        $this->assertEquals(0, $status->fresh()->likes->count());
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

    /**
     * @test
     *
     * @return void
     */
    public function a_status_knows_if_it_has_been_liked(){

        $status = Status::factory()->create();
        $this->assertFalse($status->isLiked());

        $user = User::factory()->create();
        $this->actingAs($user);
        $status->like();
        $this->assertTrue($status->isLiked());
    }

    /**
     * @test
     *
     * @return void
     */
    public function a_status_knows_how_many_likes_it_has(){

        $status = Status::factory()->create();
        $this->assertEquals(0, $status->likesCount());

        Like::factory(2)->create(['status_id' => $status->id]);

        $this->assertEquals(2, $status->likesCount());

    }
}
