<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
class Province extends Model
{
	use Notifiable;
    protected $fillable =[
        'code',
        'province_en_name',
        'province_kh_name',
        'modify_date',
        'updated_at'];
    protected $table = "provinces";
    protected $primaryKey ="province_id";

    // public function district(){
    //     return $this->belongsTo('App\District','province_id');
    // }
}
