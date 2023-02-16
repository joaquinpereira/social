<?php

namespace Tests\Unit\Listeners;

use App\Events\ModelLiked;
use App\Models\Status;
use App\Models\User;
use App\Notifications\NewLikeNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class SenNewLikeNotificationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_notification_is_sent_when_a_user_receives_a_new_like()
    {
        Notification::fake();

        $statusOwner = User::factory()->create();
        $status = Status::factory()->create(['user_id' => $statusOwner->id]);

        ModelLiked::dispatch($status);

        Notification::assertSentTo($statusOwner, NewLikeNotification::class);

    }
}
