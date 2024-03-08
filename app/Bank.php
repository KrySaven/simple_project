<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
class Bank extends Model
{
    use Notifiable;
    use SoftDeletes;
    protected $fillable = [
        'name','bank_name', 'bank_number','active','creator_id','updater_id','deleter_id',
    ];
    public function scopeActive($query){
        return $query->where('active', 1);
    }
}
