<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseHistory extends Model
{
    use SoftDeletes;
    protected $table ="purchase_history";
    protected $fillable = ['purchase_id','status','date',];

}
