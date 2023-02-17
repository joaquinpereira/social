<?php

namespace App\Http\Controllers;

use App\Models\DatabaseNotification;
use Illuminate\Http\Request;

class ReadNotificationsController extends Controller
{
    public function store(DatabaseNotification $notification)
    {
        $notification->markAsRead();

        return $notification;
    }

    public function destroy(DatabaseNotification $notification)
    {
        $notification->markAsUnread();

        return $notification;
    }
}
