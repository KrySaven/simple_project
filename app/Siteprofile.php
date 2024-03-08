<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
class Siteprofile extends Model
{
    use Notifiable;
    use SoftDeletes;
    protected $fillable = [
        'site_name' , 'company','owner_name', 'phone','email','address','user_id', 'add_by','logo','icon','created_at', 'creator_id', 'updater_id', 'deleter_id', 'line','facebook','map','site_name_kh','company_kh','owner_name_kh'
    ];
}
