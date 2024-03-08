<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use App\UserBranch;

class Expense extends Model
{
    //
    use Notifiable;
    use SoftDeletes;

    protected $fillable = [
        'branch_id',
        'expense_name',
        'date',
        'group_id',
        'amount',
        'currency_type',
        'description',
        'creator_id',
        'updater_id',
        'deleter_id',
    ];

    public function group_expense(){
        return $this->belongsTo('App\Group_expense', 'group_id');
    }
    public function expense_by_user(){
        return $this->belongsTo('App\User', 'creator_id');
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
