<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payoff extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'loan_id','date','principle','interest','insurance','admin_fee','penalty',
        'pay_off_by','created_by','updated_by','deleted_by','balance','advance_fine'
    ];
    public function Loan(){
        return $this->belongsTo('App\Sale','loan_id','id');
    }
}
