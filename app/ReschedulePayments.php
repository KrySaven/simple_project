<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReschedulePayments extends Model
{
    use SoftDeletes;
    protected $fillable = [
    	'reschedule_id',
		'sale_id', 
	    'payment_date',
	    'no',
	    'amount',
	    'interest',
	    'total',
	    'balance',
        'status'
    ];
}
