<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Dealer;
use App\Guarantor;
use App\Products;
use App\Sale;
use App\Size;
use App\User;
use Form;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Response;

class GeneralController extends Controller
{
    public function customerByBranch(Request $request)
    {
        $data   = Customer::active()->withBranch()->byBranch($request->branch_id)->get()->pluck('customer_info', 'id');
        $select = Form::select('customer', $data, null, ['class'=>'form-control show-tick selectpicker','data-live-search'=>'true', 'placeholder' => __('app.select_option'), 'id'=> 'customer_id']);
        return $select;
    }
    public function customerByBranchNew(Request $request)
    {
        $data   = Customer::active()->withBranch()->byBranch($request->branch_id)->get()->pluck('customer_info', 'id');
        $select = Form::select('customer_id', $data, null, ['class'=>'form-control show-tick selectpicker','data-live-search'=>'true', 'placeholder' => __('app.select_option'), 'id'=> 'customer_id']);
        return $select;
    }
    public function dealerByBranch(Request $request)
    {
        $data   = Dealer::active()->withBranch()->byBranch($request->branch_id)->get()->pluck('dealer_info', 'id');
        $select = Form::select('dealer', $data, null, ['class'=>'form-control show-tick selectpicker','data-live-search'=>'true', 'placeholder' => __('app.select_option')]);
        return $select;
    }
    public function coByBranch(Request $request)
    {
        $data   = User::active()->withBranch()->CoUser()->byBranch($request->branch_id)->get()->pluck('name_and_email', 'id');
        $select = Form::select('co_user', $data, null, ['class'=>'form-control show-tick selectpicker','data-live-search'=>'true', 'placeholder' => __('app.select_option')]);
        return $select;
    }
    public function guarantorByBranch(Request $request)
    {
        $data   = Guarantor::active()->withBranch()->byBranch($request->branch_id)->get()->pluck('guarantor_info', 'id');
        $select = Form::select('guarantor', $data, null, ['class'=>'form-control show-tick selectpicker','data-live-search'=>'true', 'placeholder' => __('app.select_option')]);
        return $select;
    }
    public function loanByCo(Request $request)
    {
        // $data   = Sale::where('co_id','=',$request->co_user)->get();//->pluck(['inv_no','amount'],'id');
        $data = Sale::with('payment')
                    ->where('co_id', $request->co_user)
                    ->where('sales.approve_status', '=', 'approved')
                    // ->where('payments.status','=','unpaid')
                    ->get();
        // dd($loans);
        // $data   = Payment::LeftJoin('sales','sales.id','=','payments.sale_id')
        //             // ->where('sales.approve_status','=','approved')
        //             ->where('sales.co_id','=',$request->co_user)
        //             ->where('payments.status','=','unpaid')
        //             // ->sum('payments.interest');
        //             ->get();
        // $datas   = Sale::join('payments','payments.sale_id','=','sales.id')
        //             ->where('sales.approve_status','=','approved')
        //             ->where('sales.co_id','=',$request->co_user)
        //             ->where('payments.status','=','unpaid')
        //             // ->sum('payments.interest');
        //             ->get();

        // $select = Form::select('loan', $data, null, ['class'=>'form-control show-tick selectpicker','data-live-search'=>'true', 'placeholder' => __('app.select_option'),'multiple' => 'multiple']);
        // $select = Form::checkbox('loan', $data, false, ['class'=>'filled-in']);
        // dd($data);
        $select = (string) View::make('admin.the_loan.co_checkbox')->with([
            'loan' =>$data,
        ]);
        // $sale   = Sale::where('co_id','=',$request->co_user)->get();
        // dd($data);
        return $select;
    }

    public function filterProduct(Request $request)
    {
        // dd($request->all());
        $product = Products::select('id', 'pro_no', 'name', 'name_kh')
        ->where(function ($query) use ($request) {
            $query->where('pro_no', 'LIKE', '%' . $request->product . '%');
            $query->orWhere('name', 'LIKE', '%' . $request->product . '%');
            $query->orWhere('name_kh', 'LIKE', '%' . $request->product . '%');
            $query->orWhere('code_product', 'LIKE', '%' . $request->product . '%');
        })->where('deleted_at', null)
        ->orderBy('pro_no', 'DESC')->take(50)->get();
        $products = [];

        foreach ($product as $key => $row) {
            $products[$row->id] = $row->pro_no . ' -- ' . $row->name . ' -- ' . $row->name_kh ;
        }
        return response()->json($products, 200);
    }
    public function getProductItem(Request $request)
    {
        $sizes = Size::where('deleted_at', null)->get();
        $productItem = Products::select('id', 'pro_no', 'name', 'name_kh')
        ->where('id', $request->productId)
        ->first();
        // dd($productItem);

        // return view('admin.purchase.view_item', compact('productItem', 'sizes'))->render();
        // return response()->json(['html' => $view]);

    }

}
