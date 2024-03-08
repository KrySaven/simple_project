<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Color extends Model
{
    use Notifiable;
    use SoftDeletes;

    protected $table = 'colors';
    protected $guarded = [];

     public function product()
    {
        return $this->belongsToMany(Products::class, 'product_colors', 'product_id', 'color_id')
        ->wherePivot('deleted_at', null);

    }
}
