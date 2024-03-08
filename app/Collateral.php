<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Collateral extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'customer_id','loan_id','status','return_by','created_by','updated_by','deleted_by'
        ,'collatence_no','first_date_registeration','return_date','description','file','price'
        // 'licence_no','licence_date','north','south','west','east'
    ];

    public function Collateral_Detail(){
        return $this->hasMany('App\CollateralDetail','collateral_id');
    }

    public function customer(){
        return $this->belongsTo('App\Customer','customer_id','id');
    }

    public function User(){
        return $this->belongsTo('App\User','return_by','id');
    }

    public function getFullCollateralAttribute(){
        $collateral = config('app.collateral_type_kh')[$this->collateral_type];
        // if($this->collateral_type == 'identification_card'){
        //     return "{$this->licence_type} -- {$this->licence_type} -- {$collateral}";
        // }
        return "{$this->collateral_name} -- {$this->color} -- {$collateral} -- {$this->licence_type}";
    }

    public function scopeFilterByType($query,$type){
        if($type!=""){
            return $query->whereHas('Collateral_Detail', function($q) use ($type){
                $q->where('collateral_type', $type);
            });
        }
    }
    public function scopeFilterByStatus($query,$status){
        if($status!=""){
            return $query->whereHas('Collateral_Detail', function($q) use ($status){
                $q->where('status', $status);
            });
        }
    }
    public function scopeFilterByDate($query,$from_date,$to_date,$default_status){
        return $query->whereHas('Collateral_Detail', function($q) use ($from_date,$to_date,$default_status){
            $q->where('status',$default_status)->whereDate('created_at','>=',$from_date)->whereDate('created_at','<=',$to_date);
        });
    }
    public function scopeCountByStatus($query,$status){
        return $query->whereHas('Collateral_Detail', function($q) use ($status){
            $q->where('status',$status);
        })->withCount('Collateral_Detail')->count();//->sortBy('status');
    }
}
