<?php

namespace Tests\Feature;

use App\Models\DatabaseNotification;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class CanManageNotificationsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guests_users_cannot_access_notifications()
    {
        $this->getJson(route('notifications.index'))->assertStatus(401);
    }

    /** @test */
    public function authenticated_users_can_get_their_notifications()
    {
        $user = User::factory()->create()->first();
        $user_auth = Auth::loginUsingId($user->id);

        $notification = DatabaseNotification::factory()->create(['notifiable_id' => $user->id])->first();

        $this->actingAs($user_auth)->getJson(route('notifications.index'))
            ->assertJson([
                [
                    'data' => [
                        'link' => $notification->data['link'],
                        'message' => $notification->data['message'],
                    ]
                ]
            ]);
    }

    /** @test */
    public function guests_users_cannot_mark_notifications_as_read()
    {
        $notification = DatabaseNotification::factory()->create()->first();

        $this->postJson(route('read-notifications.store', $notification))->assertStatus(401);
    }

    /** @test */
    public function authenticated_users_can_mark_notifications_as_read()
    {
        $user = User::factory()->create()->first();
        $user_auth = Auth::loginUsingId($user->id);

        $notification = DatabaseNotification::factory()->create([
            'notifiable_id' => $user->id,
            'read_at' => null
        ])->first();

        $response = $this->actingAs($user_auth)->postJson(route('read-notifications.store', $notification));

        $response->assertJson($notification->fresh()->toArray());

        $this->assertNotNull($notification->fresh()->read_at);
    }

    /** @test */
    public function guests_users_cannot_mark_notifications_as_unread()
    {
        $notification = DatabaseNotification::factory()->create(['read_at' => now()]);

        $this->deleteJson(route('read-notifications.store', $notification))->assertStatus(401);
    }

    /** @test */
    public function authenticated_users_can_mark_notifications_as_unread()
    {
        $user = User::factory()->create()->first();
        $user_auth = Auth::loginUsingId($user->id);

        $notification = DatabaseNotification::factory()->create([
            'notifiable_id' => $user->id,
            'read_at' => now()
        ])->first();

        $response = $this->actingAs($user_auth)->deleteJson(route('read-notifications.destroy', $notification));

        $response->assertJson($notification->fresh()->toArray());

        $this->assertNull($notification->fresh()->read_at);
    }
}
