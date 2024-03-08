<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use \NumberFormatter;
use App\LoanDurationType;

class Sale extends Model
{
    use Notifiable;
    use SoftDeletes;

    protected $fillable = [
        'customer_id',
        'collateral_id',
        'branch_id',
        'dealer_id',
        'co_id',
        'timeline_id',
        'inv_no',
        'date',
        'first_payment',
        'product_name',
        'serial',
        'user_id',
        'add_by',
        'price' ,
        'deposit',
        'total',
        'currency_type',
        'contract_type',
        'interest',
        'status',
        'approve_status',
        'is_complete' ,
        'is_black_list',
        'black_list_by',
        'description',
        'dealer_relation',
        'dealer_relation_other',
        'icloud_username',
        'icloud_passwoed',
        'update_icloud_date',
        'update_icoude_by',
        'commission_type',
        'commission',
        'original_file',
        'phone_type',
        'iem',
        'created_at',
        'creator_id',
        'updater_id',
        'deleter_id',
        'license_plate',
        'color',
        'make_model',
        'tax_stamp',
        'vin',
        'engine_number',
        'cylineder_size',
        'year',
        'first_card_issuance_date',
        'market_price',
        'hot_price',
        'loan_price',
        'pawn_amount',
        'guarantor_id',
        'type_leasing',
        'leasing_term',
        'duration_type',
        'pay_type',
        'chassis_no',
        'saving',
        'operation_fee',
        'admin_fee',
        'interest_amount',
        'admin_fee_amount',
        'is_reschedule',
    ];
    public function customer(){
        return $this->belongsTo('App\Customer','customer_id');
    }
    public function collateral(){
        return $this->belongsTo('App\Collateral','collateral_id');
    }
    public function LoanCollaterals(){
        return $this->hasMany('App\LoanCollateral','loan_id');
    }
    public function guarantor(){
        return $this->belongsTo('App\Guarantor','guarantor_id');
    }
    public function dealer(){
        return $this->belongsTo('App\Dealer','dealer_id');
    }
    public function timeline(){
        return $this->belongsTo('App\Timeline','timeline_id');
    }
    public function coUser(){
        return $this->hasOne(User::class, 'id', 'co_id');
    }
    public function payment(){
        return $this->hasMany('App\Payment','sale_id')->orderBy('no','ASC');
    }
    public function transaction(){
        return $this->hasOne(Transaction::class, 'sale_id', 'id');
    }
    public function last_payment_date(){
        return $this->hasMany('App\Payment','sale_id')->orderBy('no','DESC');
    }

    public function last_payment_date_first(){
        return $this->hasOne('App\Payment','sale_id')->orderBy('payment_date','DESC');
    }

    public function scopeActive($query){
        return $query->where('status', 1);
    }
    public function getLoanAmountAttribute(){
        return $this->khr_format($this->currency_type,$this->total);
    }
    public function getInterestRateAttribute(){
        return number_format($this->interest, 2)."&nbsp%";
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
    public function durationType(){
        return $this->hasOne(LoanDurationType::class, 'slug', 'duration_type');
    }
    public function getPaymentTypeAttribute(){
        $pay_types = Config('app.pay_type');
        return $pay_types[$this->pay_type]??"";
    }
    public function getLoanTermAttribute(){
        $duration_type = $this->DurationType;
        return $this->leasing_term.'&nbsp'.str_plural($duration_type->duration_en??'', $this->leasing_term);
    }
    public function scopeFilterByBranch($query, $branch_id){
        if($branch_id!=""){
            return $query->where('branch_id', $branch_id);
        }
    }
    public function scopeFilterByCustomer($query, $customer_id){
        if($customer_id!=""){
            return $query->where('customer_id', $customer_id);
        }
    }
    public function scopeFilterByDealer($query, $dealer_id){
        if($dealer_id!=""){
            return $query->where('dealer_id', $dealer_id);
        }
    }
    public function scopeFilterByCo($query, $co_id){
        if($co_id!=""){
            return $query->where('co_id', $co_id);
        }
    }
    public function scopeFilterByGuarantor($query, $guarantor_id){
        if($guarantor_id!=""){
            return $query->where('guarantor_id', $guarantor_id);
        }
    }
    public function scopeByFields($query, $fields, $search){
        foreach ($fields as $field_name) {
            if($search!=""){
                $query = $query->orwhere($field_name, 'like', "%{$search}%");
            }
        }
    }
    public function scopeWhereDateBetween($query,$fieldName,$fromDate,$todate){
        if($fromDate!="" && $todate!=""){
            return $query->whereDate($fieldName,'>=',$fromDate)->whereDate($fieldName,'<=',$todate);
        }
    }
    // Search By loan Status
    public function scopeByStatus($query,$status){
        if(!empty($status)){
            return $query->where('approve_status',$status);
        }
    }
    public function ProcessingPayment(){
        $payment = $this->payment->where('status', 'paid');
        if($payment->count()>0){
            return true;
        }
    }
    public function scopeLaonApproved($query){
        return $query->where('approve_status', 'approved');
    }
    public function isEditable(){
        if($this->ProcessingPayment() OR $this->isApproved()){
            return true;
        }else{
            return false;
        }
    }
    public function isApproved(){
        if($this->approve_status == 'approved'){
            return true;
        }
        return false;
    }
    public function scopeFilterByCustmerName($query, $search){
        if($search!=""){
            return $this->whereHas('customer', function($query) use($search) {
                $query->Orwhere('customers.name', 'like', "%{$search}%");
            });
        }
    }
    public function scopeStatus($query, $status){
        return $query->where('approve_status', $status);
    }
    public function scopeStatusIn($query, $status = array()){
        return $query->whereIn('approve_status', $status);
    }
    public function scopeSearchRelativeName($query, $search){
        $this->with('customer');
        $this->with('coUser');
        $this->with('dealer');
        $this->with('guarantor');

        return $this->whereHas('customer', function($query) use ($search) {
            $query->where('customers.name', 'like', "%{$search}%")
                ->orwhere('customers.email','like', "%{$search}%");
        })->orWhereHas('coUser', function($query) use ($search) {
            $query->where('users.name', 'like', "%{$search}%")
                ->orwhere('users.email','like', "%{$search}%");
        })->orWhereHas('dealer', function($query) use ($search) {
            $query->where('dealers.name', 'like', "%{$search}%")
                ->orwhere('dealers.email','like', "%{$search}%");
        })->orWhereHas('guarantor', function($query) use ($search) {
            $query->where('guarantors.name', 'like', "%{$search}%")
                ->orwhere('guarantors.email','like', "%{$search}%");
        });
    }
    public function branch(){
        return $this->hasOne(Branch::class, 'id', 'branch_id');
    }
    public function getPaymentStatusLabelAttribute(){
        $paymentCount = $this->payment->where('is_complete','<>',1)->where('reschedule_status','<>','reschedule')->count();
        // dd($paymentCount);
        $paid_payment = $this->paidPayment();
        // dd($paid_payment);
        $unpaid_payment = $this->unpaidPayment();
        $partial_payment = $this->partialPayment();
        $label = "";
        if($paymentCount==$paid_payment){
            $label .="<span class='label label-success payment-status'>".__('app.paid_all')."</span>";
        }elseif($paid_payment==0 && $partial_payment == 0){
            $label .="<span class='label label-danger payment-status'>".__('app.unpaid')."</span>";
        }else{
            if($paid_payment>0){
                $label .="<span class='label label-success payment-status'>".__('app.paid')." (".$paid_payment.")</span>";
            }
            if($unpaid_payment>0){
                $label .="<span class='label label-danger payment-status'>".__('app.unpaid')." (".$unpaid_payment.")</span>";
            }
            if($partial_payment>0){
                $label .="<span class='label label-warning payment-status'>".__('app.partial')." (".$partial_payment.")</span>";
            }
        }
        return $label;
    }
    public function paymentStatus($status){
        $status = $this->payment->where('status',$status)->where('total', '>', 0)->where('is_complete','<>',1)->where('reschedule_status','<>','reschedule')->count();
        return $status;
    }
    public function paidPayment(){
        $paid_payment = $this->paymentStatus('paid');
        return $paid_payment;
    }
    public function unpaidPayment(){
        $unpaid_payment = $this->paymentStatus('unpaid');
        return $unpaid_payment;
    }
    public function partialPayment(){
        $partial_payment = $this->paymentStatus('partial');
        return $partial_payment;
    }
    public function loanStatus()
    {
        $customer_id = $this->customer->id;
        $loanCount = $this->where('customer_id','=',$customer_id)->count();
        $label = "";
        if ($loanCount >= 1) {
            $label .="(".$loanCount.")";
        }
        else{
            $label .="";
        }
        return $label;
    }
    public function countLoans($status)
    {
        $status = $this->where('customer_id','>',1)->count();
        return $status;
    }
    public function ScopeLoanDurationType($query, $duration_type){
        return $query->where('duration_type', $duration_type);
    }
}
