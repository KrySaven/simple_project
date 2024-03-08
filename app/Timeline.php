<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Timeline extends Model
{
    use Notifiable;
    use SoftDeletes;
    protected $fillable = [
        'name','payment_type','duration','first_payment','description','creator_id','updater_id','deleter_id',
    ];
    public function  timeline_detail(){
        return $this->hasMany('App\Timline_detail','timeline_id')->orderBy('id','ASC');
    }
}
