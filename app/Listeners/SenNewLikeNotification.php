<?php

namespace App\Listeners;

use App\Events\ModelLiked;
use App\Notifications\NewLikeNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SenNewLikeNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\ModelLiked  $event
     * @return void
     */
    public function handle(ModelLiked $event)
    {
        $event->model->user->notify(
            new NewLikeNotification($event->model, $event->likeSender)
        );
    }
}
