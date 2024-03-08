<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LoanDurationType extends Model
{
	protected $fillable=[
		'type_en'
	];
    public function getRouteKeyName(){
	    return 'slug';
	}
	public function scopeWithSlug($query, $slug){
		return $query->where('slug', $slug);
	}
}
