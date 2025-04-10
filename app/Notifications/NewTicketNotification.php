<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;

class NewTicketNotification extends Notification
{
    use Queueable;

    protected $ticket;

    public function __construct($ticket)
    {
        $this->ticket = $ticket;
    }

    public function via($notifiable)
    {
        return ['database']; // stores in DB
    }
    public function toDatabase($notifiable)
    {
        return [
            'title' => 'New Ticket Submitted',
            'ticket_id' => $this->ticket->id,
            'ticket_number' => $this->ticket->ticket_number,
            'created_by' => $this->ticket->created_by,
            'message' => 'A new ticket has been submitted.',
            'url' => route('admin.ticket.show', $this->ticket->id),
        ];
    }
}
