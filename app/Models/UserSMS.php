<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserSMS extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'users_sms'; // Explicitly set the table name

    protected $fillable = ['name', 'phone']; // Fields that can be mass assigned

    public function groups()
    {
        return $this->belongsToMany(SmsGroup::class, 'sms_group_user');
    }
}
