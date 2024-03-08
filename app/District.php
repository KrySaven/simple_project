<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class District extends Model
{
    use Notifiable;
    protected $fillable =[
        'pro_id',
        'code',
        'district_name',
        'district_namekh',
        'modify_date',
        'updated_at'];
    protected $table = "districts";
    protected $primaryKey ="dis_id";

    public function province(){
        return $this->belongsTo('App\Province','pro_id');
    }

    public function commune()
    {
        return $this->hasMany('App\Commune','dis_id');
    }
}
