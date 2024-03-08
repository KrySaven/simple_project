<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class BranchDealer extends Model
{
    use Notifiable;
    use SoftDeletes;

    protected $fillable = [
        'dealer_id','name','long','late','address','description','active','created_at','update_at','deleted_at','creator_id','updater_id','deleter_id',
    ];
    public function dealer(){
        return $this->belongsTo('App\Dealer','dealer_id');

    }
}
