<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transfer_Loan extends Model
{
    use Notifiable;
    use SoftDeletes;
    protected $table = 'transfer_loan';
    protected $fillable = [
        'sale_id',
        'cus_id',
        'old_co_id',
        'paid_principle',
        'paid_interest',
        'balance',
        'transfer_date',
        'description',
        'transfer_by'
    ];
}
