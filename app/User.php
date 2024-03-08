<?php

namespace App;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;
use App\UserBranch;
class User extends Authenticatable
{
    use HasApiTokens, Notifiable;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'group_id',
        'branch_id',
        'name',
        'name_kh', 
        'email',
        'phone', 
        'password',
        'profile',
        'is_co',
        'is_active'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    public function usergroup(){
        return $this->belongsTo('App\User_group','group_id');
    }
    public function getGroupNameAttribute(){
        $user_group = $this->usergroup??'';
        return $user_group->group_name??'';
    }
    public function scopeActive($query){
        return $query->where('is_active', 1);
    }
    public function scopeNotDefaultUser($query){
        return $query->where('id','<>',1);
    }
    public function isNotDefaultUser(){
        if($this->id!="1"){
            return true;
        }
    }
    public function loans(){
        return $this->hasMany(Sale::class, 'co_id', 'id')->with('customer');
    }
    public function branch(){
        return $this->belongsTo('App\Branch','branch_id');
    }
    public function UserBranch(){
        return $this->hasMany(UserBranch::class, 'user_id');
    }
    public function getUserBranchLabelAttribute(){
        $user_branches = $this->UserBranch;
        $label = "";
        if($user_branches->count()>0){
            foreach($user_branches as $user_branch){
                $label.="<span>";
                $label.="<a href=".route('branch.edit', $user_branch->branch_id??'').">";
                $label.=$user_branch->branch_name??'';
                $label.="</a>";
                $label.="</span>";
                $label.="<br>";
            }
        }else{
            $label.="<span>"."N/A"."</span>";
        }
        return $label;
    }
    public function scopeCoUser($query){
        return $query->where('is_co', 1);
    }
    public function getNameAndEmailAttribute(){
        return "{$this->name} - {$this->email}";
    }
    public function scopeWithBranch($query){
       $user_branch_ids = UserBranch::LoggedUserBranchIds();
       if(count((array)$user_branch_ids)>0){
        return $query->whereHas('UserBranch', function($q) use($user_branch_ids){
            $q->whereIn('user_branches.branch_id', $user_branch_ids);
        });
       }else{
        return $query;
       }
    }
    public function scopeByBranch($query, $branch_id){
        return $query->whereHas('UserBranch', function($q) use($branch_id){
            $q->where('user_branches.branch_id', $branch_id);
        });
    }
}
