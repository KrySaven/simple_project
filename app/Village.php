<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Village extends Model
{
    use Notifiable;
    protected $fillable =[
        'commune_id',
        'code',
        'village_name',
        'village_namekh',
        'modify_date',
        'updated_at'];
    protected $table = "villages";
    protected $primaryKey ="vill_id";

    public function commune(){
        return $this->belongsTo('App\Commune', 'commune_id');
    }
}
