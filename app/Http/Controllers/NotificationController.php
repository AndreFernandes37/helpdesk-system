<?php
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Routing\Controller;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Notification;
use App\Models\Notification as AppNotification;


class NotificationController extends Controller
{
    // Marcar como lida
    public function markAsRead($id)
    {
        $notification = AppNotification::findOrFail($id);
        $notification->markAsRead();  // Marca a notificação como lida
        
        return redirect()->back()->with('success', 'Notificação marcada como lida.');
    }
}
