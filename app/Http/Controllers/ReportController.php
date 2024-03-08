<?php

namespace App\Http\Controllers;

use DB;
use App\Bank;
use App\Loan;
use App\Sale;
use App\User;
use App\Branch;
use App\Dealer;
use App\Income;
use App\Payoff;
use App\Journal;
use App\Payment;
use App\Village;
use App\Customer;
use App\Province;
use App\Timeline;
use Carbon\Carbon;
use App\Collateral;
use App\GroupIncome;
use App\Siteprofile;
use App\Transaction;
use App\BranchDealer;
use App\Group_expense;
use App\LoanDurationType;
use App\PaymentTransaction;
use Illuminate\Http\Request;
use App\Exports\CustomerData;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function loand_report(Request $request)
    {
        $date = date('Y-m-d');
        isset($request->date_from)?$date_from   = $request->date_from : $date_from = (date('Y-m-d', strtotime($date .'-1 month')));
        isset($request->date_to)?$date_to       = $request->date_to : $date_to = $date;
        $dealers = Dealer::where('active',1)->orderBy('id','DESC')->get();
        $dealer  = [];
        foreach ($dealers as $dealers) {
            $dealer[$dealers->id] = $dealers->name.' -- '.$dealers->phone.' -- '.$dealers->email;
        }

        $branchdealers = BranchDealer::where('id','')->orderBy('id','DESC')->get();
        if($request->dealer_id != ''){
            $branchdealers = BranchDealer::where('dealer_id',$request->dealer_id)->orderBy('id','DESC')->get();
        }
        $branchdealer = [];
        foreach ($branchdealers as $branchdealers) {
            $branchdealer[$branchdealers->id] = $branchdealers->name;
        }
        $loans =Loan::where('status','=','invoice')->where('dealers.active',1)->whereNull('dealers.deleted_at')
        ->join('dealers','loans.dealer_id','=','dealers.id')
        ->where('loans.date','>=',$date_from)
        ->where('loans.date','<=',$date_to)
        ->select('loans.*');
        if($request->dealer_id != ''){
           $loans       = $loans->where('loans.dealer_id',$request->dealer_id);
           $dealer_id   = $request->dealer_id;
        }
        $loans              = $loans->orderBy('loans.date','ASC')->get();
        $datas['date_from'] = $date_from;
        $datas['date_to']   = $date_to;
        return view('admin.reports.loand_report',compact('request','dealer','branchdealer'),$datas)->with('rows',$loans);
    }
    public function sales_report(Request $request){
        $date = date('Y-m-d');
        isset($request->date_from)?$date_from   = $request->date_from : $date_from = (date('Y-m-d', strtotime($date .'-1 year')));
        isset($request->date_to)?$date_to       = $request->date_to : $date_to = $date;
        $customer_id = '';
        $timeline_id = '';
        $customers   = Customer::where('active',1)->orderBy('name','ASC')->get();
        $customer    = [];
        foreach ($customers as $customers) {
            $customer[$customers->id] = $customers->name.' -- '.$customers->phone.' -- '.$customers->email;
        }

        $timelines = Timeline::orderBy('id','DESC')->get();
        $timeline  = [];
        foreach ($timelines as $timelines) {
            $timeline[$timelines->id] = $timelines->name;
        }
        $co_users = User::CoUser()->Active()->get()->pluck('name_and_email', 'id');
        $sales =Sale::where(['sales.status'=> 1,'sales.type_leasing'=> 'loan','sales.approve_status' => 'approved'])->where('customers.active',1)->whereNull('customers.deleted_at')
        ->join('customers','sales.customer_id','=','customers.id')
        ->where('sales.date','>=',$date_from)
        ->where('sales.date','<=',$date_to)
        ->select('sales.*');
        if($request->customer_id != ''){
           $sales           = $sales->where('sales.customer_id',$request->customer_id);
           $customer_id     = $request->customer_id;
        }
        if(!empty($request->co_id)){
            $sales = $sales->where('sales.co_id',$request->co_id);
        }
        if($request->timeline_id != ''){
           $sales       = $sales->where('sales.timeline_id',$request->timeline_id);
           $timeline_id = $request->timeline_id;
        }
        $sales = $sales->orderBy('sales.date','DESC')->get();
        $datas['date_from'] = $date_from;
        $datas['date_to']   = $date_to;
        return view('admin.reports.sales_report',compact('request','timeline','customer','co_users'),$datas)->with('rows',$sales);
    }

    // loan Report
    public function loan_report(Request $request){
        $branch_id      = $request->branch_id;
        $branches       = Branch::withPermission()->orderBy('id', 'ASC')->get()->pluck('branch_name', 'id');
        $searchFields   = [
            'inv_no',
            'first_payment',
            'saving',
            'operation_fee',
            'total',
            'interest',
            'leasing_term',
            'type_leasing',
            'duration_type'
        ];
        $search = $request->search;
        $date   = date('Y-m-d');
        isset($request->from_date)?$from_date   = $request->from_date : $from_date = (date('Y-m-d', strtotime($date .'-1 month')));
        isset($request->to_date)?$to_date       = $request->to_date : $to_date = $date;
        $loan   = Sale::SearchRelativeName($search)
                    ->ByFields($searchFields, $search)
                    ->WhereDateBetween('date', $from_date, $to_date)
                    ->ByStatus($request->status)
                    ->active()->orderBy('created_at', 'DESC')
                    ->StatusIn(['approved','payoff'])->get();
        $datas['from_date'] = $from_date;
        $datas['to_date'] = $to_date;
        return view('admin.reports.loan_report', compact(
            'loan',
            'branches',
            'request'
        ),$datas);
    }
    public function loan_approve_report(Request $request){
        $branch_id      = $request->branch_id;
        $branches       = Branch::withPermission()->orderBy('id', 'ASC')->get()->pluck('branch_name', 'id');
        $searchFields   = [
            'inv_no',
            'first_payment',
            'saving',
            'operation_fee',
            'total',
            'interest',
            'leasing_term',
            'type_leasing',
            'duration_type'
        ];
        $search = $request->search;
        $date   = date('Y-m-d');
        isset($request->from_date)?$from_date   = $request->from_date : $from_date = (date('Y-m-d', strtotime($date .'-1 month')));
        isset($request->to_date)?$to_date       = $request->to_date : $to_date = $date;
        $loan   = Sale::SearchRelativeName($search)
                    ->ByFields($searchFields, $search)
                    ->WhereDateBetween('date', $from_date, $to_date)
                    ->ByStatus($request->status)
                    ->active()->orderBy('created_at', 'DESC')
                    ->StatusIn(['approved','payoff'])->get();
        $datas['from_date'] = $from_date;
        $datas['to_date'] = $to_date;
        return view('admin.reports.loan_approve_report', compact(
            'loan',
            'branches',
            'request'
        ),$datas);
    }

    public function invest_sales_report(Request $request){
        $date = date('Y-m-d');
        isset($request->date_from)?$date_from   = $request->date_from : $date_from = (date('Y-m-d', strtotime($date .'-1 year')));
        isset($request->date_to)?$date_to       = $request->date_to : $date_to = $date;
        $customer_id    = '';
        $timeline_id    = '';
        $co_id          = '';
        $customers      = Customer::where('active',1)->orderBy('name','ASC')->get();
        $customer       = [];
        foreach ($customers as $customers) {
            $customer[$customers->id] = $customers->name.' -- '.$customers->phone.' -- '.$customers->email;
        }

        $timelines = Timeline::orderBy('id','DESC')->get();
        $timeline  = [];
        foreach ($timelines as $timelines) {
            $timeline[$timelines->id] = $timelines->name;
        }

        $co_users   = User::where('is_active',1)->orderBy('name','ASC')->get();
        $co_user    = [];
        foreach($co_users as $co_users){
            $co_user[$co_users->id] = $co_users->name;
        }

        $sales =Sale::where(['sales.status'=>1,'sales.approve_status'=>'approved'])->where('customers.active',1)->whereNull('customers.deleted_at')
        ->join('customers','sales.customer_id','=','customers.id')
        ->where('sales.date','>=',$date_from)
        ->where('sales.date','<=',$date_to)
        ->select('sales.*');
        if($request->customer_id != ''){
           $sales       = $sales->where('sales.customer_id',$request->customer_id);
           $customer_id = $request->customer_id;
        }
        if($request->co_id != ''){
            $sales       = $sales->where('sales.co_id',$request->co_id);
            $co_id       = $request->co_id;
         }
        if($request->timeline_id != ''){
           $sales       = $sales->where('sales.timeline_id',$request->timeline_id);
           $timeline_id = $request->timeline_id;
        }
        if($request->currency_type !=''){
            $sales = $sales->where('sales.currency_type',$request->currency_type);
        }
        $sales = $sales->orderBy('sales.date','DESC')->get();

        $datas['date_from']     = $date_from;
        $datas['date_to']       = $date_to;
        return view('admin.reports.invest_sales_report',compact('request','timeline','customer','co_user'),$datas)->with('rows',$sales);
    }
    public function customer_data_old(Request $request){
        $date = date('Y-m-d');
        isset($request->date_from)?$date_from   = $request->date_from : $date_from = (date('Y-m-d', strtotime($date .'-1 month')));
        isset($request->date_to)?$date_to       = $request->date_to : $date_to = $date;
        $customer_id    = '';
        $timeline_id    = '';
        $customers      = Customer::where('active',1)->orderBy('name','ASC')->get();
        $customer       = [];
        foreach ($customers as $customers) {
            $customer[$customers->id] = $customers->name.' -- '.$customers->phone.' -- '.$customers->email;
        }

        $timelines = Timeline::orderBy('id','DESC')->get();
        $timeline  = [];
        foreach ($timelines as $timelines) {
            $timeline[$timelines->id] = $timelines->name;
        }
        $sales =Sale::where('sales.status',1)->where('customers.active',1)->whereNull('customers.deleted_at')
        ->join('customers','sales.customer_id','=','customers.id')
        ->where('sales.date','>=',$date_from)
        ->where('sales.date','<=',$date_to)
        ->select('sales.*');
        if($request->customer_id != ''){
           $sales       = $sales->where('sales.customer_id',$request->customer_id);
           $customer_id = $request->customer_id;
        }
        if($request->timeline_id != ''){
           $sales       = $sales->where('sales.timeline_id',$request->timeline_id);
           $timeline_id = $request->timeline_id;
        }
        $sales = $sales->orderBy('sales.date','DESC')->get();
        if($request->submit_type=='download'){
            return  Excel::download(new CustomerData($sales,$request), 'call_card_report_'.date('d-m-Y').'.xlsx');
        }

        $datas['date_from'] = $date_from;
        $datas['date_to']   = $date_to;
        return view('admin.reports.customer_data',compact('request','timeline','customer'),$datas)->with('rows',$sales);
    }
    public function customer_data(Request $request){
        $date = date('Y-m-d');
        isset($request->date_from)?$date_from   = $request->date_from : $date_from = (date('Y-m-d', strtotime($date .'-1 month')));
        isset($request->date_to)?$date_to       = $request->date_to : $date_to = $date;
        if($date_from > $date_to){
            $notification = array(
                'message'       => "Please Try again!",
                'alert-type'    => 'info'
            );
            return redirect()->back()->with($notification);
        }

        $customer_id    = '';
        $timeline_id    = '';
        $customers      = Customer::where('active',1)->orderBy('name','ASC')->get();
        $customer       = [];
        foreach ($customers as $customers) {
            $customer[$customers->id] = $customers->name.' -- '.$customers->phone.' -- '.$customers->email;
        }

        $timelines = Timeline::orderBy('id','DESC')->get();
        $timeline  = [];
        foreach ($timelines as $timelines) {
            $timeline[$timelines->id] = $timelines->name;
        }
        $sales =Sale::where('sales.status',1)->where('customers.active',1)->whereNull('customers.deleted_at')
        ->join('customers','sales.customer_id','=','customers.id')
        ->join('dealers','sales.dealer_id','=','dealers.id')
        ->where('sales.is_black_list',0)
        ->where('sales.date','>=',$date_from)
        ->where('sales.date','<=',$date_to)
        ->select('sales.*','customers.name AS customer_name','customers.phone AS customer_phone','dealers.name AS dealer_name','dealers.phone AS dealer_phone');
        if($request->customer_id != ''){
           $sales       = $sales->where('sales.customer_id',$request->customer_id);
           $customer_id = $request->customer_id;
        }
        if($request->timeline_id != ''){
           $sales       = $sales->where('sales.timeline_id',$request->timeline_id);
           $timeline_id = $request->timeline_id;
        }
        $sales          = $sales->orderBy('sales.date','DESC')->get();

        $sale_id_arr    = $sales->pluck('id');
        // $stringids = implode(",", $sale_id_arr);
        $get_from_date_to_date = Payment::select(DB::raw("
                                        MIN(payment_date) AS from_date,
                                        MAX(payment_date) AS to_date
                                        "))
                                    ->whereIn('sale_id',$sale_id_arr)
                                    ->whereNull('deleted_at')
                                    ->first();
        $get_payment_data       = Payment::whereIn('sale_id',$sale_id_arr)->orderBy('sale_id','ASC')->orderBy('payment_date','ASC')->get();
        $get_payment_data_arr   = [];
        $payment_date           = '';
        $actual_date            = '';
        foreach ($get_payment_data as $pay_row) {
            // if($pay_row->status == 'paid' || $pay_row->is_complete == 1){
            //     if($pay_row->is_complete == 1){
            //         if(isset($get_payment_data_arr[date('m-Y',strtotime($pay_row->actual_date)).'-'.$pay_row->sale_id]['total'])){
            //             $get_payment_data_arr[date('m-Y',strtotime($pay_row->actual_date)).'-'.$pay_row->sale_id]['total'] = $pay_row->total;
            //             $get_payment_data_arr[date('m-Y',strtotime($pay_row->actual_date)).'-'.$pay_row->sale_id]['amount'] += $pay_row->t_amount;
            //             $get_payment_data_arr[date('m-Y',strtotime($pay_row->actual_date)).'-'.$pay_row->sale_id]['interest'] += $pay_row->t_interest;
            //             $get_payment_data_arr[date('m-Y',strtotime($pay_row->actual_date)).'-'.$pay_row->sale_id]['status'] = $pay_row->status;
            //         }else{
            //             $get_payment_data_arr[date('m-Y',strtotime($pay_row->actual_date)).'-'.$pay_row->sale_id]['total'] = $pay_row->total;
            //             $get_payment_data_arr[date('m-Y',strtotime($pay_row->actual_date)).'-'.$pay_row->sale_id]['amount'] = $pay_row->t_amount;
            //             $get_payment_data_arr[date('m-Y',strtotime($pay_row->actual_date)).'-'.$pay_row->sale_id]['interest'] = $pay_row->t_interest;
            //             $get_payment_data_arr[date('m-Y',strtotime($pay_row->actual_date)).'-'.$pay_row->sale_id]['status'] = $pay_row->status;
            //         }
            //     }else{
            //         if(isset($get_payment_data_arr[date('m-Y',strtotime($pay_row->actual_date)).'-'.$pay_row->sale_id]['total'])){
            //             $get_payment_data_arr[date('m-Y',strtotime($pay_row->actual_date)).'-'.$pay_row->sale_id]['total'] = $pay_row->total;
            //             $get_payment_data_arr[date('m-Y',strtotime($pay_row->actual_date)).'-'.$pay_row->sale_id]['amount'] += $pay_row->amount;
            //             $get_payment_data_arr[date('m-Y',strtotime($pay_row->actual_date)).'-'.$pay_row->sale_id]['interest'] += $pay_row->interest;
            //             $get_payment_data_arr[date('m-Y',strtotime($pay_row->actual_date)).'-'.$pay_row->sale_id]['status'] = $pay_row->status;
            //         }else{
            //             $get_payment_data_arr[date('m-Y',strtotime($pay_row->actual_date)).'-'.$pay_row->sale_id]['total'] = $pay_row->total;
            //             $get_payment_data_arr[date('m-Y',strtotime($pay_row->actual_date)).'-'.$pay_row->sale_id]['amount'] = $pay_row->amount;
            //             $get_payment_data_arr[date('m-Y',strtotime($pay_row->actual_date)).'-'.$pay_row->sale_id]['interest'] = $pay_row->interest;
            //             $get_payment_data_arr[date('m-Y',strtotime($pay_row->actual_date)).'-'.$pay_row->sale_id]['status'] = $pay_row->status;
            //         }
            //     }


            // }else{
            //     $get_payment_data_arr[date('m-Y',strtotime($pay_row->payment_date)).'-'.$pay_row->sale_id]['total'] = $pay_row->total;
            //     $get_payment_data_arr[date('m-Y',strtotime($pay_row->payment_date)).'-'.$pay_row->sale_id]['amount'] = $pay_row->amount;
            //     $get_payment_data_arr[date('m-Y',strtotime($pay_row->payment_date)).'-'.$pay_row->sale_id]['interest'] = $pay_row->interest;
            //     $get_payment_data_arr[date('m-Y',strtotime($pay_row->payment_date)).'-'.$pay_row->sale_id]['status'] = $pay_row->status;
            // }
            // $payment_date = $pay_row->payment_date;
            // $actual_date = $pay_row->actual_date;


            $get_payment_data_arr[date('m-Y',strtotime($pay_row->payment_date)).'-'.$pay_row->sale_id] = [
                                                                                'total'     => $pay_row->total,
                                                                                'amount'    => $pay_row->amount,
                                                                                'interest'  => $pay_row->interest,
                                                                                'status'    => $pay_row->status,
                                                                                ];
        }
        $d1             = new \DateTime($get_from_date_to_date->from_date);
        $d2             = new \DateTime($get_from_date_to_date->to_date);
        $d2             = $d2->modify( '+1 day' );
        $intervals      = \DateInterval::createFromDateString('1 day');
        $period         = new \DatePeriod($d1, $intervals, $d2);
        $group_expense  = Group_expense::get();
        $groupincome    = GroupIncome::get();
        foreach ($period as $dt) {
            $date_month_arr[$dt->format('Y-m')] = $dt->format('Y-m-d');
        }

        if($request->submit_type=='download'){

            return  Excel::download(new CustomerData($sales,$request,$date_month_arr,$get_payment_data_arr), 'Customer_date_report_'.date('d-m-Y').'.xlsx');
        }

        $datas['date_from'] = $date_from;
        $datas['date_to']   = $date_to;
        return view('admin.reports.customer_data',compact('request','timeline','customer','date_month_arr','get_payment_data_arr'),$datas)->with('rows',$sales);
    }
    public function  export_customer_data(Request $request){
        $date = date('Y-m-d');
        isset($request->date_from)?$date_from   = $request->date_from : $date_from = (date('Y-m-d', strtotime($date .'-1 month')));
        isset($request->date_to)?$date_to       = $request->date_to : $date_to = $date;
        $customer_id = '';
        $timeline_id = '';
        $customers   = Customer::where('active',1)->orderBy('name','ASC')->get();
        $customer    = [];
        foreach ($customers as $customers) {
            $customer[$customers->id] = $customers->name.' -- '.$customers->phone.' -- '.$customers->email;
        }

        $timelines = Timeline::orderBy('id','DESC')->get();
        $timeline  = [];
        foreach ($timelines as $timelines) {
            $timeline[$timelines->id] = $timelines->name;
        }
        $sales =Sale::where('sales.status',1)->where('customers.active',1)->whereNull('customers.deleted_at')
        ->join('customers','sales.customer_id','=','customers.id')
        ->where('sales.date','>=',$date_from)
        ->where('sales.date','<=',$date_to)
        ->select('sales.*');
        if($request->customer_id != ''){
           $sales       = $sales->where('sales.customer_id',$request->customer_id);
           $customer_id = $request->customer_id;
        }
        if($request->timeline_id != ''){
           $sales       = $sales->where('sales.timeline_id',$request->timeline_id);
           $timeline_id = $request->timeline_id;
        }
        $sales = $sales->orderBy('sales.date','DESC')->get();
        return  Excel::download(new CustomerData($items,$request), 'call_card_report_'.date('d-m-Y').'.xlsx');
        $datas['date_from'] = $date_from;
        $datas['date_to']   = $date_to;
        return view('admin.reports.customer_data',compact('request','timeline','customer'),$datas)->with('rows',$sales);
    }
    public function estimate_payment(Request $request){
        $date = date('Y-m-d');
        isset($request->date_from)?$date_from   = $request->date_from : $date_from = (date('Y-m-d', strtotime($date .'-6 month')));
        isset($request->date_to)?$date_to       = $request->date_to : $date_to = $date;
        if($date_from > $date_to){
            $notification = array(
                'message'       => "Please Try again!",
                'alert-type'    => 'info'
            );
            return redirect()->back()->with($notification);
        }

        $customer_id = '';
        $timeline_id = '';
        $customers   = Customer::where('active',1)->orderBy('name','ASC')->get();
        $customer    = [];
        foreach ($customers as $customers) {
            $customer[$customers->id] = $customers->name.' -- '.$customers->phone.' -- '.$customers->email;
        }

        $timelines = Timeline::orderBy('id','DESC')->get();
        $timeline  = [];
        foreach ($timelines as $timelines) {
            $timeline[$timelines->id] = $timelines->name;
        }
        $sales = Sale::where('sales.status',1)->where('customers.active',1)->whereNull('customers.deleted_at')
        ->join('customers','sales.customer_id','=','customers.id')
        ->where('sales.date','>=',$date_from)
        ->where('sales.date','<=',$date_to)
        ->select('sales.*');
        if($request->customer_id != ''){
           $sales       = $sales->where('sales.customer_id',$request->customer_id);
           $customer_id = $request->customer_id;
        }
        if($request->timeline_id != ''){
           $sales       = $sales->where('sales.timeline_id',$request->timeline_id);
           $timeline_id = $request->timeline_id;
        }
        $sales = $sales->orderBy('sales.date','DESC')->get();

        $sale_id_arr = $sales->pluck('id');
        // $stringids = implode(",", $sale_id_arr);
        $get_from_date_to_date = Payment::select(DB::raw("
                                        MIN(payment_date) AS from_date,
                                        MAX(payment_date) AS to_date
                                        "))
                                    ->whereIn('sale_id',$sale_id_arr)
                                    ->whereNull('deleted_at')
                                    ->first();
        // $get_payment_data = Payment::whereIn('sale_id',$sale_id_arr)->orderBy('sale_id','ASC')->orderBy('payment_date','ASC')->get();
        $get_payment_data = DB::table("payments")
            ->select("payments.*" ,DB::raw("(sum(payments.amount)) as sum_ammount,(sum(payments.interest)) as sum_interest,(sum(payments.total)) as sum_total,(COUNT(payments.id)) as no_payment,YEAR(payment_date) year, MONTH(payment_date) month"))
            ->whereIn('sale_id',$sale_id_arr)
            ->orderBy('payment_date')
            ->groupBy(DB::raw("month","year"))
            ->get();
        $get_payment_data_arr = [];
        foreach ($get_payment_data as $pay_row) {
            $get_payment_data_arr[date('m-Y',strtotime($pay_row->payment_date))] = [
                                                                                'total'      => $pay_row->sum_total,
                                                                                'amount'     => $pay_row->sum_ammount,
                                                                                'interest'   => $pay_row->sum_interest,
                                                                                ];
        }
        $d1         = new \DateTime($get_from_date_to_date->from_date);
        $d2         = new \DateTime($get_from_date_to_date->to_date);
        $d2         = $d2->modify( '+1 day' );
        $intervals  = \DateInterval::createFromDateString('1 day');
        $period     = new \DatePeriod($d1, $intervals, $d2);

        foreach ($period as $dt) {
            $date_month_arr[$dt->format('Y-m')] = $dt->format('Y-m-d');
        }

        $datas['date_from'] = $date_from;
        $datas['date_to']   = $date_to;
        return view('admin.reports.estimate_payment',compact('request','timeline','customer','date_month_arr','get_payment_data_arr'),$datas)->with('rows',$sales);
    }
    public function estimate_payment_detail(Request $request){
        $date = date('Y-m-d');
        isset($request->date_from)?$date_from   = $request->date_from : $date_from = (date('Y-m-01'));
        isset($request->date_to)?$date_to       = $request->date_to : $date_to = (date('Y-m-t'));
        if($date_from > $date_to){
            $notification = array(
                'message'       => "Please Try again!",
                'alert-type'    => 'info'
            );
            return redirect()->back()->with($notification);
        }

        $customer_id = '';
        $timeline_id = '';
        $customers   = Customer::where('active',1)->orderBy('name','ASC')->get();
        $customer    = [];
        foreach ($customers as $customers) {
            $customer[$customers->id] = $customers->name.' -- '.$customers->phone.' -- '.$customers->email;
        }

        $timelines = Timeline::orderBy('id','DESC')->get();
        $timeline  = [];
        foreach ($timelines as $timelines) {
            $timeline[$timelines->id] = $timelines->name;
        }
        $payments = Payment::where('sales.status',1)->where('customers.active',1)->whereNull('customers.deleted_at')
        ->join('sales','sales.id','=','payments.sale_id')
        ->join('customers','sales.customer_id','=','customers.id')
        ->where('payments.payment_date','>=',$date_from)
        ->where('payments.payment_date','<=',$date_to)
        ->select('payments.*','sales.date AS sale_date','sales.product_name','sales.serial','customers.name AS cus_name','customers.phone AS cus_phone');
        if($request->customer_id != ''){
           $payments    = $payments->where('sales.customer_id',$request->customer_id);
           $customer_id = $request->customer_id;
        }
        if($request->timeline_id != ''){
           $payments    = $payments->where('sales.timeline_id',$request->timeline_id);
           $timeline_id = $request->timeline_id;
        }
        $payments           = $payments->orderBy('payments.payment_date','ASC')->get();
        $datas['date_from'] = $date_from;
        $datas['date_to']   = $date_to;
        return view('admin.reports.estimate_payment_detail',compact('request','timeline','customer'),$datas)->with('rows',$payments);
    }
    public function customer_payment_report(Request $request){
       $date = date('Y-m-d');
        isset($request->date_from)?$date_from   = $request->date_from : $date_from = (date('Y-m-d', strtotime($date .'-1 year')));
        isset($request->date_to)?$date_to       = $request->date_to : $date_to = $date;
        $customer_id = '';
        $timeline_id = '';
        $customers   = Customer::where('active',1)->orderBy('name','ASC')->get();
        $customer    = [];
        foreach ($customers as $customers) {
            $customer[$customers->id] = $customers->name.' -- '.$customers->phone.' -- '.$customers->email;
        }

        $timelines = Timeline::orderBy('id','DESC')->get();
        $timeline  = [];
        foreach ($timelines as $timelines) {
            $timeline[$timelines->id] = $timelines->name;
        }
        $sales =Sale::where('sales.status',1)->where('customers.active',1)->whereNull('customers.deleted_at')
        ->join('customers','sales.customer_id','=','customers.id')
        ->where('sales.date','>=',$date_from)
        ->where('sales.date','<=',$date_to)
        ->select('sales.*');
        if($request->customer_id != ''){
           $sales       = $sales->where('sales.customer_id',$request->customer_id);
           $customer_id = $request->customer_id;
        }
        if($request->timeline_id != ''){
           $sales       = $sales->where('sales.timeline_id',$request->timeline_id);
           $timeline_id = $request->timeline_id;
        }
        $sales              = $sales->orderBy('sales.date','DESC')->get();
        $datas['date_from'] = $date_from;
        $datas['date_to']   = $date_to;
        return view('admin.reports.customer_payment_report',compact('request','timeline','customer'),$datas)->with('rows',$sales);
    }


    public function customer_dialy_payment_report(Request $request){
        $date = date('Y-m-d');
         isset($request->date_to)?$date_to       = $request->date_to : $date_to = $date;
         $customer_id = '';
         $timeline_id = '';
         $customers   = Customer::where('active',1)->orderBy('name','ASC')->get();
         $customer    = [];
         foreach ($customers as $customers) {
             $customer[$customers->id] = $customers->name.' -- '.$customers->phone.' -- '.$customers->email;
         }

         $timelines = Timeline::orderBy('id','DESC')->get();
         $timeline  = [];
         foreach ($timelines as $timelines) {
             $timeline[$timelines->id] = $timelines->name;
         }
         $sales =Sale::where('sales.status',1)->where('customers.active',1)->whereNull('customers.deleted_at')
         ->join('customers','sales.customer_id','=','customers.id')
        //  ->where('sales.date','>=',$date_from)
         ->where('sales.date','=',$date_to)
         ->select('sales.*');
         if($request->customer_id != ''){
            $sales       = $sales->where('sales.customer_id',$request->customer_id);
            $customer_id = $request->customer_id;
         }
         if($request->timeline_id != ''){
            $sales       = $sales->where('sales.timeline_id',$request->timeline_id);
            $timeline_id = $request->timeline_id;
         }
         $sales              = $sales->orderBy('sales.date','DESC')->get();
         $datas['date_to']   = $date_to;
         return view('admin.reports.customer_dialy_payment_report',compact('request','timeline','customer'),$datas)->with('rows',$sales);
     }





    public function invoice_daily_report(Request $request){
        $date = date('Y-m-d');
        isset($request->date_from)?$date_from   = $request->date_from : $date_from = (date('Y-m-d', strtotime($date .'-1 month')));
        isset($request->date_to)?$date_to       = $request->date_to : $date_to = $date;
        $customer_id = '';
        $timeline_id = '';
        $customers   = Customer::where('active',1)->orderBy('name','ASC')->get();
        $siteprofile = Siteprofile::first();
        $customer    = [];
        foreach ($customers as $customers) {
            $customer[$customers->id] = $customers->name.' -- '.$customers->phone.' -- '.$customers->email;
        }

        $timelines = Timeline::orderBy('id','DESC')->get();
        $timeline  = [];
        foreach ($timelines as $timelines) {
            $timeline[$timelines->id] = $timelines->name;
        }
        $payment = Payment::where('payments.status','<>','notpaid')->where('customers.active',1)->whereNull('customers.deleted_at')
        ->join('sales','sales.id','=','payments.sale_id')
        ->join('customers','sales.customer_id','=','customers.id')
        ->where('payments.actual_date','>=',$date_from)
        ->where('payments.actual_date','<=',$date_to)
        ->select('payments.*');
        if($request->customer_id != ''){
           $payment     = $payment->where('sales.customer_id',$request->customer_id);
           $customer_id = $request->customer_id;
        }
        if($request->timeline_id != ''){
           $payment     = $payment->where('sales.timeline_id',$request->timeline_id);
           $timeline_id = $request->timeline_id;
        }
        $payment            = $payment->orderBy('payments.actual_date','DESC')->get();
        $datas['date_from'] = $date_from;
        $datas['date_to']   = $date_to;
        return view('admin.reports.invoice_daily_report',compact('request','timeline','customer','siteprofile'),$datas)->with('rows',$payment);
    }
    public function get_province(){
        $province  = Province::get();
        $provinces = [];
        foreach ($province as $provin) {
            $provinces[$provin->province_id] = $provin->province_kh_name;
        }
        return $provinces;
    }
    public function customer_topay_report(Sale $loan,Request $request){
        $province = $this->get_province();
        $date = date('Y-m-d');
        isset($request->date_from)?$date_from   = $request->date_from : $date_from = (date('Y-m-d', strtotime($date .'-7 days')));
        isset($request->date_to)?$date_to       = $request->date_to : $date_to = (date('Y-m-d', strtotime($date .'+7 days')));
        $customer_id        = '';
        $timeline_id        = '';
        $duration_type      = '';

        $customers          = Customer::where('active',1)->orderBy('name','ASC')->get();
        $customer           = [];
        foreach ($customers as $customers) {
            $customer[$customers->id] = $customers->name.' -- '.$customers->phone.' -- '.$customers->email;
        }

        $timelines = Timeline::orderBy('id','DESC')->get();
        $timeline  = [];
        foreach ($timelines as $timelines) {
            $timeline[$timelines->id] = $timelines->name;
        }

        $duration_types     = LoanDurationType::orderBy('id', 'ASC')->pluck('type_en', 'slug');
        $villages           = Village::orderBy('vill_id','ASC')->pluck('village_name');
        // dd($duration_types );
        // $duration_type      = [];
        // foreach($duration_types as $duration_types){
        //     $duration_type[$duration_types->type_en]=$duration_types->type_en;
        // }

        // $provinces = Province::orderBy('created_at', 'ASC')->get()->pluck('province_id');
        // dd($provinces);

        $payment = Payment::where('payments.status','=','unpaid')->where('customers.active',1)->whereNull('customers.deleted_at')
        ->join('sales','sales.id','=','payments.sale_id')
        ->join('customers','customers.id','=','sales.customer_id')
        ->where('payments.payment_date','>=',$date_from)
        ->where('payments.payment_date','<=',$date_to)
        // ->where('payments.status','<>','paid')
        ->where('sales.is_black_list',0)
        ->where('sales.approve_status',['approved','payoff'])
        ->select('payments.*','sales.customer_id','sales.currency_type','sales.duration_type','customers.province_id');
        // dd($payment);
        if($request->customer_id != ''){
           $payment     = $payment->where('sales.customer_id',$request->customer_id);
           $customer_id = $request->customer_id;
        }
        if($request->timeline_id != ''){
           $payment     = $payment->where('sales.timeline_id',$request->timeline_id);
           $timeline_id = $request->timeline_id;
        }
        if($request->duration_type != ''){
            $payment        = $payment->where('sales.duration_type', $request->duration_type);
            $duration_type  = $request->duration_type;
        }
        if($request->province_id != ''){
            $payment = $payment->where('customers.province_id',$request->province_id);
        }
        if($request->district_id != ''){
            $payment = $payment->where('customers.district_id',$request->district_id);
        }
        if($request->commune_id != ''){
            $payment = $payment->where('customers.commune_id',$request->commune_id);
        }
        if($request->village_id != ''){
            $payment = $payment->where('customers.village_id',$request->village_id);
        }
        // $payment = $payment->orderBy('sales.customer_id','ASC')->orderBy('payments.payment_date','ASC')->get();
        $payment            = $payment->orderBy('payments.payment_date','ASC')->get();
        $datas['date_from'] = $date_from;
        $datas['date']      = $date;
        $datas['date_to']   = $date_to;
        return view('admin.reports.customer_topay_report',compact('request','timeline','customer','loan','duration_types','province','villages'),$datas)->with('rows',$payment);
    }
    public function sale_payment_report(Request $request){
        $date = date('Y-m-d');
        isset($request->date_from)?$date_from = $request->date_from : $date_from = (date('Y-m-d', strtotime($date .'-1 month')));
        isset($request->date_to)?$date_to = $request->date_to : $date_to = $date;
        $customer_id = '';
        $co_id       = '';
        $timeline_id = '';
        $customers   = Customer::where('active',1)->orderBy('name','ASC')->get();
        $customer    = [];
        foreach ($customers as $customers) {
            $customer[$customers->id] = $customers->name.' -- '.$customers->phone.' -- '.$customers->email;
        }
        $timelines = Timeline::orderBy('id','DESC')->get();
        $timeline  = [];
        foreach ($timelines as $timelines) {
            $timeline[$timelines->id] = $timelines->name;
        }

        $co     = User::where('is_co',1)->orderBy('name_kh','ASC')->get();
        $co_users  = [];
        foreach($co as $co){
            $co_users[$co->id]= $co->name;
        }

        $payment = PaymentTransaction::leftJoin('sales','sales.id','=','payment_transactions.loan_id')
        ->leftJoin('customers','customers.id','=','sales.customer_id')
        ->leftjoin('users','users.id','sales.co_id')
        // ->leftjoin('payments','payments.sale_id','payment_transactions.loan_id')
        ->where('payment_transactions.date','>=',$date_from)
        ->where('payment_transactions.date','<=',$date_to)
        ->whereNull('payment_transactions.deleted_at')
        ->select('payment_transactions.*','sales.inv_no as loan_no','sales.date as loan_date','customers.name_kh as cus_name','users.name','sales.currency_type');

        // $payment = PaymentTransaction::leftJoin('sales','sales.id','=','payment_transactions.loan_id')
        // ->leftJoin('customers','customers.id','=','sales.customer_id')
        // ->where('payment_transactions.date','>=',$date_from)
        // ->where('payment_transactions.date','<=',$date_to)
        // ->select('payment_transactions.*','sales.inv_no as loan_no','sales.date as loan_date','customers.name as cus_name','sales.currency_type');

        if($request->customer_id != ''){
           $payment     = $payment->where('sales.customer_id',$request->customer_id);
           $customer_id = $request->customer_id;
        }
        if($request->co_id != ''){
            $payment     = $payment->where('sales.co_id',$request->co_id);
            $co_id       = $request->co_id;
        }
        if($request->timeline_id != ''){
           $payment     = $payment->where('sales.timeline_id',$request->timeline_id);
           $timeline_id = $request->timeline_id;
        }
        if(!empty($request->loan_no)){
            $payment = $payment->where('sales.inv_no','like','%'.$request->loan_no.'%');
        }
        $payment = $payment->orderBy('payment_transactions.date','DESC')->orderBy('payment_transactions.loan_id','DESC')->get();
        // dd($payment);
        // $data_arr = [];
        // $trans_arr = [];
        // foreach ($payment as $key => $value) {
        //     $data_arr[$value->t_status][$value->t_id] = $value->tran_amount_usd;
        // }

        // $id_arr = $payment->pluck('id');

        // $transactiuon_data = Transaction::whereIn('payment_id',$id_arr)->get();
        // $tran_arr = [];
        // $tran_arr_date = [];
        // foreach ($transactiuon_data as $transac) {
        //     $tran_arr_date[$transac->payment_id][$transac->date] = 1;
        //     if(isset($tran_arr[$transac->payment_id][$transac->date][$transac->status])){
        //         $tran_arr[$transac->payment_id][$transac->date][$transac->status] += $transac->amount_usd;
        //     }else{
        //         $tran_arr[$transac->payment_id][$transac->date][$transac->status] = $transac->amount_usd;
        //     }
        // }
        $datas['date_from'] = $date_from;
        $datas['date_to']   = $date_to;
        return view('admin.reports.sale_payment_report',compact('request','timeline','customer','co_users'),$datas)->with('rows',$payment);
    }


    public function sale_daily_payment_report(Request $request){
        $date = date('Y-m-d');
        // isset($request->date_from)?$date_from = $request->date_from : $date_from = (date('Y-m-d', strtotime($date .'-1 month')));
        isset($request->date_to)?$date_to = $request->date_to : $date_to = $date;
        $customer_id = '';
        $co_id       = '';
        $timeline_id = '';
        $customers   = Customer::where('active',1)->orderBy('name','ASC')->get();
        $customer    = [];
        foreach ($customers as $customers) {
            $customer[$customers->id] = $customers->name.' -- '.$customers->phone.' -- '.$customers->email;
        }
        $timelines = Timeline::orderBy('id','DESC')->get();
        $timeline  = [];
        foreach ($timelines as $timelines) {
            $timeline[$timelines->id] = $timelines->name;
        }

        $co     = User::where('is_co',1)->orderBy('name_kh','ASC')->get();
        $co_users  = [];
        foreach($co as $co){
            $co_users[$co->id]= $co->name;
        }

        $payment = PaymentTransaction::leftJoin('sales','sales.id','=','payment_transactions.loan_id')
        ->leftJoin('customers','customers.id','=','sales.customer_id')
        ->leftjoin('users','users.id','sales.co_id')
        // ->leftjoin('payments','payments.sale_id','payment_transactions.loan_id')
        // ->where('payment_transactions.date','>=',$date_from)
        ->where('payment_transactions.date','=',$date_to)
        ->whereNull('payment_transactions.deleted_at')
        ->select('payment_transactions.*','sales.inv_no as loan_no','sales.date as loan_date','customers.name_kh as cus_name','users.name','sales.currency_type');

        // $payment = PaymentTransaction::leftJoin('sales','sales.id','=','payment_transactions.loan_id')
        // ->leftJoin('customers','customers.id','=','sales.customer_id')
        // ->where('payment_transactions.date','>=',$date_from)
        // ->where('payment_transactions.date','<=',$date_to)
        // ->select('payment_transactions.*','sales.inv_no as loan_no','sales.date as loan_date','customers.name as cus_name','sales.currency_type');

        if($request->customer_id != ''){
           $payment     = $payment->where('sales.customer_id',$request->customer_id);
           $customer_id = $request->customer_id;
        }
        if($request->co_id != ''){
            $payment     = $payment->where('sales.co_id',$request->co_id);
            $co_id       = $request->co_id;
        }
        if($request->timeline_id != ''){
           $payment     = $payment->where('sales.timeline_id',$request->timeline_id);
           $timeline_id = $request->timeline_id;
        }
        if(!empty($request->loan_no)){
            $payment = $payment->where('sales.inv_no','like','%'.$request->loan_no.'%');
        }
        $payment = $payment->orderBy('payment_transactions.date','DESC')->orderBy('payment_transactions.loan_id','DESC')->get();
        // dd($payment);
        // $data_arr = [];
        // $trans_arr = [];
        // foreach ($payment as $key => $value) {
        //     $data_arr[$value->t_status][$value->t_id] = $value->tran_amount_usd;
        // }

        // $id_arr = $payment->pluck('id');

        // $transactiuon_data = Transaction::whereIn('payment_id',$id_arr)->get();
        // $tran_arr = [];
        // $tran_arr_date = [];
        // foreach ($transactiuon_data as $transac) {
        //     $tran_arr_date[$transac->payment_id][$transac->date] = 1;
        //     if(isset($tran_arr[$transac->payment_id][$transac->date][$transac->status])){
        //         $tran_arr[$transac->payment_id][$transac->date][$transac->status] += $transac->amount_usd;
        //     }else{
        //         $tran_arr[$transac->payment_id][$transac->date][$transac->status] = $transac->amount_usd;
        //     }
        // }
        // $datas['date_from'] = $date_from;
        $datas['date_to']   = $date_to;
        return view('admin.reports.sale_dialy_payment_report',compact('request','timeline','customer','co_users'),$datas)->with('rows',$payment);
    }
    public function repayloand_report(Request $request)
    {
        $date = date('Y-m-d');
        isset($request->date_from)?$date_from   = $request->date_from : $date_from = (date('Y-m-d', strtotime($date .'-1 month')));
        isset($request->date_to)?$date_to       = $request->date_to : $date_to = $date;

        $dealers = Dealer::where('active',1)->orderBy('id','DESC')->get();
        $dealer  = [];
        foreach ($dealers as $dealers) {
            $dealer[$dealers->id] = $dealers->name.' -- '.$dealers->phone.' -- '.$dealers->email;
        }

        $branchdealers = BranchDealer::where('id','')->orderBy('id','DESC')->get();
        if($request->dealer_id != ''){
            $branchdealers = BranchDealer::where('dealer_id',$request->dealer_id)->orderBy('id','DESC')->get();
        }
        $branchdealer = [];
        foreach ($branchdealers as $branchdealers) {
            $branchdealer[$branchdealers->id] = $branchdealers->name;
        }
        $loans =Loan::where('status','=','payment')->where('dealers.active',1)->whereNull('dealers.deleted_at')
        ->join('dealers','loans.dealer_id','=','dealers.id')
        ->where('loans.date','>=',$date_from)
        ->where('loans.date','<=',$date_to)
        ->select('loans.*');
        if($request->dealer_id != ''){
           $loans       = $loans->where('loans.dealer_id',$request->dealer_id);
           $dealer_id   = $request->dealer_id;
        }
        $loans              = $loans->orderBy('loans.date','ASC')->get();
        $datas['date_from'] = $date_from;
        $datas['date_to']   = $date_to;
        return view('admin.reports.repayloand_report',compact('request','dealer','branchdealer'),$datas)->with('rows',$loans);
    }
    public function credit_report(Request $request)
    {
        $date = date('Y-m-d');
        isset($request->date_from)?$date_from   = $request->date_from : $date_from = (date('Y-m-d', strtotime($date .'-1 month')));
        isset($request->date_to)?$date_to       = $request->date_to : $date_to = $date;
        $customer_id = '';
        $customers   = Customer::where('active',1)->where('type','account')->orderBy('name','ASC')->get();
        $customer    = [];
        foreach ($customers as $customers) {
            $customer[$customers->id] = $customers->name.' -- '.$customers->phone.' -- '.$customers->email;
        }
        $credits =Journal::where('status','=','credit')->where('customers.active',1)->whereNull('customers.deleted_at')
        ->join('customers','journals.customer_id','=','customers.id')
        ->where('journals.date','>=',$date_from)
        ->where('journals.date','<=',$date_to)
        ->select('journals.*');
        if($request->customer_id != ''){
           $credits     = $credits->where('journals.customer_id',$request->customer_id);
           $customer_id = $request->customer_id;
        }
        $credits = $credits->orderBy('journals.date','ASC')->get();
        $datas['date_from'] = $date_from;
        $datas['date_to'] = $date_to;
        return view('admin.reports.credit_report',compact('request','customer'),$datas)->with('rows',$credits);
    }
    public function debit_report(Request $request)
    {
        $date = date('Y-m-d');
        isset($request->date_from)?$date_from   = $request->date_from : $date_from = (date('Y-m-d', strtotime($date .'-1 month')));
        isset($request->date_to)?$date_to       = $request->date_to : $date_to = $date;
        $customer_id = '';
        $customers = Customer::where('active',1)->where('type','account')->orderBy('name','ASC')->get();
        $customer = [];
        foreach ($customers as $customers) {
            $customer[$customers->id] = $customers->name.' -- '.$customers->phone.' -- '.$customers->email;
        }
        $debits =Journal::where('status','=','debit')->where('customers.active',1)->whereNull('customers.deleted_at')
        ->join('customers','journals.customer_id','=','customers.id')
        ->where('journals.date','>=',$date_from)
        ->where('journals.date','<=',$date_to)
        ->select('journals.*');
        if($request->customer_id != ''){
           $debits      = $debits->where('journals.customer_id',$request->customer_id);
           $customer_id = $request->customer_id;
        }
        $debits             = $debits->orderBy('journals.date','ASC')->get();
        $datas['date_from'] = $date_from;
        $datas['date_to']   = $date_to;
        return view('admin.reports.debit_report',compact('request','customer'),$datas)->with('rows',$debits);
    }
    public function statement_report(Request $request)
    {
        $date = date('Y-m-d');
        isset($request->date_from)?$date_from   = $request->date_from : $date_from = (date('Y-m-d', strtotime($date .'-1 month')));
        isset($request->date_to)?$date_to       = $request->date_to : $date_to = $date;
        $customer_id = '';
        $customers   = Customer::where('active',1)->where('type','account')->orderBy('name','ASC')->get();
        $customer    = [];
        foreach ($customers as $customers) {
            $customer[$customers->id] = $customers->name.' -- '.$customers->phone.' -- '.$customers->email;
        }
        $statements =Journal::where('status','=','statement')->where('customers.active',1)->whereNull('customers.deleted_at')
        ->join('customers','journals.customer_id','=','customers.id')
        ->where('journals.date','>=',$date_from)
        ->where('journals.date','<=',$date_to)
        ->select('journals.*');
        if($request->customer_id != ''){
           $statements  = $statements->where('journals.customer_id',$request->customer_id);
           $customer_id = $request->customer_id;
        }
        $statements         = $statements->orderBy('journals.date','ASC')->get();
        $datas['date_from'] = $date_from;
        $datas['date_to']   = $date_to;
        return view('admin.reports.statement_report',compact('request','customer'),$datas)->with('rows',$statements);
    }
    public function transaction_report(Request $request)
    {
        $date = date('Y-m-d');
        isset($request->date_from)?$date_from   = $request->date_from : $date_from = (date('Y-m-d', strtotime($date .'-1 month')));
        isset($request->date_to)?$date_to       = $request->date_to : $date_to = $date;
        $customer_id = '';
        $customers   = Customer::where('active',1)->where(function($query){$query->Where('type','loan')->orWhereNull('type');})->orderBy('name','ASC')->get();
        $customer    = [];
        foreach ($customers as $customers) {
            $customer[$customers->id] = $customers->name.' -- '.$customers->phone.' -- '.$customers->email;
        }

        $dealers = Dealer::where('active',1)->orderBy('id','DESC')->get();
        $dealer  = [];
        foreach ($dealers as $dealers) {
            $dealer[$dealers->id] = $dealers->name.' -- '.$dealers->phone.' -- '.$dealers->email;
        }

        $branchdealers = BranchDealer::where('id','')->orderBy('id','DESC')->get();
        if($request->dealer_id != ''){
            $branchdealers = BranchDealer::where('dealer_id',$request->dealer_id)->orderBy('id','DESC')->get();
        }
        $branchdealer = [];
        foreach ($branchdealers as $branchdealers) {
            $branchdealer[$branchdealers->id] = $branchdealers->name;
        }
        $where   ="";
        $wherecu ="";
        $where   = " AND loans.date >= '$date_from' AND loans.date <= '$date_to'";
        if($request->dealer_id != ''){
           $wherecu = "AND loans.dealer_id = '$request->dealer_id'";
        }
        $loans = DB::select("SELECT
					dealer_id,
                    SUM(case when status <> '' AND loans.date < '$date_from' then amount else 0 end) as last_balance,
					SUM(case when status = 'invoice' {$where} then amount else 0 end) as invoice,
					SUM(case when status = 'payment' {$where} then amount else 0 end) as payment,
					dealers.`name`,SUM(amount) as balance
					FROM loans
					INNER JOIN dealers ON loans.dealer_id = dealers.id
                    WHERE loans.status <> '' AND ISNULL(loans.deleted_at) AND dealers.active = 1 AND ISNULL(dealers.deleted_at) {$wherecu}
					GROUP BY dealer_id ORDER BY dealers.name ASC" );
        // dd($loans);
        // $loans =Loan::where('status','<>','')
        // ->where('date','>=',$date_from)
        // ->where('date','<=',$date_to);
        // if($request->customer_id != ''){
        //    $loans = $loans->where('customer_id',$request->customer_id);
        //    $customer_id = $request->customer_id;
        // }
        // $loans = $loans->orderBy('id','DESC')->paginate(25)->withPath('?date_from='.$date_from,'&date_to='.$date_to,'&customer_id='.$customer_id);
        $datas['date_from'] = $date_from;
        $datas['date_to']   = $date_to;
        return view('admin.reports.transaction_report',compact('request','dealer','branchdealer'),$datas)->with('rows',$loans);
    }
    public function balance_report(Request $request){

        $loans = DB::select("SELECT
                    dealer_id,
                    SUM(case when status <> '' then amount else 0 end) as last_balance,
                    SUM(case when status = 'loan' then amount else 0 end) as loan,
                    SUM(case when status = 'repay'  then amount else 0 end) as repay,
                    dealers.`name`,SUM(amount) as balance
                    FROM loans
                    INNER JOIN dealers ON loans.dealer_id = dealers.id
                    WHERE loans.status <> '' AND ISNULL(loans.deleted_at) AND dealers.active = 1 AND ISNULL(dealers.deleted_at)
                    GROUP BY dealer_id ORDER BY dealers.name ASC");
        return view('admin.reports.balance_report')->with('rows',$loans);
    }
    public function account_report(Request $request)
    {
        $date = date('Y-m-d');
        // isset($request->date_from)?$date_from = $request->date_from : $date_from = (date('Y-m-d', strtotime($date .'-1 month')));
        isset($request->date_from)?$date_from   = $request->date_from : $date_from = $date;
        isset($request->date_to)?$date_to       = $request->date_to : $date_to = $date;
        $customer_id = '';
        $customers   = Customer::where('active',1)->where('type','account')->orderBy('name','ASC')->get();
        $customer    = [];
        foreach ($customers as $customers) {
            $customer[$customers->id] = $customers->name.' -- '.$customers->phone.' -- '.$customers->email;
        }
        $where   ="";
        $wherecu ="";
        $where   = " AND journals.date >= '$date_from' AND journals.date <= '$date_to'";
        if($request->customer_id != ''){
           $wherecu = "AND journals.customer_id = '$request->customer_id'";
        }
        $accounts = DB::select("SELECT
                    customer_id,
                    SUM(case when status <> '' AND journals.date < '$date_from' then amount else 0 end) as last_balance,
                    SUM(case when status = 'credit' {$where} then amount else 0 end) as credit,
                    SUM(case when status = 'debit' {$where} then amount else 0 end) as debit,
                    SUM(case when status = 'statement' {$where} then amount else 0 end) as statement,
                    customers.`name`,SUM(amount) as balance
                    FROM journals
                    INNER JOIN customers ON journals.customer_id = customers.id
                    WHERE journals.status <> '' AND ISNULL(journals.deleted_at) AND customers.active = 1 AND ISNULL(customers.deleted_at) {$wherecu}
                    GROUP BY customer_id ORDER BY customers.name ASC" );
        $datas['date_from'] = $date_from;
        $datas['date_to']   = $date_to;
        return view('admin.reports.account_report',compact('request','customer'),$datas)->with('rows',$accounts);
    }
    public function daily_report(Request $request){
        $year_arr              = [];
        $month_arr             = [];
        $where                 = "";
        $date                  = date('Y-m-d');
        $months                = isset($request->month)?$request->month:date('m');
        $years                 = isset($request->year)?$request->year:date('Y');
        $date_one_month_arr    = [];
        $date_month_arr        = [];
        $data_no_payment_arr   = [];
        $no_payment            = '';
        $from_date             = date('Y-m-01',strtotime($years.'-'.$months.'-01'));
        $to_date               = date('Y-m-t',strtotime($years.'-'.$months.'-01'));
        $day_arr = [
            'Mon',
            'Tue',
            'Wed',
            'Thu',
            'Fri',
            'Sat',
            'Sun',
        ];
        $get_year = DB::table("sales")
        ->select('sales.*')
        ->orderBy('date','asc')
        ->groupBy(DB::raw("YEAR(date)"))->get();
        if($get_year){
            foreach ($get_year as $year) {
                $year_arr[date('Y',strtotime($year->date))] = date('Y',strtotime($year->date));
            }
        }
        for ($m=1; $m<=12; $m++) {
            $month = date('F', mktime(0,0,0,$m, 1, date('Y')));
            $month_arr[date('m',strtotime($month))] = $month;
         }
        // $payment_sexmonth = DB::table("payments")
        // ->select("payments.*" ,DB::raw("(sum(payments.amount)) as sum_ammount,(sum(payments.interest)) as sum_interest,(sum(payments.total)) as sum_total,(COUNT(payments.id)) as payment_no"))
        // ->where('actual_date','>=',$from_date)
        // ->where('actual_date','<=',$to_date)
        //  ->where('status','=','paid')
        // ->orderBy('actual_date')
        // ->groupBy(DB::raw("actual_date,payment_type"))
        // ->get();
        $where              = "AND payments.actual_date >= '$from_date' AND payments.actual_date <= '$to_date'";
        $payment_sexmonth   = DB::select("SELECT
                payments.actual_date,
                SUM(case when payment_type <> 'bank' {$where} then (t_amount + t_interest) else 0 end) as cash_amount,
                SUM(case when payment_type = 'bank' {$where} then (t_amount + t_interest) else 0 end) as bank_amount,
                SUM(payments.t_amount) as sum_ammount,
                SUM(t_interest) as interest,COUNT(payments.id) as payment_no
                FROM payments
                INNER JOIN sales ON payments.sale_id = sales.id
                WHERE ISNULL(sales.deleted_at) AND  (payments.status <> 'notpaid' OR payments.status ='paitail') {$where}
                GROUP BY payments.actual_date ORDER BY payments.actual_date ASC
            ");
        $no_payment = DB::table("payments")
        ->select("payments.*" ,DB::raw("(sum(payments.amount)) as sum_ammount,(sum(payments.interest)) as sum_interest,(sum(payments.total)) as sum_total,(COUNT(payments.id)) as no_payment"))
        ->where('payment_date','>=',$from_date)
        ->where('payment_date','<=',$to_date)
        ->where('status','<>','paid')
        ->where('is_complete',0)
        // ->where('t_amount','>',0)
        ->orderBy('payment_date')
        ->groupBy(DB::raw("payment_date"))
        ->get();
        $d1         = new \DateTime($from_date);
        $d2         = new \DateTime($to_date);
        $d2         = $d2->modify( '+1 day' );
        $intervals  = \DateInterval::createFromDateString('1 day');
        $period     = new \DatePeriod($d1, $intervals, $d2);
        foreach ($period as $dt) {
            $date_month_arr[$dt->format('Y-m-d')] = $dt->format('Y-m-d');
        }
        foreach ($payment_sexmonth as $payment) {
           $date_one_month_arr[$payment->actual_date]['payment_no_id']  = $payment->payment_no;
           $date_one_month_arr[$payment->actual_date]['sum_ammount']    = $payment->sum_ammount;
           $date_one_month_arr[$payment->actual_date]['sum_interest']   = $payment->interest;
           $date_one_month_arr[$payment->actual_date]['cash_amount']    = $payment->cash_amount;
           $date_one_month_arr[$payment->actual_date]['bank_amount']    = $payment->bank_amount;
           $date_one_month_arr[$payment->actual_date]['sum_total']      = $payment->cash_amount + $payment->bank_amount;
        }
        // dd($date_one_month_arr);
        if($no_payment){
            foreach ($no_payment as $no_pay) {
               $data_no_payment_arr[$no_pay->payment_date]['no_payment'] = $no_pay->sum_ammount;
            }
        }

        // $from  = date('Y-m-d',strtotime('2019-08-01'));
        // $to  = date('Y-m-d',strtotime('2019-08-31'));
        // $step = Carbon\CarbonInterval::day();
        // $period = new DatePeriod($from, $step, $to);

        // $period = Carbon::create('2019-08-01', '2019-08-31');
        // foreach ($period as $date) {
        //     $date_one_month_arr[$date->format('Y-m-d')] = $date->format('Y-m-d');
        // }
        // dd($date_one_month_arr);
        $datas['from_date']             = $from_date;
        $datas['to_date']               = $to_date;
        $datas['month']                 = $months;
        $datas['year']                  = $years;
        $datas['day_arr']               = array_flip($day_arr);
        $datas['date_one_month_arr']    = $date_one_month_arr;
        $datas['date_month_arr']        = $date_month_arr;
        $datas['data_no_payment_arr']   = $data_no_payment_arr;
        return view('admin.reports.daily_report',compact('year_arr','month_arr'),$datas);
    }
    public function profitandloss_report(Request $request)
    {
        $date = date('Y-m-d');
        isset($request->date_from)?$date_from   = $request->date_from : $date_from = date('Y-01-01');
        isset($request->date_to)?$date_to       = $request->date_to : $date_to = date('Y-12-t');
        $date_from                              = date('Y-m-01',strtotime($date_from));
        $date_to                                = date('Y-m-t',strtotime($date_to));
        if($date_from > $date_to){
            $notification = array(
                'message'       => "Please Try again!",
                'alert-type'    => 'info'
            );
            return redirect()->back()->with($notification);
        }
        $d1         = new \DateTime($date_from);
        $d2         = new \DateTime($date_to);
        $d2         = $d2->modify( '+1 day' );
        $intervals  = \DateInterval::createFromDateString('1 day');
        $period     = new \DatePeriod($d1, $intervals, $d2);

        $group_expense = Group_expense::get();
        $groupincome   = GroupIncome::get();
        foreach ($period as $dt) {
            $date_month_arr[$dt->format('Y-m')] = $dt->format('Y-m-d');
        }
        $where      ="";
        $wherecu    ="";
        $where      = " AND tran.date >= '$date_from' AND tran.date <= '$date_to'";

        $profitandloss = DB::select("SELECT
                        tran.*, exp.expense_name,
                        SUM(tran.amount_usd) AS sum_amount_usd,
                        SUM(tran.amount_riel) AS sum_amount_riel,
                        SUM(tran.interest_usd) AS sum_interest_usd,
                        SUM(tran.interest_riel) AS sum_inrerest_riel,
                        SUM(tran.admin_fee) AS sum_admin_fee
                    FROM
                        transactions tran
                    LEFT JOIN expenses exp ON tran.expen_id = exp.id
                    WHERE tran.status <> '' AND tran.status <> 'expense' AND ISNULL(tran.deleted_at)  {$where}
                    GROUP BY
                        tran.`status`,
                        MONTH (tran.date),YEAR(tran.date)
                    ORDER BY tran.date ASC");
        $enpenses = DB::select("SELECT
                                tran.*, exp.expense_name,
                                exp.group_id,
                                gr_exp.group_name,
                                SUM(tran.amount_usd) AS sum_amount_usd,
                                SUM(tran.amount_riel) AS sum_amount_riel,
                                SUM(tran.interest_usd) AS sum_interest_usd,
                                SUM(tran.interest_riel) AS sum_inrerest_riel
                            FROM
                                transactions tran
                            LEFT JOIN expenses exp ON tran.expen_id = exp.id
                            INNER JOIN group_expenses gr_exp ON exp.group_id = gr_exp.id
                            WHERE
                                tran.`status` = 'expense'
                            AND ISNULL(tran.deleted_at) {$where}
                            GROUP BY
                                tran.`status`,
                                MONTH (tran.date),
                                YEAR (tran.date),
                                exp.group_id
                            ORDER BY
                                tran.date ASC");
        $incomes = DB::select("SELECT
                                tran.*, inc.name,
                                inc.group_id,
                                gr_income.name AS group_name,
                                SUM(tran.amount_usd) AS sum_amount_usd,
                                SUM(tran.amount_riel) AS sum_amount_riel,
                                SUM(tran.interest_usd) AS sum_interest_usd,
                                SUM(tran.interest_riel) AS sum_inrerest_riel
                            FROM
                                transactions tran
                            LEFT JOIN incomes inc ON tran.income_id = inc.id
                            INNER JOIN group_incomes gr_income ON inc.group_id = gr_income.id
                            WHERE
                                tran.`status` = 'income'
                            AND ISNULL(tran.deleted_at) {$where}
                            GROUP BY
                                tran.`status`,
                                MONTH (tran.date),
                                YEAR (tran.date),
                                inc.group_id
                            ORDER BY
                                tran.date ASC");
        $profitandloss_arr  = [];
        $enpenses_arr       = [];
        $incomes_arr        = [];
        foreach ($date_month_arr as $month) {
            foreach ($profitandloss as $row) {
                if(date('mY',strtotime($month)) == date('mY',strtotime($row->date))){
                    $profitandloss_arr[date('mY',strtotime($month))][$row->status] = [
                                                                                    'sum_amount_usd'        => $row->sum_amount_usd,
                                                                                    'sum_amount_riel'       => $row->sum_amount_riel,
                                                                                    'sum_interest_usd'      => $row->sum_interest_usd,
                                                                                    'sum_inrerest_riel'     => $row->sum_inrerest_riel,
                                                                                    'sum_admin_fee'         => $row->sum_admin_fee
                                                                                    ];
                }
            }
            foreach ($enpenses as $expense) {
                if(date('mY',strtotime($month)) == date('mY',strtotime($expense->date))){
                    $enpenses_arr[date('mY',strtotime($month))][$expense->status][$expense->group_id] = [
                                                                                    'sum_amount_usd'        => $expense->sum_amount_usd,
                                                                                    'sum_amount_riel'       => $expense->sum_amount_riel,
                                                                                    'sum_interest_usd'      => $expense->sum_interest_usd,
                                                                                    'sum_inrerest_riel'     => $expense->sum_inrerest_riel
                                                                                    ];
                }
            }
            foreach ($incomes as $income) {
                if(date('mY',strtotime($month)) == date('mY',strtotime($income->date))){
                    $incomes_arr[date('mY',strtotime($month))][$income->status][$income->group_id] = [
                                                                                    'sum_amount_usd'        => $income->sum_amount_usd,
                                                                                    'sum_amount_riel'       => $income->sum_amount_riel,
                                                                                    'sum_interest_usd'      => $income->sum_interest_usd,
                                                                                    'sum_inrerest_riel'     => $income->sum_inrerest_riel
                                                                                    ];
                }
            }
        }
        $datas['date_from'] = $date_from;
        $datas['date_to']   = $date_to;
        return view('admin.reports.profitandloss_report',compact('date_month_arr','request','profitandloss','profitandloss_arr','enpenses_arr','group_expense','incomes_arr','groupincome'))->with($datas);
    }
    public function daily_sale_report(Request $request){
        $date                                   = date('Y-m-d');
        isset($request->date_from)?$date_from   = $request->date_from : $date_from = date('Y-m-d');
        isset($request->date_to)?$date_to       = $request->date_to : $date_to = date('Y-m-d');
        $date_from = date('Y-m-d',strtotime($date_from));
        $date_to   = date('Y-m-d',strtotime($date_to));
        if($date_from > $date_to){
            $notification = array(
                'message'       => "Please Try again!",
                'alert-type'    => 'info'
            );
            return redirect()->back()->with($notification);
        }
        $siteprofile    = Siteprofile::first();
        $where          ="";
        $wherecu        ="";
        $where          = " AND tran.date >= '$date_from' AND tran.date <= '$date_to'";
        $daily_sale     = DB::select("SELECT
                        tran.*,
                        SUM(tran.amount_usd) AS sum_amount_usd,
                        SUM(tran.amount_riel) AS sum_amount_riel,
                        SUM(tran.admin_fee) AS sum_admin_fee
                    FROM
                        transactions tran
                    WHERE tran.status <> '' AND ISNULL(tran.deleted_at)  {$where}
                    GROUP BY
                        tran.`status`
                    ORDER BY tran.date ASC");
        $daily_sale_arr = [];
        foreach ($daily_sale as $row) {
            $daily_sale_arr[$row->status] = $row->sum_amount_riel;
            $daily_sale_arr['admin_fee']  = $row->sum_admin_fee;
        }
        $status_arr = [];
        $status_arr = [
                    'investment'        => __('app.capital'),
                    'loan'              => __('app.loan'),
                    // 'used_over_payment' => __('app.last_over_payment_en'),
                    'interest'          => __('app.interest_place_holder'),
                    'saving'            => __('app.saving_label'),
                    // 'operation_fee'     => 'Operation Fee',
                    'admin_fee'         => __('app.operation_fee'),
                    'penalty'           => __('app.penalty'),
                    'principle'         => __('app.principle'),
                    // 'over_payment'      => __('app.over_payment'),
                    'expense'           => __('app.expenses'),
                    // 'payment'           => 'Payment Investment',
                ];
        if($siteprofile->is_income == 1){
            $status_arr += [
                    'income'       => __('app.income')
                ];
        }
        $datas['date_from'] = $date_from;
        $datas['date_to']   = $date_to;
        return view('admin.reports.daily_sale_report',compact('request','daily_sale','daily_sale_arr','status_arr'))->with($datas);
    }
    public function investment_report(Request $request){
        $date                                   = date('Y-m-d');
        isset($request->date_from)?$date_from   = $request->date_from : $date_from = date('Y-m-d');
        isset($request->date_to)?$date_to       = $request->date_to : $date_to = date('Y-m-d');
        $date_from = date('Y-m-d',strtotime($date_from));
        $date_to   = date('Y-m-d',strtotime($date_to));
        if($date_from > $date_to){
            $notification = array(
                'message'       => "Please Try again!",
                'alert-type'    => 'info'
            );
            return redirect()->back()->with($notification);
        }
        $siteprofile    = Siteprofile::first();
        $where          ="";
        $wherecu        ="";
        $where          = " AND tran.date >= '$date_from' AND tran.date <= '$date_to'";
        $daily_sale     = DB::select("SELECT
                        tran.*,
                        SUM(tran.amount_usd) AS sum_amount_usd,
                        SUM(tran.amount_riel) AS sum_amount_riel,
                        SUM(tran.admin_fee) AS sum_admin_fee
                    FROM
                        transactions tran
                    WHERE tran.status <> '' AND ISNULL(tran.deleted_at)
                    GROUP BY
                        tran.`status`
                    ORDER BY tran.date ASC");
        $daily_sale_arr = [];
        foreach ($daily_sale as $row) {
            $daily_sale_arr[$row->status] = $row->sum_amount_riel;
            $daily_sale_arr['admin_fee']  = $row->sum_admin_fee;
        }
        // dd($daily_sale);
        $status_arr = [];
        $status_arr = [
                    'investment'        => __('app.capital'),
                    'loan'              => __('app.loan'),
                    // 'used_over_payment' => __('app.last_over_payment_en'),
                    // 'interest'          => __('app.interest_place_holder'),
                    // 'saving'            => __('app.saving_label'),
                    // 'operation_fee'     => 'Operation Fee',
                    // 'admin_fee'         => __('app.operation_fee'),
                    // 'penalty'           => __('app.penalty_en'),
                    'principle'         => __('app.principle'),
                    // 'over_payment'      => __('app.over_payment'),
                    // 'expense'           => __('app.expenses'),
                    // 'payment'           => 'Payment Investment',
                ];
        // dd($status_arr);

        //income
        // if($siteprofile->is_income == 1){
        //     $status_arr += [
        //             'income'       => __('app.income')
        //         ];
        // }

        $datas['date_from'] = $date_from;
        $datas['date_to']   = $date_to;
        return view('admin.reports.investment_report',compact('request','daily_sale','daily_sale_arr','status_arr'))->with($datas);
    }
    public function channel_report(Request $request){
        $date = date('Y-m-d');
        isset($request->date_from)?$date_from   = $request->date_from : $date_from = date('Y-m-01');
        isset($request->date_to)?$date_to       = $request->date_to : $date_to = date('Y-m-t');
        $date_from  = date('Y-m-d',strtotime($date_from));
        $date_to    = date('Y-m-d',strtotime($date_to));
        if($date_from > $date_to){
            $notification = array(
                'message'       => "Please Try again!",
                'alert-type'    => 'info'
            );
            return redirect()->back()->with($notification);
        }
        $bank       = Bank::all();
        $customers  = Customer::where('active',1)->orderBy('name','ASC')->get();
        $customer   = [];
        foreach ($customers as $customers) {
            $customer[$customers->id] = $customers->name.' -- '.$customers->name_kh.' -- '.$customers->phone.' -- '.$customers->email;
        }
        $where ="";
        $where = " AND tran.date >= '$date_from' AND tran.date <= '$date_to'";
        if($request->customer_id){
            $where = " AND customers.id = '$request->customer_id'";
        }
        if($request->search){
            $where = "AND inv_no like '%' '$request->search' '%'";
        }
        $bankchannel = DB::select("SELECT
                                tran.*,customers.`name` AS cus_name,customers.name_kh AS cus_namekh,customers.phone AS cus_phone,
                                sales.inv_no
                            FROM
                                transactions tran
                                LEFT JOIN payments pay ON tran.payment_id = pay.id
                                INNER JOIN sales ON pay.sale_id = sales.id
                                INNER JOIN customers ON sales.customer_id = customers.id
                            WHERE
                                (tran.`status` = 'principle' OR tran.`status` = 'interest' OR tran.`status` = 'penalty')
                            AND ISNULL(tran.deleted_at) {$where}
                            GROUP BY
                                tran.`id`
                            ORDER BY
                                tran.date ASC,tran.`id` ASC");
        $datas['date_from'] = $date_from;
        $datas['date_to']   = $date_to;
        return view('admin.reports.channel_report',compact('request','bankchannel','bank','customer'))->with($datas);

    }
    public function dealer_sale(Request $request){
        $date                                   = date('Y-m-d');
        isset($request->date_from)?$date_from   = $request->date_from : $date_from = date('Y-m-01');
        isset($request->date_to)?$date_to       = $request->date_to : $date_to = date('Y-m-t');
        $date_from                              = date('Y-m-d',strtotime($date_from));
        $date_to                                = date('Y-m-d',strtotime($date_to));
        if($date_from > $date_to){
            $notification = array(
                'message' => "Please Try again!",
                'alert-type' => 'info'
            );
            return redirect()->back()->with($notification);
        }
        $where ="";
        $where = " AND sales.date >= '$date_from' AND sales.date <= '$date_to'";
        $dealer_sale = DB::select("SELECT
                                    dealers.*, COUNT(sales.id) AS count_sale,
                                    SUM(sales.total) AS total_sale
                                FROM
                                    dealers
                                LEFT JOIN sales ON dealers.id = sales.dealer_id
                                WHERE
                                    ISNULL(dealers.deleted_at)
                                AND ISNULL(sales.deleted_at) {$where}
                                GROUP BY
                                    sales.dealer_id
                                ORDER BY
                                    dealers.`name` ASC");
        $datas['date_from'] = $date_from;
        $datas['date_to']   = $date_to;
        return view('admin.reports.dealer_sale',compact('request','dealer_sale'))->with($datas);
    }
    public function co_report(Request $request){
        $date                                   = date('Y-m-d');
        isset($request->date_from)?$date_from   = $request->date_from : $date_from = date('Y-m-01');
        isset($request->date_to)?$date_to       = $request->date_to : $date_to = date('Y-m-t');
        $date_from                              = date('Y-m-d',strtotime($date_from));
        $date_to                                = date('Y-m-d',strtotime($date_to));
        if($date_from > $date_to){
            $notification = array(
                'message'       => "Please Try again!",
                'alert-type'    => 'info'
            );
            return redirect()->back()->with($notification);
        }
        $where ="";
        $where = " AND sales.date >= '$date_from' AND sales.date <= '$date_to'";
        $payment = " AND payments.actual_date >= '$date_from' AND payments.actual_date <= '$date_to'";
        $active = "AND sales.date <= '$date_to'";

        $co_report = DB::select("SELECT
                                    users.`name`, COUNT(sales.id) AS count_sale,
                                    SUM(sales.total) AS total_sale
                                FROM
                                    users
                                LEFT JOIN sales ON users.id = sales.co_id
                                WHERE
                                    users.is_active = 1
                                AND sales.is_complete != 1
                                AND ISNULL(sales.deleted_at) {$where}
                                GROUP BY
                                    sales.co_id
                                ORDER BY
                                    users.`name` ASC");
        $loan_amount = DB::select("SELECT
                                    users.`name`, COUNT(sales.id) AS count_sale,
                                    SUM(sales.total) AS total_sale,
                                    -- SUM(transactions.admin_fee) AS admin_fee,
                                    SUM(CASE WHEN transactions.status='principle' THEN transactions.amount_riel END) AS collect_principle,
                                    SUM(CASE WHEN transactions.status='interest' THEN transactions.amount_riel END) AS collect_interest,
                                    SUM(CASE WHEN transactions.status='saving' THEN transactions.amount_riel END) AS collect_saving,
                                    SUM(CASE WHEN transactions.status='advance_fine' THEN transactions.amount_riel END) AS collect_advance_fine
                                    -- COUNT(CASE WHEN payments.status='paid' THEN payments.id END) AS number_of_collect_payment
                                FROM
                                    users
                                LEFT JOIN sales ON users.id = sales.co_id
                                JOIN transactions ON transactions.sale_id = sales.id
                                -- JOIN payments ON payments.sale_id = sales.id
                                WHERE
                                    users.is_active = 1
                                AND ISNULL(sales.deleted_at) {$where}
                                GROUP BY
                                    sales.co_id
                                ORDER BY
                                    users.`name` ASC");
        $loan_amount_arr = [];
        foreach($loan_amount as $key => $loan_amounts){
            $loan_amount_arr['co_name'][$key]             = $loan_amounts->name;
            $loan_amount_arr['total_loan_amount'][$key]   = $loan_amounts->total_sale;
            // $loan_amount_arr['admin_fee'][$key]           = $loan_amounts->admin_fee;
            $loan_amount_arr['collect_principle'][$key]   = $loan_amounts->collect_principle;
            $loan_amount_arr['collect_interest'][$key]    = $loan_amounts->collect_interest;
            $loan_amount_arr['collect_saving'][$key]      = $loan_amounts->collect_saving;
            $loan_amount_arr['collect_advance_fine'][$key]= $loan_amounts->collect_advance_fine;
        }

        $collect_prin_and_inter_arr = [];
        $collect_prin_and_inter = DB::select("SELECT
                                                SUM(CASE WHEN transactions.status='principle' THEN transactions.amount_riel END) AS collect_principle,
                                                SUM(CASE WHEN transactions.status='interest' THEN transactions.amount_riel END) AS collect_interest,
                                                SUM(CASE WHEN transactions.status='saving' THEN transactions.amount_riel END) AS collect_saving
                                            FROM transactions
                                                JOIN sales ON sales.id = transactions.sale_id
                                            WHERE
                                                ISNULL(transactions.deleted_at)
                                                AND transactions.date >= '$date_from' AND transactions.date <= '$date_to'
                                            GROUP BY
                                                sales.co_id ");
        foreach($collect_prin_and_inter as $cpi => $collects_prin_and_inter){
            $collect_prin_and_inter_arr['collect_principle'][$cpi] = $collects_prin_and_inter->collect_principle;
            $collect_prin_and_inter_arr['collect_interest'][$cpi] = $collects_prin_and_inter->collect_interest;
            $collect_prin_and_inter_arr['collect_saving'][$cpi] = $collects_prin_and_inter->collect_saving;
        }

        $admin_fee_arr = [];
        $admin_fee = DB::select("SELECT
                                    SUM(transactions.admin_fee) AS admin_fee
                                FROM transactions
                                    JOIN sales ON sales.id = transactions.sale_id
                                    JOIN users ON users.id = sales.co_id
                                WHERE
                                    users.is_active = 1
                                    AND ISNULL(transactions.deleted_at)
                                    AND transactions.date >= '$date_from' AND transactions.date <= '$date_to'
                                GROUP BY
                                    sales.co_id");
        foreach($admin_fee as $af => $admin_fees){
            $admin_fee_arr['admin_fee'][$af] = $admin_fees->admin_fee;
        }

        // $sales =Sale::where(['sales.status'=>1,'sales.approve_status'=>'approved'])->where('customers.active',1)->whereNull('customers.deleted_at')
        // ->join('customers','sales.customer_id','=','customers.id')
        // ->where('sales.date','>=',$date_from)
        // ->where('sales.date','<=',$date_to)
        // // ->groupBy('sales.co_id')
        // ->select('sales.*')
        // ->get();
        // // dd($sales->count());
        // foreach($sales as $q => $sale){
        //     // $kaka = $sale->groupBy('sales.co_id');
        //     // dump($kaka->get());
        //     $active_payments = $sale->payment()->where('payments.status','unpaid')->sum('amount')->groupBy('sales.co_id');
        //     // dump($active_payments);
        // }

        $count_sales_arr = [];
        $count_sales = DB::select("SELECT
                                    COUNT(sales.id) AS count_sales,
                                    SUM(sales.price) AS sum_loan_amount
                                FROM sales
                                    JOIN users ON users.id = sales.co_id
                                    WHERE users.is_active = 1
                                    AND sales.approve_status = 'approved'
                                    AND ISNULL(sales.deleted_at) {$active}
                                GROUP BY
                                    sales.co_id ");
        foreach($count_sales as $cs => $count_sale){
            $count_sales_arr['count_sale'][$cs]         = $count_sale->count_sales;
            $count_sales_arr['sum_loan_amount'][$cs]    = $count_sale->sum_loan_amount;
        }

        $paid_amount_arr = [];
        $paid_amounts = DB::select("SELECT
                                    SUM(CASE WHEN payments.`status`='paid' THEN payments.amount END) AS paid_amount,
	                                SUM(CASE WHEN payments.`status`='partial' THEN payments.t_amount END) AS partial_amount
                                FROM payments JOIN sales ON sales.id = payments.sale_id
                                WHERE
                                    sales.approve_status = 'approved'
                                    AND ISNULL(sales.deleted_at) {$active}
                                GROUP BY
                                    sales.co_id");
        foreach($paid_amounts as $pa => $paid_amount){
            $paid_amount_arr['paid_amount'][$pa]    = $paid_amount->paid_amount;
            $paid_amount_arr['partial_amount'][$pa] = $paid_amount->partial_amount;
        }

        $active_amount = DB::select("SELECT
                                        COUNT(sales.id) AS count_sale_active,
                                        SUM(CASE WHEN payments.status='paid' THEN payments.amount END) AS active_paid_amount
                                        -- SUM(sales.price) AS loan_amount
                                        -- SUM(CASE WHEN payments.status='partial' THEN payments.t_amount END)AS active_partial_amount
                                    FROM payments
                                    JOIN sales ON sales.id = payments.sale_id
                                    JOIN users ON users.id = sales.co_id
                                    WHERE users.is_active = 1
                                    AND sales.approve_status = 'approved'
                                    AND ISNULL(sales.deleted_at) {$where}
                                    GROUP BY
                                        sales.co_id ");
        $active_amount_arr = [];
        foreach($active_amount as $rows => $active_amounts){
            $active_amount_arr['active_paid_amount'][$rows] = $active_amounts->active_paid_amount;
        }

        $financing_fee = DB::select("SELECT
                                        SUM(CASE WHEN payments.status='unpaid' THEN payments.amount END) AS unpaid_amount
                                FROM sales
                                JOIN users ON users.id = sales.co_id
                                JOIN payments ON payments.sale_id = sales.id
                                WHERE
                                    users.is_active = 1
                                AND ISNULL(sales.deleted_at) {$where}
                                GROUP BY
                                    sales.co_id");
        $financing_fee_arr = [];
        foreach($financing_fee as $row => $financing_fees){
            $financing_fee_arr[$row] = $financing_fees->unpaid_amount;
        }

        $actual_date = date('Y-m-d');
        $where_actual_date= "AND payments.payment_date <= '$date_to' ";
        $late_credit_arr = [];
        $late_credit = DB::select("SELECT
                                        COUNT(CASE WHEN payments.status IN ('unpaid','partial') THEN payments.id END) AS num_late_unpaid,
                                        SUM(CASE WHEN payments.status='unpaid' THEN payments.amount END) AS late_unpaid_amount,
                                        COUNT(CASE WHEN DATEDIFF(NOW(),payments.payment_date) >=30 AND payments.status='unpaid' THEN payments.id END) AS num_late_unpaid_more_thirty,
                                        SUM(CASE WHEN DATEDIFF(NOW(),payments.payment_date) >=30 AND payments.status='unpaid' THEN payments.amount END) AS amount_late_unpaid_more_thirty
                                    FROM sales
                                    JOIN users ON users.id = sales.co_id
                                    JOIN payments ON payments.sale_id = sales.id
                                    WHERE
                                        users.is_active = 1
                                    AND ISNULL(sales.deleted_at) {$where_actual_date}
                                    GROUP BY
                                        sales.co_id");
        foreach($late_credit as $r =>$late_credits){
            $late_credit_arr['num_late_unpaid'][$r]                 = $late_credits->num_late_unpaid;
            $late_credit_arr['late_unpaid_amount'][$r]              = $late_credits->late_unpaid_amount;
            $late_credit_arr['num_late_unpaid_more_thirty'][$r]     = $late_credits->num_late_unpaid_more_thirty;
            $late_credit_arr['amount_late_unpaid_more_thirty'][$r]  = $late_credits->amount_late_unpaid_more_thirty;
        }

        $sales = Sale::join('users','users.id','sales.co_id')->where('sales.deleted_at')->groupBy('sales.co_id');
        $sales_id = $sales->pluck('co_id');
        $count_payment_arr = [];
        $count_payments_arr = [];
        foreach($sales_id as $key => $co_id){

            $count_payments = DB::select("SELECT
                                            COUNT(CASE WHEN payments.status='paid' THEN payments.id END) AS count_paid_payment
                                    FROM
                                        payments
                                    JOIN
                                        sales ON sales.id = payments.sale_id
                                    JOIN
                                        users ON users.id = sales.co_id
                                    WHERE
                                        ISNULL(sales.deleted_at)
                                    AND
                                        sales.co_id = {$co_id} {$payment}");
            foreach($count_payments as $k => $value){
                $count_payments_arr[$k][$key] =  $value->count_paid_payment;
            }

            $count_payment  = Payment::join('sales','sales.id','payments.sale_id')
                                ->join('users','users.id','sales.co_id')
                                ->whereNull('sales.deleted_at')
                                ->where('sales.co_id',$co_id)
                                ->whereDate('payments.actual_date','>=',$date_from)
                                ->whereDate('payments.actual_date','<=',$date_to)
                                // ->groupBy('sales.co_id')
                                ->count('sales.id');
            $count_payment_arr[$key]= $count_payment;
        }

        $datas['date_from'] = $date_from;
        $datas['date_to']   = $date_to;
        return view('admin.reports.co_report',compact('request','co_report','loan_amount_arr','count_payment_arr','count_payments_arr','financing_fee_arr','late_credit_arr','active_amount_arr','count_sales_arr','paid_amount_arr','admin_fee_arr','collect_prin_and_inter_arr'))->with($datas);
    }

    public function customer_loan_statement_report(Request $request){
        $loan                   = Sale::where('inv_no',$request->search)->first();
        $payment_transaction    = Sale::where('sales.id',$loan->id??null)->join('payment_transactions','payment_transactions.loan_id','=','sales.id')
        ->join('payments','payments.id','payment_transactions.payment_id')
        ->select('payment_transactions.*','sales.inv_no as loan_no','sales.total as loan_amount','sales.date as loan_date','payments.payment_date');
        if(!empty($request->search)){
            $payment_transaction = $payment_transaction->where('sales.inv_no','like','%'.$request->search.'%');
        }
        $payment_transaction = $payment_transaction->get();
        return view('admin.reports.customer_loan_statement_report',compact('request','payment_transaction','loan'));
    }

    public function pay_off_report(Request $request){
        $date                                   = date('Y-m-d');
        isset($request->date_from)?$date_from   = $request->date_from : $date_from = $date;
        isset($request->date_to)?$date_to       = $request->date_to : $date_to = $date;
        $pay_off                                = new Payoff;
        $customer                               = Customer::get()->pluck('KhmerAndEnglishName','id');
        $pay_off                                = $pay_off->whereBetween('payoffs.date',[$date_from,$date_to]);
        $pay_off                                = $pay_off->join('sales','sales.id','=','payoffs.loan_id')
        ->select('payoffs.*');
        if(!empty($request->customer_id)){
            $pay_off = $pay_off->where('sales.customer_id',$request->customer_id);
        }
        if(!empty($request->loan_no)){
            $pay_off = $pay_off->where('sales.inv_no','like','%'.$request->loan_no.'%');
        }
        $pay_off            = $pay_off->whereNull('payoffs.deleted_at')->get();
        $dates['date_from'] = $date_from;
        $dates['date_to']   = $date_to;
        return view('admin.reports.pay_off_report',compact('request','pay_off','customer'),$dates);
    }

    // public function collateral_report(Request $request){
    //     $date = date('Y-m-d');
    //     isset($request->date_from)?$date_from = $request->date_from : $date_from = (date('Y-m-d', strtotime($date .'-1 month')));
    //     isset($request->date_to)?$date_to = $request->date_to : $date_to = $date;
    //     isset($request->status)?$default_status = $request->status : $default_status = 'using';
    //     $status = ['using' => 'Using','return' => 'Return'];
    //     $customer = Customer::get()->pluck('KhmerAndEnglishName','id');
    //     $collateral = new Collateral;
    //     $collateral = $collateral->where('status',$default_status);//->whereBetween('created_at',[$date_from,$date_to]);
    //     $collateral = $collateral->where('created_at','>=',$date_from,'and','created_at','<=',$date_to);
    //     if(!empty($request->customer_id)){
    //         $collateral = $collateral->where('customer_id',$request->customer_id);
    //     }
    //     if(!empty($request->status)){
    //         $collateral = $collateral->where('status',$request->status);
    //     }
    //     $number_of_collateral = $collateral->count();
    //     $collateral = $collateral->get();
    //     $dates['date_from'] = $date_from;
    //     $dates['date_to'] = $date_to;
    //     return view('admin.reports.collateral_report',compact('request','collateral','customer','status','number_of_collateral'),$dates);
    // }
    public function collateral_report(Request $request){
        $date                                   = date('Y-m-d');
        isset($request->date_from)?$date_from   = $request->date_from : $date_from = (date('Y-m-d', strtotime($date .'-1 month')));
        isset($request->date_to)?$date_to       = $request->date_to : $date_to = $date;
        isset($request->status)?$default_status = $request->status : $default_status = 'using';
        $status                                 = ['using' => 'Using','return' => 'Return'];
        $customer                               = Customer::orderBy('id','DESC')->get()->pluck('CustomerInfo','id');
        $collateral                             = Collateral::join('collateral_details','collateral_details.collateral_id','=','collaterals.id')
        ->leftJoin('customers','customers.id','=','collaterals.customer_id')
        ->leftJoin('users','users.id','=','collateral_details.return_by')
        ->select('collaterals.customer_id as customer_id','users.name as user_name','customers.name as customer_name','collateral_details.*');
        $collateral = $collateral->where('collateral_details.status',$default_status)
        ->whereDate('collateral_details.created_at','>=',$date_from)
        ->whereDate('collateral_details.created_at','<=',$date_to);
        if(!empty($request->customer_id)){
            $collateral = $collateral->where('collaterals.customer_id',$request->customer_id);
        }
        $number_of_collateral   = $collateral->CountByStatus($default_status);
        $collateral             = $collateral->orderBy('collaterals.customer_id','DESC')->get();
        $data                   = [];
        foreach($collateral as $row) {
            $data[$row->customer_id][$row->id]['customer_id']               = $row->customer_id;
            $data[$row->customer_id][$row->id]['customer_name']             = $row->customer_name;
            $data[$row->customer_id][$row->id]['id']                        = $row->id;
            $data[$row->customer_id][$row->id]['collateral_type']           = $row->collateral_type;
            $data[$row->customer_id][$row->id]['collateral_name']           = $row->collateral_name;
            $data[$row->customer_id][$row->id]['color']                     = $row->color;
            $data[$row->customer_id][$row->id]['year_of_mfg']               = $row->year_of_mfg;
            $data[$row->customer_id][$row->id]['engine_no']                 = $row->engine_no;
            $data[$row->customer_id][$row->id]['licence_type']              = $row->licence_type;
            $data[$row->customer_id][$row->id]['frame_no']                  = $row->frame_no;
            $data[$row->customer_id][$row->id]['first_date_registeration']  = $row->first_date_registeration;
            $data[$row->customer_id][$row->id]['status']                    = $row->status;
            $data[$row->customer_id][$row->id]['return_date']               = $row->return_date;
            $data[$row->customer_id][$row->id]['return_by']                 = $row->user_name;
            $data[$row->customer_id][$row->id]['description']               = $row->description;
        }
        $dates['date_from'] = $date_from;
        $dates['date_to']   = $date_to;
        return view('admin.reports.collateral_report',compact('data','request','customer','status','number_of_collateral'),$dates);
    }
}
