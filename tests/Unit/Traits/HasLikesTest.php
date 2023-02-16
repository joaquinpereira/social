<?php

namespace App\Traits;


use Tests\TestCase;
use App\Models\User;
use App\Models\Like;
use App\Traits\HasLikes;
use App\Events\ModelLiked;
use Illuminate\Support\Facades\Event;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HasLikesTest extends TestCase
{
    use RefreshDatabase;

    //TODO Averiguar por que este metodo es incompatible
    // public function setUp()
    // {
    //     parent::setUp();

    //     Schema::create('model_with_likes', function($table){
    //         $table->increments('id');
    //     });
    // }

    /** @test */
    public function a_model_morph_many_likes()
    {

        $model = ModelWithLikes::create();

        Like::factory()->create([
            'likeable_id' => $model->id,
            'likeable_type' => get_class($model)
        ]);

        $this->assertInstanceOf(Like::class, $model->likes->first());
    }

    /** @test */
    public function a_model_can_be_liked_and_unlike()
    {
        $user = User::factory()->create();
        $model = ModelWithLikes::create();
        $this->actingAs($user);
        $model->like();
        $this->assertEquals(1, $model->likes()->count());
        $model->unlike();
        $this->assertEquals(0, $model->likes()->count());
    }

    /** @test */
    public function a_model_can_be_liked_once()
    {
        $user = User::factory()->create();
        $model = ModelWithLikes::create();
        $this->actingAs($user);
        $model->like();
        $this->assertEquals(1, $model->likes()->count());
        $model->like();
        $this->assertEquals(1, $model->likes()->count());
    }

    /** @test */
    public function a_model_knows_if_it_has_been_liked()
    {

        $model = ModelWithLikes::create();
        $this->assertFalse($model->isLiked());

        $user = User::factory()->create();
        $this->actingAs($user);
        $this->assertFalse($model->isLiked());
        $model->like();
        $this->assertTrue($model->isLiked());
    }

    /** @test */
    public function a_model_knows_how_many_likes_it_has()
    {

        $model = ModelWithLikes::create();
        $this->assertEquals(0, $model->likesCount());

        Like::factory(2)->create([
            'likeable_id' => $model->id,
            'likeable_type' => get_class($model)
        ]);

        $this->assertEquals(2, $model->likesCount());

    }

    /** @test */
    public function an_event_is_fired_when_a_model_is_liked()
    {
        Event::fake([ModelLiked::class]);
        Broadcast::shouldReceive('socket')->andReturn('socket-id');

        $user = User::factory()->create();
        $model = ModelWithLikes::create();
        $this->actingAs($user);
        $model->like();

        Event::assertDispatched(ModelLiked::class, function ($event){
            $this->assertInstanceOf(ModelWithLikes::class, $event->model);

            $this->assertEventChaneltype('public',$event);
             $this->assertEventChanelName($event->model->eventChannelName(),$event);
            $this->assertDontBroadcastToCurrentUser($event);

            return true;
        });
    }

    /** @test */
    public function can_get_the_channel_name()
    {
        $model = ModelWithLikes::create();

        $this->assertEquals(
            'modelwithlikes.1.likes',
            $model->eventChannelName()
        );

    }

}

class ModelWithLikes extends Model
{
    use HasFactory, HasLikes;

    public $timestamps = false;

    protected $guarded = [];
}
