<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use App\UserBranch;
use App\Helpers\MyHelper;
class Customer extends Model
{
    use Notifiable;
    use SoftDeletes;

    protected $fillable = [
        'cutomer_no',
        'type',
        'branch_id',
        'name_kh',
        'name',
        'gender',
        'date_of_birth',
        'phone',
        'company',
        'email',
        'identity_number',
        'identitycard_number_date',
        'issued_by',
        'customer_relation_issued_by',
        'nationality',
        'customer_relation_nationality',
        'family_status',
        'education_level',
        'education_level_other',
        'house_no',
        'street_no',
        'add_group',
        'province_id',
        'district_id',
        'commune_id',
        'village_id',
        'personal_ownership',
        'facebook_name',
        'facebook_link',
        'facebook_name',
        'work_company',
        'work_role',
        'work_salary',
        'work_house_no',
        'work_street_no',
        'work_group',
        'work_province_id',
        'work_district_id',
        'work_commune_id',
        'work_village_id',
        'business_occupation',
        'business_term',
        'business_house_no',
        'business_street_no',
        'business_group',
        'business_province_id',
        'business_district_id',
        'business_commune_id',
        'business_village_id',
        'address',
        'url',
        'identity',
        'description',
        'active',
        'created_at',
        'update_at',
        'deleted_at',
        'creator_id',
        'updater_id',
        'deleter_id',
        'identity_type',
        'customer_relation',
        'customer_relation_identity_number',
        'customer_relation_identity_type',
        'customer_relation_date_of_birth',
        'customer_relation_identity_created_at',
        'customer_relation_sex',
        'customer_relation_name_kh',
        'customer_relation_name_en',
        'cus_no',
        'business_img',
        'lat',
        'long'
    ];
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
    public function work_province(){
        return $this->belongsTo('App\Province','work_province_id');
    }
    public function work_district(){
        return $this->belongsTo('App\District','work_district_id');
    }
    public function work_commune(){
        return $this->belongsTo('App\Commune','work_commune_id');
    }
    public function work_village(){
        return $this->belongsTo('App\Village','work_village_id');
    }
    public function business_province(){
        return $this->belongsTo('App\Province','business_province_id');
    }
    public function business_district(){
        return $this->belongsTo('App\District','business_district_id');
    }
    public function business_commune(){
        return $this->belongsTo('App\Commune','business_commune_id');
    }
    public function business_village(){
        return $this->belongsTo('App\Village','business_village_id');
    }
    public function branch(){
        return $this->hasOne(Branch::class, 'id', 'branch_id');
    }
    public function getBranchNameAttribute(){
        $branch = $this->branch;
        return $branch->branch_name??'';
    }
    public function getKhmerAndEnglishNameAttribute(){
        return "{$this->name} - {$this->name_kh}";
    }
    public function getCustomerInfoAttribute(){
        return "{$this->khmer_and_english_name} -- {$this->phone} -- {$this->identity_number} -- {$this->email}";
    }
    public function scopeActive($query){
        return $query->where('active', 1);
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
    public function village_name_kh(){
        $village = $this->village;
        if($village){
            return $village->village_namekh??'';
        }
    }
    public function commune_name_kh(){
        $commune = $this->commune;
        if($commune){
            return $commune->commune_namekh??'';
        }
    }
    public function district_name_kh(){
        $district = $this->district;
        if($district){
            return $district->district_namekh??'';
        }
    }
    public function province_name_kh(){
        $province = $this->province;
        if($province){
            return $province->province_kh_name??'';
        }
    }
    public function getFullAddressKhAttribute(){
        $style    = "style='width:100px'";
        // $village  = __('app.village').$this->village_name_kh();
        // $commune  = __('app.commune').$this->commune_name_kh();
        // $district = __('app.district').$this->district_name_kh();
        // $province = __('app.province').$this->province_name_kh();
        $village  = $this->village_name_kh();
        $commune  = $this->commune_name_kh();
        $district = $this->district_name_kh();
        $province = $this->province_name_kh();
        return $village." ".$commune." ".$district." ".$province;
    }
    public function getFullAddressKhDottedAttribute(){
        $style = "style='width:100px'";
        $village = __('app.village')."<dotted $style>".$this->village_name_kh()."</dotted>";
        $commune = __('app.commune')."<dotted $style>".$this->commune_name_kh()."</dotted>";
        $district = __('app.district')."<dotted $style>".$this->district_name_kh()."</dotted>";
        $province = __('app.province')."<dotted $style>".$this->province_name_kh()."</dotted>";
        return $village." ".$commune." ".$district." ".$province;
    }
    public function getDOBAttribute(){
        if(!empty($this->date_of_birth)){
            return MyHelper::khMultipleNumber(date('d',strtotime($this->date_of_birth??""))) .'-'. MyHelper::khMonth(date('m',strtotime($this->date_of_birth??""))) .'-'. MyHelper::khMultipleNumber(date('Y',strtotime($this->date_of_birth??"")));
        }
    }
    public function getRelationDOBAttribute(){
        if(!empty($this->customer_relation_date_of_birth)){
            return MyHelper::khMultipleNumber(date('d',strtotime($this->customer_relation_date_of_birth??""))) .'-'. MyHelper::khMonth(date('m',strtotime($this->customer_relation_date_of_birth??""))) .'-'. MyHelper::khMultipleNumber(date('Y',strtotime($this->customer_relation_date_of_birth??"")));
        }
    }
    public function getFullAddressCusAttribute(){
        // return $this->house_no ? $this->house_no.',' : ''. $this->street_no ? $this->street_no.',':'';
    }
}