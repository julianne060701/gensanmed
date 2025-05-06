<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;
use App\Models\User;
use App\Jobs\SendEventCreatedNotification;

class EventCreatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $eventTitle;
    protected $eventDescription;

    public function __construct($eventTitle, $eventDescription)
    {
        $this->eventTitle = $eventTitle;
        $this->eventDescription = $eventDescription;
    }

    public function via($notifiable)
    {
        return ['database']; // Store notification in DB
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'New Event Created',
            'message' => $this->eventTitle . ' - ' . $this->eventDescription,
            'url' => route('admin.schedule.calendar'),
        ];
    }

    public function toArray($notifiable)
{
    return [
        'title' => 'New Event Created',
        'message' => $this->eventTitle . ' - ' . $this->eventDescription,
        'url' => route('admin.schedule.calendar'),
    ];
}

}
