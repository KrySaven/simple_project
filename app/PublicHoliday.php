<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PublicHoliday extends Model
{
	use SoftDeletes;
    protected $fillable = [
    	'branch_id',
		'parent_id', 
	    'name_en',
	    'name_kh',
	    'from_date',
	    'to_date',
	    'note',
	    'created_by'
    ];
    public function getFromDateWithFormatAttribute(){
    	if($this->from_date){
    		return date('M d, Y', strtotime($this->from_date));
    	}
    }
    public function getToDateWithFormatAttribute(){
    	if($this->to_date){
    		return date('M d, Y', strtotime($this->to_date));
    	}
    }
	public function parent()
    {
        return $this->hasMany(PublicHoliday::class,'parent_id');
    }

}
