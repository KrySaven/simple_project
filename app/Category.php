<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Category extends Model
{
    use Notifiable;
    use SoftDeletes;

    protected $table = 'categories';
    protected $guarded = [];
}
