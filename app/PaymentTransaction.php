<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentTransaction extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'loan_id','payment_id','date','pay_amount','principle','interest','insurance','status','paid_by','balance','advance_fine','penalty'
    ];
    public function Payments(){
        return $this->belongsTo('App\Payment','payment_id')->orderBy('id','ASC');
    }
}
