<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Collateral extends Model
{
    protected $fillable = [
        'loan_id',
        'status',
        'return_by',
        'customer_id',
        'created_by',
        'updated_by',
        'deleted_by',
    ];
}
