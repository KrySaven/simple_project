<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
class Investment extends Model
{
    use Notifiable;
    use SoftDeletes;
    protected $fillable = [
        'shareholder', 'date', 'amount','percentage','description', 'creator_id', 'updater_id', 'deleter_id',
    ];
}
