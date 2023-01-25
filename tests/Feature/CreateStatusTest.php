<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateStatusTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guests_users_can_not_create_statuses(){

        $response = $this->post(route('statuses.store',['body'=>'Mi primer status']));

        $response->assertRedirect('login');
    }

    /** @test */
    public function an_authenticated_user_can_create_statuses()
    {
        $this->withoutExceptionHandling();
        //1 given
        $user = User::factory()->create();
        $this->actingAs($user);

        //2 When
        $response = $this->postJson(route('statuses.store',['body'=>'Mi primer status']));

        $response->assertJson([
            'data' => ['body' =>'Mi primer status']
        ]);

        //3 Then
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
