<?php

namespace App;

use App\Supplier;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseReturn extends Model
{
    use SoftDeletes;
    protected $table = 'purchase_return';
    protected $fillable = [
        'supplier_id',
        'exchange_id',
        'amount',
        'discount',
        'grand_total',
        'status',
        'date',
        'source_image',
        'purchase_return_code',
        'generate_code',
        'purchase_code',
        'created_by',
        'updated_by',
        'deleted_by',
        'verify_date',
        'verifier',
        'accepted_date',
        'accepted_by',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
    //  public function items()
    // {
    //     return $this->hasMany(PurchaseReturnDetail::class,'id', 'purchase_return_id');
    // }
    public function productDetails()
    {
        return $this->belongsToMany(Products::class, 'purchase_return_detail', 'purchase_return_id', 'product_id')
        ->withPivot('color_id', 'size_id', 'price', 'qty', 'amount', 'discount', 'total')
        ->wherePivotNull('deleted_at')
        ->withTimestamps();
    }

    /**
     * Get all of the comments for the PurchaseReturn
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function purchaseReturnPDFs()
    {
        return $this->hasMany(PurchaseReturnPDF::class, 'purchase_return_id', 'id');
    }
}
