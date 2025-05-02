<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;

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
        'admin_attachment',
        'created_by',
        'status',
        'approval_date',
        'accepted_date',
        'PO_date',
        'total_duration',
    ];

    /**
     * Relationship: A PR belongs to a User (created_by).
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
