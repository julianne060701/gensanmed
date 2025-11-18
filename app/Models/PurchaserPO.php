<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class PurchaserPO extends Model
{
    use HasFactory;

    protected $table = 'purchaser_po'; // Match the database table name

    protected $fillable = [
        'po_number',
        'name',
        'description',
        'status',
        'image_url',
        'admin_attachment',
        'remarks',
        'approval_date',
        'accepted_date',
        'send_date',         // ← make sure this is included
        'total_duration',
        'created_by',        // Track who created the purchase order
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
