<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reschedule extends Model
{
    use SoftDeletes;
    protected $fillable = [
    	'sale_id',
		'new_term', 
	    'interest_rate',
	    'loan_amount',
	    'old_duration_type',
	    'old_admin_fee',
	    'old_pay_type',
	    'old_sale_date',
        'old_first_payment',
        'old_interest_rate',
        'paid_term',
        'principle_paid',
        'interest_paid',
        'pay_type',
        'duration_type',
        'status',
        'drawdown_principle',
        'principle_amount',
        'interest_amount'
    ];
    public function sale(){
        return $this->hasOne(Sale::class,'id','sale_id');
    }
    public function reschedules(){
        return $this->hasMany('App\ReschedulePayments','reschedule_id')->orderBy('no','ASC');
    }
}
