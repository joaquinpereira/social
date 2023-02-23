<?php

namespace Tests\Unit\Models;

use App\Models\Comment;
use App\Models\Status;
use App\Models\User;
use App\Traits\HasLikes;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StatusTest extends TestCase
{
    use RefreshDatabase;
    /**
     * @test
     */
    public function a_status_belongs_to_a_user()
    {
        $status = Status::factory()->create()->first();

        $this->assertInstanceOf(User::class, $status->user);
    }

    /**
     * @test
     */
    public function a_status_has_many_comments(){

        $status = Status::factory()->create()->first();

        Comment::factory()->create([
            'status_id' => $status->id,
        ]);

        $this->assertInstanceOf(Comment::class, $status->comments->first());
    }

    /**
     * @test
     */
    public function a_status_model_must_use_the_trait_has_like()
    {
        $this->assertClassUsesTrait(HasLikes::class, Status::class);
    }
}
