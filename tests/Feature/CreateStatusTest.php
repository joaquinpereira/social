<?php

namespace Tests\Feature;

use App\Models\User;
use App\Events\StatusCreated;
use App\Http\Resources\StatusResource;
use App\Models\Status;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class CreateStatusTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guests_users_can_not_create_statuses(){

        $response = $this->postJson(route('statuses.store',['body'=>'Mi primer status']));

        $response->assertStatus(401);
    }

    /** @test */
    public function an_authenticated_user_can_create_statuses()
    {
        Event::fake([StatusCreated::class]);

        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->postJson(route('statuses.store',['body'=>'Mi primer status']));

        Event::assertDispatched(StatusCreated::class, function ($e){
            return $e->status->id === Status::first()->id
                && $e->status instanceof StatusResource
                && $e->status->resource instanceof Status
                && $e instanceof ShouldBroadcast
            ;
        });

        $response->assertJson([
            'data' => ['body' =>'Mi primer status']
        ]);

        $this->assertDatabaseHas('statuses',[
            'user_id' => $user->id,
            'body' => 'Mi primer status'
        ]);

    }

    /** @test */
    public function a_status_requires_a_body()
    {

        //1 given
        $user = User::factory()->create();
        $this->actingAs($user);

        //2 When
        $response = $this->postJson(route('statuses.store',['body'=>'']));

        //3 Then
        $response->assertStatus(422);
        $response->assertJsonStructure([
            'message','errors'=>['body']
        ]);

    }

    /** @test */
    public function a_status_body_requires_a_length()
    {
        //1 given
        $user = User::factory()->create();
        $this->actingAs($user);

        //2 When
        $response = $this->postJson(route('statuses.store',['body'=>'1245']));

        //3 Then
        $response->assertStatus(422);
        $response->assertJsonStructure([
            'message','errors'=>['body']
        ]);

    }
}
