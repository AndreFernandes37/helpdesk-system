<?php

namespace App\Notifications;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Events\NotificationSent;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TicketActionNotification extends Notification
{
    use Queueable;

    protected $ticket;
    protected $action;

    public function __construct($ticket, $action)
    {
        $this->ticket = $ticket;
        $this->action = $action;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'ticket_id' => $this->ticket->id,
            'ticket_title' => $this->ticket->title,
            'action' => $this->action,
            'user_id' => $this->ticket->user->id,
            'user_name' => $this->ticket->user->name,
        ];
    }

    public function getIdAttribute()
    {
        return $this->ticket->id; // Garantir que o ID seja o UUID correto
    }
}