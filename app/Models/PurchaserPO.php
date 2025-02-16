<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
    ];
}
