<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseDetail extends Model
{
    use SoftDeletes;
    protected $table ="purchase_detail";
    protected $fillable = ['purchase_return_id','size_id','purchase_id','product_id','color_id','price','qty','amount','discount','total','deleted_at','status_id','status_date'];

    public function product()
    {
        return $this->belongsTo(Products::class);
    }

    public function color()
    {
        return $this->belongsTo(Color::class);
    }

    public function size_qty()
    {
        return $this->hasMany(PurchaseProductSize::class, 'product_detail_id');
    }

    /**
     * Get the user associated with the PurchaseDetail
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function productDetailStatus()
    {
        return $this->hasOne(Status::class, 'status_id', 'id');
    }
}
