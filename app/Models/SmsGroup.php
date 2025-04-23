<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SmsGroup extends Model
{
    protected $table = 'sms_groups';
    protected $fillable = ['name'];

    public function users()
    {
        return $this->belongsToMany(UserSMS::class, 'sms_group_user');
    }
}
