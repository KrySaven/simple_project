<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
class Salesman extends Model
{
    use Notifiable;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'username',
        'sex',
        'phone',
        'email',
        'password',
        'address',
        'url',
        'description',
        'active',
        'created_at',
        'update_at',
        'deleted_at',
        'creator_id',
        'updater_id',
        'deleter_id',
        
    ];
    public function  salesman_dealer(){
        return $this->hasMany('App\Dealer');
    }
}
