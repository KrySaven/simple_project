<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CollateralDetail extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'collateral_id','collateral_type','collateral_name','color','licence_type',
        'year_of_mfg','engine_no','frame_no','first_date_registeration','file','status',
        'return_date','return_by','description',
        'licence_no','licence_date','north','south','west','east','price','size'
    ];

    public function collaterals(){
        return $this->belongsTo('App\Collateral','collateral_id');
    }
    public function User(){
        return $this->belongsTo('App\User','return_by','id');
    }
    public function getFullCollateralAttribute(){
        $collateral = config('app.collateral_type_kh')[$this->collateral_type];
        // if($this->collateral_type == 'identification_card'){
        //     return "{$this->licence_type} -- {$this->licence_type} -- {$collateral}";
        // }
        return "{$this->collateral_name} -- {$this->price} ";
    }

    // public function getFullCollateralAttribute(){
    //     $collateral = config('app.collateral_type_kh')[$this->collateral_type];
    //     return " Collateral Name : {$this->collateral_name} :  Price : {$this->price} : Type  {$collateral} -- {$this->licence_type}";
    // }

    public function getPriceCollateralAttribute(){
        $collateral = config('app.collateral_type_kh')[$this->collateral_type];
        return "{$this->price}";
    }
}
