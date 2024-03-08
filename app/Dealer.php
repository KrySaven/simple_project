<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
class Dealer extends Model
{
    use Notifiable;
    use SoftDeletes;

    protected $fillable = [
        'branch_id',
        'salesman_id',
        'name_kh',
        'name',
        'gender',
        'date_of_birth',
        'identity_number',
        'issued_by',
        'username',
        'phone',
        'company',
        'bank_name',
        'bank_number',
        'email',
        'password',
        'house_no',
        'street_no',
        'add_group',
        'province_id',
        'district_id',
        'commune_id',
        'village_id',
        'address',
        'url',
        'description',
        'active',
        'created_at',
        'update_at',
        'deleted_at',
        'creator_id',
        'updater_id',
        'deleter_id'
    ];
    public function salesman(){
        return $this->belongsTo('App\Salesman','salesman_id');
    }
    public function sale(){
        return $this->hasMany('App\Sale','dealer_id','id');
    }
    public function province(){
        return $this->belongsTo('App\Province','province_id');
    }
    public function district(){
        return $this->belongsTo('App\District','district_id');
    }
    public function commune(){
        return $this->belongsTo('App\Commune','commune_id');
    }
    public function village(){
        return $this->belongsTo('App\Village','village_id');
    }
    public function getDealerInfoAttribute(){
        return "{$this->name_english_and_khmer} - {$this->phone} - {$this->identity_number}";
    }
    public function scopeActive($query){
        return $query->where('active', 1);
    }
    public function getNameEnglishAndKhmerAttribute(){
        return "{$this->name} - {$this->name_kh}";
    }
    public function scopeWithBranch($query){
       $user_branch_ids = UserBranch::LoggedUserBranchIds();
       if(count((array)$user_branch_ids)>0){
        return $query->whereIn('branch_id', $user_branch_ids);
       }else{
        return $query;
       }
    }
    public function scopeByBranch($query, $branch_id){
        return $query->where('branch_id', $branch_id);
    }
}
