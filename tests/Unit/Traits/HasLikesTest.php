<?php

namespace App\Traits;

use App\Models\Like;
use App\Models\Status;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HasLikesTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function a_model_morph_many_likes()
    {

        $model = new ModelWithLikes(['id' => 1]);

        Like::factory()->create([
            'likeable_id' => $model->id,
            'likeable_type' => get_class($model)
        ]);

        $this->assertInstanceOf(Like::class, $model->likes->first());
    }

    /**
     * @test
     */
    public function a_model_can_be_liked_and_unlike()
    {
        $user = User::factory()->create();
        $model = new ModelWithLikes(['id' => 1]);
        $this->actingAs($user);
        $model->like();
        $this->assertEquals(1, $model->likes()->count());
        $model->unlike();
        $this->assertEquals(0, $model->likes()->count());
    }

    /**
     * @test
     */
    public function a_model_can_be_liked_once()
    {
        $user = User::factory()->create();
        $model = new ModelWithLikes(['id' => 1]);
        $this->actingAs($user);
        $model->like();
        $this->assertEquals(1, $model->likes()->count());
        $model->like();
        $this->assertEquals(1, $model->likes()->count());
    }

    /**
     * @test
     */
    public function a_model_knows_if_it_has_been_liked()
    {

        $model = new ModelWithLikes(['id' => 1]);
        $this->assertFalse($model->isLiked());

        $user = User::factory()->create();
        $this->actingAs($user);
        $this->assertFalse($model->isLiked());
        $model->like();
        $this->assertTrue($model->isLiked());
    }

    /**
     * @test
     */
    public function a_model_knows_how_many_likes_it_has()
    {

        $model = new ModelWithLikes(['id' => 1]);
        $this->assertEquals(0, $model->likesCount());

        Like::factory(2)->create([
            'likeable_id' => $model->id,
            'likeable_type' => get_class($model)
        ]);

        $this->assertEquals(2, $model->likesCount());

    }
}

class ModelWithLikes extends Model
{
    use HasFactory, HasLikes;

    protected $guarded = [];
}
