<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guests_users_can_not_update_and_store_the_avatar_file()
    {
        $user = User::factory()->create()->first();

        $this->put(route('users.update',$user), [
            'picture_update' => true,
            'avatar' => UploadedFile::fake()->image('avatar.jpg')
        ])->assertRedirect('login');;

    }

    /** @test */
    public function an_authenticated_user_can_update_and_store_the_avatar_file()
    {
        Storage::fake();

        $user = User::factory()->create()->first();
        $user = Auth::loginUsingId($user->id);

        $avatar = Storage::get('public/users_for_test/1.jpg');

        $response = $this->actingAs($user)->putJson(route('users.update', $user),[
            'picture_update' => true,
            'avatar' => UploadedFile::fake()->image('avatar.jpg')
        ]);

        $response->assertStatus(200);

        Storage::assertExists($avatar);
        Storage::assertMissing("missing.jpg");
    }
}
