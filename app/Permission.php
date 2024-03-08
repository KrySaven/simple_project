<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
class Permission extends Model
{
 	use Notifiable;
 	protected $table = 'permissions';
    protected $fillable = [
        'user_group_id','name','creator_id','updater_id','deleter_id',
    ];
    public function User_group(){
        return $this->belongsTo('App\User_group','user_group_id');

    }
}