<?php

namespace App\Http\Controllers;

use App\Currency;
use App\Province;
use App\Supplier;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
        $search     = $request->search;
        $date       = date('Y-m-d');
        $date_from  = $request->date_from;
        $date_to    = $request->date_to;
        $supplier   = Supplier::where(function ($query) use ($search) {
            $query->orWhere('name', 'like', '%'.$search.'%')
            ->orWhere('phone', 'like', '%'.$search.'%')
            ->orWhere('email', 'like', '%'.$search.'%');
        });
        $supplier = $supplier->orderBy('id', 'DESC')->paginate(20);
        return view('admin.supplier.index', compact('search', 'request', 'date_from', 'date_to', 'supplier'));
    }

    public function create()
    {
        $province = $this->get_province();
        $currencies = Currency::pluck('name', 'id');
        return view('admin.supplier.create', compact('province', 'currencies'));
    }


    public function get_province()
    {
        $province  = Province::get();
        $provinces = [];
        foreach ($province as $provin) {
            $provinces[$provin->province_id] = $provin->province_kh_name;
        }
        return $provinces;
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name'              => 'required|string|max:255',
            'currency_id'       => 'required',
            'gender'            => 'required',
            'email'             => 'required|string|email|max:255|unique:suppliers,email',
            'phone'             => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:9',
            'password'          => 'required|string|min:6|confirmed',
        ], [], [
            'name'              =>'Suppliers Name (English)',
            'email'             =>'Email',
            'password'          =>'Suppliers password',
            'gender'            =>'Gender',
            'phone'             =>'Phone Number',
        ]);
        // dd($request->all());
        try {
            $check_supplier = Supplier::where(function ($query) use ($request) {
                $query->orWhere('name', $request->name);
                if($request->phone != '') {
                    $query = $query->orWhere('phone', $request->phone);
                }
                if($request->email != '') {
                    $query = $query->orWhere('email', $request->email);
                }
            })->first();
            if($check_supplier) {
                $notification = array(
                    'message'       => "Supplier already exist!",
                    'alert-type'    => 'warning'
                );
                return redirect()->back()->with($notification);
            }

            $fiatured_new_name  = '';
            $photo              =$request->file('profile');
            if($request->hasFile('profile')) {
                $fiatured_new_name  =time().$photo->getClientOriginalName();
                $photo->move('images/supplier/', $fiatured_new_name);
                $fiatured_new_name  = 'images/supplier/'.$fiatured_new_name;
            }

            $identity_name  = '';
            $identity       = $request->file('identity');
            if($request->hasFile('identity')) {
                $identity_name = time().$identity->getClientOriginalName();
                $identity->move('images/identity_supplier/', $identity_name);
                $identity_name = 'images/identity_supplier/'.$identity_name;
            }

            $business_name  = '';
            $business = $request->file('business');
            if($request->hasFile('business')) {
                $business_name = time().$business->getClientOriginalName();
                $business->move('images/business/', $business_name);
                $business_name = 'images/business/'.$business_name;
            }

            $user_id                = Auth::user()->id;
            $supplier               = new Supplier();
            $gender                 = $request->gender;

            $supp_no = $this->getSupplierNo();
            $supplier_id = $supplier->create([
                'supp_no'                   => $supp_no,
                'currency_id'               => $request->currency_id,
                'name'                      => $request->name,
                'name_kh'                   => $request->name_kh,
                'gender'                    => $request->gender,
                'date_of_birth'             => date('Y-m-d', strtotime($request->date_of_birth ?? now())),
                'nationality'               => $request->nationality,
                'email'                     => $request->email,
                'phone'                     => $request->phone,
                'description'               => $request->description,
                'password'                  => Hash::make($request->password),
                'identity'                  => $identity_name,
                'business'                  => $business_name,
                'profile'                    => $fiatured_new_name,
                'creator_id'                => $user_id,
                'created_at'                => now()
            ]);

        } catch (\Throwable $th) {
            throw $th;
        }

        $notification = array(
            'message'       => "Supplier Create Success!.",
            'alert-type'    => 'success'
        );
        return redirect()->route('suppliers')->with($notification);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
           'name'              => 'required|string|max:255',
           'currency_id'       => 'required',
           'gender'            => 'required',
           'email'             => 'required|string|email|max:255|unique:suppliers,email,'.$id,
           'phone'             => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:9',
       ], [], [
           'name'              =>'Name (English)',
           'currency_id'       =>'Currency',
           'password'          =>'Password',
           'gender'            =>'Gender',
           'phone'             =>'Phone Number',
       ]);

        try {
            $user_id            = Auth::user()->id;
            $supplier           = Supplier::find($id);
            $fiatured_new_name   = '';
            $photo              = $request->file('profile');
            if($request->hasFile('profile')) {
                if($supplier->profile) {
                    if(file_exists((string)$supplier->profile)) {
                        unlink($supplier->profile);
                    }
                }

                $fiatured_new_name  =time().$photo->getClientOriginalName();
                $photo->move('images/supplier/', $fiatured_new_name);
                $fiatured_new_name  = 'images/supplier/'.$fiatured_new_name;
            } else {
                $fiatured_new_name = $supplier->profile;

            }

            $identity_name  = '';
            $identity       = $request->file('identity');
            if($request->hasFile('identity')) {
                if($supplier->identity) {
                    if(file_exists((string)$supplier->identity)) {
                        unlink($supplier->identity);
                    }
                }

                $identity_name = time().$identity->getClientOriginalName();
                $identity->move('images/identity_supplier/', $identity_name);
                $identity_name = 'images/identity_supplier/'.$identity_name;
            } else {
                $identity_name = $supplier->identity;
            }

            $business_name  = '';
            $business = $request->file('business');
            if($request->hasFile('business')) {

                if($supplier->business) {
                    if(file_exists((string)$supplier->business)) {
                        unlink($supplier->business);
                    }
                }

                $business_name = time().$business->getClientOriginalName();
                $business->move('images/business/', $business_name);
                $business_name = 'images/business/'.$business_name;
            } else {
                $business_name = $supplier->business;
            }

            $supp_no = $this->getSupplierNo();
            $supplier->update([
                'name_kh'                   => $request->name_kh,
                'currency_id'               => $request->currency_id,
                'name'                      => $request->name,
                'supp_no'                   => $supp_no,
                'gender'                    => $request->gender,
                'date_of_birth'             => date('Y-m-d', strtotime($request->date_of_birth ?? now())),
                'nationality'               => $request->nationality,
                'phone'                     => $request->phone,
                'email'                     => $request->email,
                'description'               => $request->description,
                'identity'                  => $identity_name,
                'business'                  => $business_name,
                'profile'                    => $fiatured_new_name,
                'identity'                  => $identity_name,
                'business'                  => $business_name,
                'profile'                    => $fiatured_new_name,
                'updater_id'                => $user_id,
                'updated_at'                => now()
            ]);

        } catch (\Throwable $th) {
            throw $th;
        }

        $notification = array(
            'message'       => "Supplier Updated Success!.",
            'alert-type'    => 'success'
        );

        return redirect()->route('suppliers')->with($notification);
    }

    public function edit($id)
    {
        $currencies = Currency::pluck('name');
        $supplier = Supplier::find($id);
        if(!$supplier) {
            $notification = array(
                'message'       => "Please Try again!.",
                'alert-type'    => 'error'
            );
            return redirect()->back()->with($notification);
        }
        $province = $this->get_province();
        return view('admin.supplier.edit', compact('province', 'currencies'))->with('supplier', $supplier);
    }


    public function getSupplierNo()
    {
        $prefix     = "SUPP";
        $supp_no    = "";
        if($prefix!="") {
            $supp_no = IdGenerator::generate([
                'table'     => 'suppliers',
                'length'    => 7,
                'field'     =>  'supp_no',
                'prefix'    => $prefix]);
        }
        return $supp_no;
    }

    public function active($id)
    {
        $user_id  = Auth::user()->id;
        $supplier = Supplier::find($id);
        $supplier->update([
            'is_active'        => 1,
            'updater_id'    => $user_id,
        ]);
        $notification = array(
            'message'       => "Supplier Activate Success!.",
            'alert-type'    => 'success'
        );
        return redirect()->route('suppliers')->with($notification);
    }

    public function deactive($id)
    {
        $user_id  = Auth::user()->id;
        $supplier = Supplier::find($id);
        $supplier->update([
            'is_active'        => 0,
            'updater_id'    => $user_id,
        ]);
        $notification = array(
            'message'       => "Supplier Deactivate Success!.",
            'alert-type'    => 'success'
        );
        return redirect()->route('suppliers')->with($notification);
    }

    public function supplier_change($id)
    {
        $supplier = Supplier::find($id);
        return view('admin.supplier.changepassword', compact('supplier'));
    }


    public function changepassword(Request $request)
    {
        $this->validate($request, [
            'password'          => 'min:6|required_with:password_confirm|same:password_confirm',
            'password_confirm'   => 'required|min:6'
        ]);

        $supplier           = Supplier::find($request->id);
        $supplier->update([
            'password' => Hash::make($request->password),
        ]);

        // return response()->json(['success'=>'Change Password Success !']);
        $notification = array(
            'message'       => "Password Chnaged Successfuly !",
            'alert-type'    => 'success'
        );
        return redirect()->route('suppliers')->with($notification);

    }

}
