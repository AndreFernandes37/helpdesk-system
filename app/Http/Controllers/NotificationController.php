<?php
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Routing\Controller;


class NotificationController extends Controller
{
    public function markAsRead(DatabaseNotification $notification)
    {
        $notification->markAsRead();
        return redirect()->back();
    }
}
