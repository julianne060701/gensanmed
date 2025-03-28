<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_number',
        'department',
        'responsible_department',
        'concern_type',
        'equipment',
        'urgency',
        'serial_number',
        'remarks',
        'image_url',
        'status',
        'created_by',
        'approval_date' => 'datetime',
        'accepted_date' => 'datetime',
        'days_from_request',
        'completed_date' => 'datetime',
        'days_to_complete',
        'total_duration',
        'completed_by',
        'remarks_by',
        'responsible_remarks',
    ];

    /**
     * Relationship: A ticket belongs to a user (creator).
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
