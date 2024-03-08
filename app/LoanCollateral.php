<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LoanCollateral extends Model
{
    protected $fillable = [
        'loan_id','customer_id','collateral_detail_id'
    ];
    public function Collateral_Details(){
        return $this->hasMany('App\CollateralDetail','id','collateral_detail_id');
    }
}
