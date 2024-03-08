<?php

namespace App;

use App\Unit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Size extends Model
{
    use SoftDeletes;
    protected $fillable = [ 'unit_id','name','name_kh','size','description'];

    public function unit()
    {
        return $this->hasOne(Unit::class, 'id', 'unit_id');
    }
}
