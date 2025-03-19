<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pr extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'pr'; // Ensure the correct table name

    protected $fillable = [
        'request_number',
        'requester_name',
        'po_number',
        'description', // Added description field
        'remarks',
        'attachment_url',
        'created_by',
        'status',
        'approval_date',
        'accepted_date',
    ];

    /**
     * Relationship: A PR belongs to a User (created_by).
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
