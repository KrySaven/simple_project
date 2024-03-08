<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Expense;
use App\Group_expense;
use App\User;
use App\Dealer;
use App\Branch;
use App\Loan;
use App\Journal;
use App\Payment;
use App\Customer;
use App\Siteprofile;
use App\Helpers\MyHelper;
use App\Sale;
use DB;
use Illuminate\Support\Facades\Auth;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(Auth::user()->is_active == 0){
            return view('errors.user_delete');
        }
        $search         = $request->search;
        $siteprofile    = Siteprofile::get()->first();
        $session        = Session()->put('siteprofile',$siteprofile);
        $branch         = count(Branch::all());
        $expense        = Expense::sum('amount');
        $group_expense  = count(Group_expense::all());
        $users          = User::where('is_active',1)->where('id','<>',1)->get()->count();
        $co             = User::where(['is_active' =>1, 'is_co'=>1])->where('id','<>',1)->get()->count();
        $customers      = count(Customer::all());
        $dealers        = count(Dealer::all());
        $count_sale     = count(Sale::where(['type_leasing'=>'loan'])->whereIn('approve_status', ['approved','payoff'])->get());
        $count_car_leasing  = Sale::where(['type_leasing'=>"car"])->get()->count();
        $loans          = Loan::where('status','invoice')->where('dealers.active',1)->whereNull('dealers.deleted_at')
        ->join('dealers','loans.dealer_id','=','dealers.id')->sum('amount');
        $payback = Loan::where('status','payment')->where('dealers.active',1)->whereNull('dealers.deleted_at')
        ->join('dealers','loans.dealer_id','=','dealers.id')->sum('amount');
        $transaction    = Loan::where('status','<>','')->where('dealers.active',1)->whereNull('dealers.deleted_at')
        ->join('dealers','loans.dealer_id','=','dealers.id')->sum('amount');
        $now        = date("Y-m-d");
        $end        = date("Y-m-d", strtotime($now."+1 week"));
        $from_month = date("Y-m-d", strtotime($now."-6 month"));
        $to_month   = date("Y-m-d", strtotime($now."+6 month"));
        $new_from_month = date("Y-m-d", strtotime($now."-10 month"));
        $new_to_month   = date("Y-m-d", strtotime($now."+1 month"));
        $d1 = new \DateTime($from_month);
        $d2 = new \DateTime($to_month);
        $d2 = $d2->modify( '+1 day' ); 
        $intervals  = \DateInterval::createFromDateString('1 day');
        $period     = new \DatePeriod($d1, $intervals, $d2);

        foreach ($period as $dt) {
            $date_month_arr[$dt->format('Y-m')] = $dt->format('Y-m-d');
        }
        // $pay_list = Payment::where('payment_date','>=',$now)->where('payment_date','<=',$end)->where('status','<>','paid')->orderBy('payment_date','ASC')->paginate(10);
        // $pay_list = Payment::where('payment_date','<=',$now)->where('status','<>','paid')
        //     ->whereHas('sale', function ($query) {
        //             $query->where('is_black_list','=','0');
        //         })

        //     ->with(['sale' => function ($q) {
        //     $q->orderBy('customer_id','ASC');
        // }])->orderBy('payments.payment_date','ASC')->paginate(200);

            $pay_list = Payment::where('payments.payment_date','<=',$now)
            // ->where('payments.status','<>','paid')
            ->whereIn('payments.status',['unpaid','partial'])
            ->whereIn('sales.approve_status',['approved'])
            ->where('sales.is_black_list',0)
            ->join('sales','sales.id','=','payments.sale_id')
            ->join('customers','sales.customer_id','=','customers.id')
            ->where(function($query) use ($search){
                $query->orWhere('customers.name', 'like', '%'.$search.'%')
                ->orWhere('customers.name_kh', 'like', '%'.$search.'%')
                ->orWhere('customers.phone', 'like', '%'.$search.'%')
                ->orWhere('customers.identity_number', 'like', '%'.$search.'%')
                ->orWhere('customers.email', 'like', '%'.$search.'%')
                ->orWhere('sales.product_name', 'like', '%'.$search.'%')
                ->orWhere('sales.serial', 'like', '%'.$search.'%');
            })
            ->select('payments.*','sales.customer_id','sales.approve_status')
            ->select('payments.*','sales.customer_id')
            ->orderBy('sales.customer_id','ASC')
            ->orderBy('payments.payment_date','ASC')
            ->paginate(200)->withPath('home?&search='.$search);

        $payment_sexmonth = DB::table("payments")
                    ->select("payments.*" ,DB::raw("(sum(payments.amount)) as sum_ammount,(sum(payments.interest)) as sum_interest,(sum(payments.total)) as sum_total"))
                        ->where('payment_date','>=',$from_month)
                        ->where('payment_date','<=',$to_month)
                        ->orderBy('payment_date')
                        ->groupBy(DB::raw("MONTH(payment_date)","YEAR(payment_date)"))
                        ->get();
        $transactions = DB::table("transactions")
                    ->select("transactions.*" ,DB::raw("(sum(transactions.amount_usd)) as sum_ammount,(sum(transactions.interest_usd)) as sum_interest"))
                        ->where('date','>=',$new_from_month)
                        ->where('date','<=',$new_to_month)
                        ->orderBy('date')
                        ->groupBy(DB::raw("MONTH(date)"))
                        ->get();
        $transactions_arr = [];
        foreach ($date_month_arr as $month) {
            foreach ($transactions as $row) {
                if(date('mY',strtotime($month)) == date('mY',strtotime($row->date))){
                    $transactions_arr[date('mY',strtotime($month))] = [
                                                                                    'sum_ammount'    => $row->sum_ammount,
                                                                                    'sum_interest'    => $row->sum_interest
                                                                                    ];
                }
            }
        }
        // dd($pay_list);   
        $data['date_month_arr']     = $date_month_arr;
        $data['transactions_arr']   = $transactions_arr;
        $data['transactions']       = $transactions;    
        $data['branch']             = $branch;
        $data['users']              = $users;
        $data['co']                 = $co;
        $data['customers']          = $customers;
        $data['dealers']            = $dealers;
        $data['count_sale']         = $count_sale;
        $data['count_car_leasing']  = $count_car_leasing;
        $data['expenses']           = $expense;
        $data['group_expenses']     = $group_expense;
        $data['loans']              = $loans;
        $data['payback']            = $payback;
        $data['transaction']        = $transaction;
        $data['pay_list']           = $pay_list;
        $data['payment_sexmonth']   = $payment_sexmonth;
        $data['request']            = $request;
        return view('home', $data);
    }
}
