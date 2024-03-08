<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseReturnPDF extends Model
{
    use SoftDeletes;
    protected $fillable = ['purchase_return_id','path'];
    protected $table = "purchase_return_pdf";
}
