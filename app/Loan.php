<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
class Loan extends Model
{
    //
    use Notifiable;
    use SoftDeletes;

    protected $fillable = [
        'date', 'dealer_id', 'user_id', 'branchdealer_id', 'add_by','title', 'amount', 'status', 'description', 'creator_id', 'updater_id', 'deleter_id',
    ];
    public function dealer(){
        return $this->belongsTo('App\Dealer','dealer_id');

    }
    public function branchdealer(){
        return $this->belongsTo('App\BranchDealer','branchdealer_id');

    }
    public function customer_loan(){
        return $this->belongsTo('App\Customer','customer_id');
    }
    public static function getTransaction($request)
    {
        $transaction = Loan::select('id', 'title', 'dealer_id', 'branchdealer_id', 'date', 'amount', 'description')->orderBy('created_at', 'desc');
        if ($request->dealer_id != null) {
            $transaction->where('dealer_id', $request->dealer_id);
        }
        if ($request->branchdealer_id != null) {
            $transaction->where('branchdealer_id', $request->branchdealer_id);
        }
        if ($request->from_date) {
            $transaction->where('date', '>=' ,$request->from_date);
        }
        if ($request->to_date) {
            $transaction->where('date', '<=' ,$request->to_date);
        }
        return $transaction->get();
    }

}
