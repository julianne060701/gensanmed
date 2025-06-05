<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;
use App\Models\PR;

class PurchaseRequestAccepted extends Notification
{
    use Queueable;

    protected $purchase;

    public function __construct(PR $purchase)
    {
        $this->purchase = $purchase;
    }

    public function via($notifiable)
    {
        return ['database']; // Store in DB for adminlte
    }

    public function toDatabase($notifiable)
    {
        return [
            'message' => 'Your purchase request (#' . $this->purchase->request_number . ') has been accepted.',
            'url' => url('/purchaser/requests/' . $this->purchase->id),
        ];
    }
}
