<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class ProductColor extends Model
{
    use Notifiable;
    use SoftDeletes;

    protected $table = 'product_colors';
    protected $guarded = [];
}
