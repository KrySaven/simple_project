<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Supplier extends Authenticatable
{
    protected $table ="suppliers";
    use Notifiable;
    use SoftDeletes;
    protected $fillable = [
            'supp_no',
            'currency_id',
            'name',
            'name_kh',
            'gender',
            'date_of_birth',
            'identity_type',
            'identity_number',
            'identitycard_number_date',
            'phone',
            'company',
            'nationality',
            'address',
            'is_active',
            'email',
            'user_name',
            'password',
            'confirm_password',
            'description',
            'profile',
            'business',
            'identity',
            'creator_id',
            'updater_id',
            'deleter_id',
        ];


    protected $hidden = [
        'password', 'remember_token',
    ];
}
