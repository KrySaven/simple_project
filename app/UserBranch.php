<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;
class UserBranch extends Model
{
    use SoftDeletes;
    protected $fillable = [
    	'user_id',
    	'branch_id',
    	'created_by'
    ];
    public function branch(){
    	return $this->hasOne(Branch::class, 'id', 'branch_id');
    }
    public function getBranchNameAttribute(){
    	$branch = $this->branch??'';
    	return $branch->branch_name??'';
    }
    public static function LoggedUserBranch(){
        $user = Auth::user();
        $user_branches = collect();
        if($user){
            if($user->isNotDefaultUser()){
                $user_branches = $user->UserBranch??"";
            }
        }
        return $user_branches;
    }
    public static function LoggedUserBranchIds(){
        $user_branches = UserBranch::LoggedUserBranch();
        $user_branch_ids = [];
        if($user_branches->count()>0){
            $user_branch_ids = $user_branches->pluck('branch_id');
        }
        return $user_branch_ids;
    }
}
