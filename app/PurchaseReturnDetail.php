<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseReturnDetail extends Model
{
    use SoftDeletes;
    protected $table = "purchase_return_detail";
    protected $fillable = ['purchase_return_id','size_id','purchase_id','product_id','color_id','price','qty','amount','discount','total','deleted_at'];

    public function product() {
        return $this->belongsTo(Products::class);
    }

    public function color() {
        return $this->belongsTo(Color::class);
    }

}
