<?php

namespace App;


use App\Size;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Unit extends Model
{
    use SoftDeletes;
    protected $table = 'units';
    protected $guarded = [];

    public function sizes(){
        return $this->hasMany('App\Size','unit_id');
    }
    public function products(){
        return $this->hasMany('App\Products','unit_id');
    }


}
