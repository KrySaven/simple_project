<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseProductSize extends Model
{
    use SoftDeletes;
    protected $table ="purchase_product_size";
    protected $fillable = ['product_detail_id','size_id','price','qty','amount'];

    public function size() {
        return $this->belongsTo(Size::class);
    }
}
