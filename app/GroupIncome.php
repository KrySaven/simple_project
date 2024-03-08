<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
class GroupIncome extends Model
{
    use Notifiable;
    use SoftDeletes;

    protected $fillable = [
    	'branch_id',
        'name',
        'description',
        'created_at',
        'update_at',
        'deleted_at',
        'creator_id',
        'updater_id',
        'deleter_id'
    ];
    public function income(){
        return $this->hasMany('App\Income');
    }
    public function branch(){
        return $this->hasOne(Branch::class, 'id', 'branch_id');
    }
    public function getBranchNameAttribute(){
        return $this->branch->name_en??"";
    }
    public function scopeWithBranch($query){
       $user_branch_ids = UserBranch::LoggedUserBranchIds();
       if(count((array)$user_branch_ids)>0){
        return $query->whereIn('branch_id', $user_branch_ids);
       }else{
        return $query;
       }
    }
}
