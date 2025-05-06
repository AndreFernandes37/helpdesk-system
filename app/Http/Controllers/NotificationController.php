<?php
namespace App\Http\Controllers;


use Illuminate\Notifications\DatabaseNotification;  // Importando o modelo correto
use Illuminate\Http\Request;



class NotificationController extends Controller
{
    // Marcar como lida
    public function markAsRead($id)
    {
        $notification = DatabaseNotification::findOrFail($id);  // Usando DatabaseNotification
        $notification->markAsRead();  // Marca a notificação como lida
        
        return redirect()->back()->with('success', 'Notificação marcada como lida.');
    }
}
