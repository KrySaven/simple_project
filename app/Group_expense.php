<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use App\UserBranch;

class Group_expense extends Model
{
    //
    //
    use Notifiable;
    use SoftDeletes;

    protected $fillable = [
        'branch_id',
        'group_name',
        'description',
        'created_at',
        'update_at',
        'deleted_at',
        'creator_id',
        'updater_id',
        'deleter_id'
    ];

    public function expense(){
        return $this->hasMany('App\Expense');
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
