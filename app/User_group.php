<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;
class User_group extends Model
{
    use Notifiable;
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'group_name', 'description','creator_id','updater_id','deleter_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    public function  user(){
        return $this->hasMany('App\User');
    }
    public function  permisions(){
        return $this->hasMany('App\Permission');
    }
    public function ScopeNotDefaultUserGroup($query){
        return $query->where('group_name','<>','Super Admin');
    }
}
