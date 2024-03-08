<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
class Transaction extends Model
{
    use Notifiable;
    use SoftDeletes;
    protected $fillable = [
        'payment_transaction_id',
        'date',
        'amount_usd',
        'admin_fee', 
        'amount_riel',
        'interest_usd',
        'interest_riel',
        'exchange',
        'open_balance_id',
        'investment_id',
        'close_balance_id',
        'sale_id',
        'payment_id',
        'payment_detail_id',
        'journal_id','payroll_id',
        'invoice_id','invoice_pay_id',
        'expen_id',
        'income_id',
        'bank_id',
        'user_id',
        'is_close',
        'status',
        'payment_status',
        'description',
        'creator_id',
        'updater_id',
        'deleter_id', 
        'branch_id',
        'currency_type'
    ];
    public function bank(){
        return $this->belongsTo('App\Bank','bank_id');
    }
    public function payment(){
        return $this->belongsTo('App\Payment','payment_id');
    }
}
