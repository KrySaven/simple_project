<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductFile extends Model
{
    use SoftDeletes;
    protected $table ="product_pdf";
    protected $fillable = ['product_id','path'];

}
