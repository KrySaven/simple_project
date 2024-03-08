<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use App\Helpers\MyHelper;
use App\Helpers\LoanHelper;
use \NumberFormatter;
use DateTime;
class Payment extends Model
{
    use Notifiable;
    protected $fillable = [
        'sale_id' ,
        'timeline_id',
        'payment_date',
        'actual_date',
        'no',
        'user_id',
        'add_by',
        'amount',
        't_amount',
        'interest',
        't_interest',
        'percentage',
        'total',
        'balance',
        'status',
        'payment_status',
        'payment_type',
        'is_complete',
        'complete_by',
        'complete_date',
        'is_follow',
        'follow_by',
        'follow_date',
        'note',
        'active',
        'paid_off_interest',
        'paid_off_capital',
        'total_paid_off',
        'paid_interest_id',
        'paid_capital_id',
        'paid_off_by',
        'paid_off_date',
        'description',
        'creator_id',
        'updater_id',
        'deleter_id',
        'pay_gap',
        'branch_id',
        'saving',
        't_saving',
        'operation_fee',
        'total_paid_amount_riel',
        'total_paid_amount_usd',
        'advance_fine',
        'reschedule_status',

    ];
    public function sale(){
        return $this->belongsTo('App\Sale','sale_id');
    }
    public function loan(){
        return $this->belongsTo('App\Sale','sale_id');
    }
    public function Transaction(){
        return $this->hasMany('App\Transaction','payment_id','id')->where('amount_riel','>',0);
    }

    public function PaymentTransaction(){
        return $this->hasMany('App\PaymentTransaction','payment_id','id');
    }

    public function transaction_payment(){
        return $this->hasMany('App\Transaction','payment_id','id')->where('status','payment');
    }
    public function overPaymentTransactions(){
        return $this->hasMany('App\Transaction','sale_id','sale_id')->where('status','over_payment');
    }
    public function getOverPaymentAttribute(){
        $over_payments = $this->overPaymentTransactions??collect();
        $last_over_payments = 0;
        if($over_payments->count()>0){
            $last_over_payments = $over_payments->sum('amount_riel');
        }
        return $last_over_payments;
    }
    public function getOverPaymentWithFormatAttribute(){
        if($this->over_payment>0){
            return $this->khr_format($this->loan->currency_type,$this->over_payment);
        }
    }
    public function transactions(){
        return $this->hasMany('App\Transaction','payment_id','id');
    }
    public function getDateAttribute(){
        if($this->payment_date!=""){
            return date("Y/m/d", strtotime($this->payment_date??""));
        }
    }
    public function khr_format($currencyType,$amount){
        $currencySymbol = '$';
        if($currencyType == 'riel'){
            $amount = round($amount, -2);
            $fmt = new NumberFormatter('en_us', NumberFormatter::CURRENCY);
            $amount = $fmt->formatCurrency($amount, 'KHR');
            $currencySymbol = '៛';
        }
        $loan_amount = preg_replace( '/[^0-9,"."]/', '', $amount ).'&nbsp;'.$currencySymbol.'';
        return $loan_amount;
    }
    public function getPrincipleAttribute(){
        if($this->amount!=""){
            return $this->khr_format($this->loan->currency_type,$this->amount);
        }
    }
    public function getTotalPrincipleAttribute(){
        if($this->amount!=""){
            return $this->sum($this->amount);
        }
    }
    public function getInterestRateAttribute(){
        if($this->interest!=""){
            return $this->khr_format($this->loan->currency_type,$this->interest);
        }
    }
    public function getOperationFeeRielAttribute(){
        if($this->operation_fee!=""){
            return $this->khr_format($this->loan->currency_type,$this->operation_fee);
        }
    }
    public function getSavingRielAttribute(){
        if($this->saving!=""){
            return $this->khr_format($this->loan->currency_type,$this->saving);
        }
    }
    public function getTotalRielAttribute(){
        if($this->total!=""){
            return $this->khr_format($this->loan->currency_type,$this->total);
        }
    }
    public function getBalanceRielAttribute(){
        // dd($this->loan->currency_type);
        if($this->balance!=""){
            return $this->khr_format($this->loan->currency_type,$this->balance);
        }
    }
    public function getStatusLableAttribute(){
        if($this->status=="unpaid"){
            return "<span class='label label-warning'>".__('app.unpaid_schedule')."</span>";
        }elseif($this->status=="paid"){
            return "<span class='label label-success'>".__('app.paid_schedule')."</span>";
        }
    }
    public function isHasPayment(){
        if($this->total>0){
            return true;
        }else{
            return false;
        }
    }
    public function isUnPaid(){
        if($this->status=="unpaid"){
            return true;
        }else{
            return false;
        }
    }
    public function isPaid(){
        if($this->status=="paid"){
            return true;
        }else{
            return false;
        }
    }
    public function isPartial(){
        if($this->status=="partial"){
            return true;
        }else{
            return false;
        }
    }
    public function hasPayPermission(){
        $UserPermision = MyHelper::UserPermision();
        $checkisadmin = MyHelper::checkisadmin();
        if(isset($checkisadmin) ||isset($UserPermision['loan.payment.store'])){
            return true;
        }else{
            return false;
        }
    }
    public function hasPartialPermission(){
        $UserPermision = MyHelper::UserPermision();
        $checkisadmin = MyHelper::checkisadmin();
        if(isset($checkisadmin) ||isset($UserPermision['loan.payment.partail'])){
            return true;
        }else{
            return false;
        }
    }
    public function getTotalPaidAmountRielWithFormatAttribute(){
        if($this->total_paid_amount_riel!=""){
            return $this->khr_format($this->loan->currency_type,$this->total_paid_amount_riel);
        }
    }
    public function getAmountToBePayAttribute(){
        $last_over_payment = $this->over_payment;
        $total_paid_amount_riel = $this->total_paid_amount_riel;
        $amount_to_be_pay = $this->total - $last_over_payment;
        if($total_paid_amount_riel>0){
            $amount_to_be_pay = $amount_to_be_pay-$total_paid_amount_riel;
        }
        return $amount_to_be_pay;
    }
    public function getAmountToBePayWithPernaltyAttribute(){
        $current_amount  =$this->amount_to_be_pay;
        $penalty = $this->getPenalty($this->actual_date);
        $penalty_amount = $penalty['penalty_amount']??0;
        $amount_to_be_pay = $current_amount+$penalty_amount;
        return $amount_to_be_pay;
    }
    public function getAmountToBePayWithFormatAttribute(){
        return $this->khr_format($this->loan->currency_type,$this->amount_to_be_pay);
    }
    public function getAmountToBePayWithPernaltyAndFormatAttribute(){
        return $this->khr_format($this->loan->currency_type,$this->amount_to_be_pay_with_pernalty);
    }
    public function paymentTransactions($transaction_type){
        return $this->hasMany('App\Transaction','payment_id','id')->where('status',$transaction_type);
    }
    public function paidAmount($transaction_type){
        // $paid_amount = $this->paymentTransactions($transaction_type)->sum('amount_usd');
        $paid_amount = $this->paymentTransactions($transaction_type)->sum('amount_riel');
        return $paid_amount;
    }
    public function isPaidPernalty($transaction_type, $penalty_amount){
        $paid_pernalty = $this->paidAmount($transaction_type);
        if($paid_pernalty>=$penalty_amount){
            return true;
        }else{
            return false;
        }
    }
    public function isPaidTransaction($transaction_type){
        $paid_amount = $this->paidAmount($transaction_type);
        if($transaction_type=='principle'){
            $total =$this->amount;
        }else{
            $total = $this->$transaction_type;
        }
        if($paid_amount>=$total){
            return true;
        }else{
            return false;
        }
    }
    public function getPenalty($actual_date){
        $payment_date = $this->payment_date;
        $penalty_amount = 0;
        $days = 0;
        $penalty = [];
        if($actual_date>$payment_date){
            $days = $this->calculate_day($payment_date, $actual_date);
            // dd($days);
            $durationType = $this->loan->duration_type??"";
            if($durationType!=""){
                $penalty_type       = $this->penalty_type($durationType);
                $penalty_day        = $penalty_type['penalty_day']??"";
                $penalty_interest   = $penalty_type['interest']??"";
                if($days>$penalty_day){
                    $paid_amount    = $this->total_paid_amount_riel??0;
                    $payment_amount = $this->total??0;
                    if($paid_amount<=$payment_amount AND $this->isUnPaid()){
                        $amount = $payment_amount-$paid_amount;
                    }else{
                        $amount = $payment_amount;
                    }
                    // $penalty_amount_per_day =  ($penalty_interest/100)*$amount;
                    // $loanHelper = new LoanHelper;
                    // $penalty_amount     = $loanHelper->roundFormat($this->loan->currency_type,$penalty_amount_per_day*$days);
                }
            }
        }
        $penalty = [
            'penalty_amount' => $penalty_amount,
            'penalty_day'    => $days.__('app.day')
        ];
        return $penalty;
    }
    public function calculate_day($fromDate, $toDate){
        $fromDate = $fromDate;
        $toDate = $toDate;
        $datetime1 = new DateTime($fromDate);
        $datetime2 = new DateTime($toDate);
        $interval = $datetime1->diff($datetime2);
        $days = $interval->format('%a');//now do whatever you like with $days
        return $days;
    }
    public function penalty_type($durationType){
        $day = 0;
        $interest = 1;
        $penalty_type = [];
		if($durationType=="daily"){
			$day = 2;
            $interest = 1;
		}elseif($durationType=="weekly"){
			$day = 3;
            $interest = 1;
		}elseif($durationType=="2weeks" OR $durationType=="monthly" OR $durationType=="refinance"){
			$day = 5;
            $interest = 1;
		}else{
            $day = 0;
            $interest = 0;
        }
		$penalty_type = [
            'penalty_day' =>$day,
            'interest'    => $interest 
        ];
        return $penalty_type;
    }

    // Paid Principle
    public function SumPaidPrinciple($loan_id){
        $payments = $this->where('sale_id',$loan_id)->where('status','paid')->sum('amount');
        return $payments;
    }
    // Paid Interest
    public function SumPaidInterest($loan_id){
        $payments = $this->where('sale_id',$loan_id)->where('status','paid')->sum('interest');
        return $payments;
    }
    // Unpaid Principle
    public function SumAmountPrinciple($loan_id){
        $payments = $this->where('sale_id',$loan_id)->whereIn('status',['unpaid','partial'])->sum('amount');
        return $payments;
    }
    //Unpaid Interest
    public function SumAmountInterest($loan_id){
        $payments = $this->where('sale_id',$loan_id)->whereIn('status',['unpaid','partial'])->sum('interest');
        return $payments;
    }
    // Unpaid Total
    public function SumAmountTotal($loan_id){
        $payments = $this->where('sale_id',$loan_id)->whereIn('status',['unpaid','partial'])->sum('total');
        return $payments;
    }
}
