<?php

namespace App;

use App\Products;
use App\PurchasePDF;
use App\Supplier;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Purchase extends Model
{
    use Notifiable;
    use SoftDeletes;
    protected $table ="purchases";
    protected $fillable = [
        'supplier_id',
        'exchange_id',
        'amount',
        'discount',
        'grand_total',
        'status',
        'date',
        'source_image',
        'purchase_code',
        'p_no',
        'created_by',
        'updated_by',
        'deleted_by',
        'verify_date',
        'verifier',
        'accepted_date',
        'accepted_by',
        'purchase_code',
        'total_qty',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
    public function productDetails()
    {
        return $this->belongsToMany(Products::class, 'purchase_detail', 'purchase_id', 'product_id')
        ->withPivot('id','color_id', 'size_id', 'price', 'qty', 'amount', 'discount', 'total','status_id')
        ->wherePivotNull('deleted_at')
        ->withTimestamps();
    }


    /**
     * Get all of the comments for the Purchase
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function purchasePdfs()
    {
        return $this->hasMany(PurchasePDF::class, 'purchase_id', 'id');
    }
}
