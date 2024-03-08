<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Exchange extends Model
{
    use Notifiable;
    use SoftDeletes;

    protected $table = 'exchanges';
    protected $guarded = [];
}
