<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScheduleList extends Model
{
    use HasFactory;

    protected $table = 'schedule_list';

    protected $fillable = [
        'event',
        'description',
        'from_department',
        'from_date',
        'to_date',
        'status',
        'user_id',
    ];

  //  protected $dates = ['from_date', 'to_date', 'deleted_at'];
}
