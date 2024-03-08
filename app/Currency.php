<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Currency extends Model
{
    use Notifiable;
    use SoftDeletes;

    protected $table = 'currencies';
    protected $guarded = [];
}
