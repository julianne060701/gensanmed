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
        'urgency',
        'serial_number',
        'remarks',
        'image_url',
        'status',
    ];
}
