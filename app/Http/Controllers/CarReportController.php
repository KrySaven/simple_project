<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Loan;
use App\Customer;
use App\Dealer;
use App\BranchDealer;
use App\Sale;
use App\Timeline;
use App\Payment;
use App\Group_expense;
use App\GroupIncome;
use App\Income;
use DB; 
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Bank;
use App\Siteprofile;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CustomerData;

class CarReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){

        $date = date('Y-m-d');
        isset($request->date_from)?$date_from   = $request->date_from : $date_from = (date('Y-m-d', strtotime($date .'-1 year')));
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
        $sales =Sale::where(['sales.status'=>1, 'sales.type_leasing'=> 'car'])->where('customers.active',1)->whereNull('customers.deleted_at')
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
        $datas['date_from'] = $date_from;
        $datas['date_to']   = $date_to;
        return view('admin.reports.car_report.car_report',compact('request','timeline','customer'),$datas)->with('rows',$sales);
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
