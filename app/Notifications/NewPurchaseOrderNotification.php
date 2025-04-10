<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Illuminate\Notifications\Notification;
use App\Models\PurchaserPO;
use App\Models\User;
class NewPurchaseOrderNotification extends Notification
{
    use Queueable;

    protected $purchaserPO;

    /**
     * Create a new notification instance.
     *
     * @param PurchaserPO $purchaserPO
     */
    public function __construct(PurchaserPO $purchaserPO)
    {
        $this->purchaserPO = $purchaserPO; // Assign the passed PurchaserPO instance
    }

    /**
     * Determine the delivery channels the notification should use.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];  // You can also include 'mail', 'broadcast', etc.
    }

    /**
     * Get the database representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
        return [
            'title' => 'New Purchase Order Created',
            'message' => 'A new purchase order with ID ' . $this->purchaserPO->po_number . ' has been created.',
            'po_number' => $this->purchaserPO->po_number,
            'name' => $this->purchaserPO->name,
            'status' => $this->purchaserPO->status,
            'created_at' => $this->purchaserPO->created_at->format('m/d/Y'),
            // 'url' => route('purchaser.purchase.show', $this->purchaserPO->id), // Optional URL to redirect to
        ];
    }
}
