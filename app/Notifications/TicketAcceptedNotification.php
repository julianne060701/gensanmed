<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;
use App\Models\Ticket;

class TicketAcceptedNotification extends Notification
{
    use Queueable;

    protected $ticket;

    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket;
    }

    public function via($notifiable)
    {
        return ['database', 'mail']; // You can use 'mail', 'database', 'broadcast', etc.
    }

    public function toDatabase($notifiable)
    {
        return [
            'ticket_id' => $this->ticket->id,
            'ticket_number' => $this->ticket->ticket_number,
            'title' => 'Ticket Accepted',
            'message' => 'Your ticket #' . $this->ticket->ticket_number . ' has been accepted by the admin.',
            'url' => route('admin.ticket.show', $this->ticket->id),
            'status' => $this->ticket->status,
        ];
    }
    
}

