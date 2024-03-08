<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;
use App\UserBranch;
class Branch extends Model
{
    use Notifiable;
    use SoftDeletes;

    protected $fillable = [
        'name_en',
        'name_kh',
        'owner_name_en',
        'owner_name_kh',
        'phone',
        'email',
        'map',
        'logo',
        'icon',
        'facebook',
        'line',
        'address', 
        'village',
        'commune',
        'district',
        'province',
        'description',
        'created_by',
        'updated_by',
        'deleted_by',
        'schedule_excluding_public_holiday',
        'schedule_excluding_saturday',
        'schedule_excluding_sunday',
        'owner_title_en',
        'owner_title_kh',
        'sex',
        'date_of_birth',
        'nationality',
        'identity_number',
        'identity_created_at',
    ];
    public function sale(){
        return $this->belongsTo(Sale::class, 'id', 'branch_id');
    }
    public function customer(){
        return $this->belongsTo(Customer::class, 'id', 'branch_id');
    }
    public function getLogoPathAttribute(){
    	$file_path = $this->logo;
    	if($this->isFile($file_path)){
    		return asset($file_path);
    	}else{
    		return asset('images/noimage.png');
    	}
    }
    public function getIconPathAttribute(){
        $file_path = $this->icon;
    	if($this->isFile($file_path)){
    		return asset($file_path);
    	}else{
    		return asset('images/noimage.png');
    	}
    }
    public function getBranchNameAttribute(){
    	$string = isset($this->name_kh)?"-":'';
    	return "{$this->name_en} {$string} {$this->name_kh}";
    }
    public function getOwnerNameAttribute(){
    	$string = isset($this->owner_name_kh)?"-":'';
    	return "{$this->owner_name_en} {$string} {$this->owner_name_kh}";
    }
    public function isFile($file_path){
        if(file_exists(base_path($file_path)) AND $file_path!=""){
            return true;
        }else{
            return false;
        }
    }
    public function getScheduleExcludedOnAttribute(){
        $label = "";
        if($this->IsExcludingPublicHoliday()){
           $label.="<a href=".route('publicHoliday.index').">".$this->lable(trans('app.public_holiday'))."</a>";
        }
        if($this->IsExcludingSaturday()){
            $label.=$this->lable(trans('app.saturday'));
        }
        if($this->IsExcludingSunday()){
            $label.=$this->lable(trans('app.sunday'));
        }
        return $label;
    }
    public function scopeIsExcludingPublicHoliday(){
        if($this->schedule_excluding_public_holiday==1){
            return true;
        }else{
            return false;
        }
    }
    public function scopeIsExcludingSaturday(){
        if($this->schedule_excluding_saturday==1){
            return true;
        }else{
            return false;
        }
    }
    public function scopeIsExcludingSunday(){
        if($this->schedule_excluding_sunday==1){
            return true;
        }else{
            return false;
        }
    }
    public function lable($value){
        $label="";
        $label .= "<span>";
        $label .= $value;
        $label .=  "</span>";
        $label .=  "</br>";
        return $label;
    }
    public function scopeWithPermission($query){
        $user_branch_ids = UserBranch::LoggedUserBranchIds();
        if(count((array)$user_branch_ids)>0){
            return $query->whereIn('id', $user_branch_ids);
        }else{
            return $query;
        }
    }
    public function hasRelation(){
        if($this->sale OR $this->customer OR $this->guarantor OR $this->dealer){
            return true;
        }else{
            return false;
        }
    }
}
