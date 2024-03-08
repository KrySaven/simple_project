<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Timline_detail extends Model
{
    use Notifiable;
    // use SoftDeletes;

    protected $fillable = [
        'timeline_id', 'duration_type', 'percentage', 'duration', 'status' , 'description', 'creator_id', 'updater_id', 'deleter_id','created_at',
    ];
}
