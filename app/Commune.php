<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Commune extends Model
{
    use Notifiable;
    protected $fillable =[
        'district_id',
        'code',
        'commune_name',
        'commune_namekh',
        'modify_date',
        'updated_at'];
    protected $table = "communes";
    protected $primaryKey ="com_id";

    // public function province(){
    //     return $this->belongsTo('App\Province','pro_id');
    // }
    
    public function district(){
        return $this->belongsTo('App\District','district_id');
    }

    public function village(){
        return $this->hasMany('App\Village','com_id');
    }
}
