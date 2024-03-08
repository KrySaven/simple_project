<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Journal extends Model
{
    //
    use Notifiable;
    use SoftDeletes;

    protected $fillable = [
        'date', 'customer_id', 'branch_id', 'user_id', 'add_by', 'amount', 'status', 'description', 'creator_id', 'updater_id', 'deleter_id',
    ];
    public function customer(){
        return $this->belongsTo('App\Customer','customer_id');

    }
}
