<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;
use App\Notifications\NewPurchaseRequestNotification;
use Illuminate\Bus\Queueable;

class NewPurchaseRequestNotification extends Notification
{
    protected $purchaseRequest;

    public function __construct($purchaseRequest)
    {
        $this->purchaseRequest = $purchaseRequest;
    }

    public function via($notifiable)
    {
        return ['database']; // Using database for storing the notification
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'New Purchase Request Created',
            'message' => 'A new purchase request with ID ' . $this->purchaseRequest->request_number . ' has been created.',
            // 'url' => route('head.purchase_request.show', $this->purchaseRequest->id),
        ];
    }
}
