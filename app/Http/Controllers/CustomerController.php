<?php

namespace App\Http\Controllers;

use App\Loan;
use App\Sale;
use App\Branch;
use App\Customer;
use App\Province;
use App\Timeline;
use App\CollateralDetail;
use App\Models\Collateral;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Haruncpi\LaravelIdGenerator\IdGenerator;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search     = $request->search;
        $date       = date('Y-m-d');
        $date_from  = $request->date_from;
        $date_to    = $request->date_to;

        $customer   = Customer::where(function ($query) use ($search) {
            $query->orWhere('name', 'like', '%'.$search.'%')
            ->orWhere('phone', 'like', '%'.$search.'%')
            ->orWhere('identity_number', 'like', '%'.$search.'%')
            ->orWhere('email', 'like', '%'.$search.'%');
        });
        if($date_from != '' && $date_to) {
            $customer = $customer->whereDate('created_at', '>=', $date_from)
                                 ->whereDate('created_at', '<=', $date_to);
        }
        $customer = $customer->withBranch()->orderBy('id', 'DESC')->paginate(20)->withPath('customers?date_from='.$date_from.'date_to='.$date_to.'&search='.$search);
        return view('admin.customer.index', compact('search', 'request', 'date_from', 'date_to'))->with('customer', $customer);
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

    public function create()
    {
        $province = $this->get_province();
        $branches = Branch::withPermission()->orderBy('created_at', 'ASC')->get()->pluck('branch_name', 'id');
        return view('admin.customer.create', compact('province', 'branches'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $this->validate($request, [
            'branch_id'         => 'required',
            'name'              => 'required',
            'name_kh'           => 'required',
            'gender'            => 'required',
            'phone'             => 'required',
            'identity_number'   => 'required|unique:customers,identity_number,NULL,id,deleted_at,NULL',
            'date_of_birth'     => 'required',
            'issued_by'         => 'required',
        ], [], [
            'branch_id'         => __('app.branch'),
            'name'              =>'Customer Name (English)',
            'name_kh'           =>'Customer Name (Khmer)',
            'gender'            =>'Sex',
            'phone'             =>'Phone Number',
            'identity_number'   =>'Identity Number/ Passport ID',
            'date_of_birth'     =>'Date of birth',
            'issued_by'         =>'Issued by',
        ]);
        $check_customer = Customer::where(function ($query) use ($request) {
            $query->orWhere('name', $request->name);
            if($request->phone != '') {
                $query = $query->orWhere('phone', $request->phone);
            }
            if($request->email != '') {
                $query = $query->orWhere('email', $request->email);
            }
        })->first();
        if($check_customer) {
            $notification = array(
                'message'       => "Customer already exist!",
                'alert-type'    => 'warning'
            );
            return redirect()->back()->with($notification);
        }
        $fiatured_new_name  = '';
        $photo              =$request->file('profile');
        if($request->hasFile('profile')) {
            $fiatured_new_name  =time().$photo->getClientOriginalName();
            $photo->move('images/customer/', $fiatured_new_name);
            $fiatured_new_name  = 'images/customer/'.$fiatured_new_name;
        }
        $identity_name  = '';
        $identity       = $request->file('identity');
        if($request->hasFile('identity')) {
            $identity_name = time().$identity->getClientOriginalName();
            $identity->move('images/customer/', $identity_name);
            $identity_name = 'images/customer/'.$identity_name;
        }

        $business_name  = '';
        $business = $request->file('business');
        if($request->hasFile('business')) {
            $business_name = time().$business->getClientOriginalName();
            $business->move('images/business/', $business_name);
            $business_name = 'images/business/'.$business_name;
        }

        $user_id                = Auth::user()->id;
        $customer               = new Customer();
        $gender                 = $request->gender;
        $family_status          = $request->family_status;
        $customer_relation      = "";
        $customer_relation_sex  = "";
        if($family_status=="married") {
            if($gender=='male') {
                $customer_relation      = 'wife';
                $customer_relation_sex  = 'female';
            } elseif($gender=='female') {
                $customer_relation      = 'husband';
                $customer_relation_sex  = 'male';
            }
        }
        $cus_no = $this->getCusNo();
        $customer_id = $customer->create([
            'type'                      => $request->type,
            'branch_id'                 => $request->branch_id,
            'name_kh'                   => $request->name_kh,
            'name'                      => $request->name,
            'gender'                    => $request->gender,
            'date_of_birth'             => date('Y-m-d', strtotime($request->date_of_birth)),
            'cus_no'                    => $cus_no,

            'phone'                     => $request->phone,
            'company'                   => $request->company,
            'email'                     => $request->email,
            'identity_number'           => $request->identity_number,
            'identitycard_number_date'  => date('Y-m-d', strtotime($request->identitycard_number_date)),
            'issued_by'                 => $request->issued_by,
            'nationality'               => $request->nationality,
            'family_status'             => $request->family_status,
            'education_level'           => $request->education_level,
            'education_level_other'     => $request->education_level_other,
            'house_no'                  => $request->house_no,
            'street_no'                 => $request->street_no,
            'add_group'                 => $request->add_group,
            'province_id'               => $request->province_id,
            'district_id'               => $request->district_id,
            'commune_id'                => $request->commune_id,
            'village_id'                => $request->village_id,
            // 'lat'                       => $request->lat,
            // 'long'                      => $request->long,

            'personal_ownership'        => $request->personal_ownership,
            'facebook_name'             => $request->facebook_name,
            'facebook_link'             => $request->facebook_link,
            'work_company'              => $request->work_company,
            'work_role'                 => $request->work_role,
            'work_salary'               => isset($request->work_salary)?$request->work_salary:0,
            'work_house_no'             => $request->work_house_no,
            'work_street_no'            => $request->work_street_no,
            'work_group'                => $request->work_group,
            'work_province_id'          => $request->work_province_id,
            'work_district_id'          => $request->work_district_id,
            'work_commune_id'           => $request->work_commune_id,
            'work_village_id'           => $request->work_village_id,

            'business_occupation'       => $request->business_occupation,
            'business_term'             => $request->business_term,
            'business_house_no'         => $request->business_house_no,
            'business_street_no'        => $request->business_street_no,
            'business_group'            => $request->business_group,
            'business_province_id'      => $request->business_province_id,
            'business_district_id'      => $request->business_district_id,
            'business_commune_id'       => $request->business_commune_id,
            'business_village_id'       => $request->business_village_id,

            'address'                   => $request->address,
            'url'                       => $fiatured_new_name,
            'identity'                  => $identity_name,
            'description'               => $request->description,
            'creator_id'                => $user_id,
            // 'business_img'              => $business_name,
            //customer Relation
            'identity_type'             => $request->identity_type,
            'customer_relation'         => $customer_relation,
            'customer_relation_issued_by'           => $request->customer_relation_issued_by,
            'customer_relation_nationality'         => $request->customer_relation_nationality,
            'customer_relation_identity_number'     => $request->customer_relation_identity_number,
            'customer_relation_identity_created_at' => $request->customer_relation_date_of_birth?date('Y-m-d', strtotime($request->customer_relation_identity_created_at)):null,
            'customer_relation_identity_type'       => $request->customer_relation_identity_type,
            'customer_relation_date_of_birth'       => $request->customer_relation_date_of_birth?date('Y-m-d', strtotime($request->customer_relation_date_of_birth)):null,
            'customer_relation_sex'                 => $customer_relation_sex,
            'customer_relation_name_kh'             => $request->customer_relation_name_kh,
            'customer_relation_name_en'             => $request->customer_relation_name_en,
        ]);

        if($request->is_collateral==1) {
            $collateral = Collateral::create([
                'customer_id' => $customer_id->id,
                'created_by'  => Auth::id()
            ]);

            for($i =0 ;$i < count($request->collateral_type);$i++) {
                $collateral_detail = new CollateralDetail;
                $file_name = '';
                if($request->hasFile('file.'.$i)) {
                    if($request->file('file')[$i]->isValid()) {
                        $file                   = $request->file('file')[$i];
                        $extension              = $request->file[$i]->extension();
                        $mainFilename           = Str::random(6).date('h-i-s');
                        $file_name_to_store     = $mainFilename.".".$extension;
                        $file->move('images/collateral_file/', $mainFilename.".".$extension);
                        $file_name              = $file_name_to_store;
                    }
                }
                $collateral_detail->collateral_id               = $collateral->id;
                $collateral_detail->collateral_name             = $request->collateral_name[$i]??null;
                $collateral_detail->color                       = $request->color[$i]??null;
                $collateral_detail->collateral_type             = $request->collateral_type[$i];
                $collateral_detail->licence_type                = $request->licence_type[$i];
                $collateral_detail->year_of_mfg                 = $request->year_of_mfg[$i];
                $collateral_detail->engine_no                   = $request->engine_no[$i];
                $collateral_detail->frame_no                    = $request->frame_no[$i];
                $collateral_detail->first_date_registeration    = $request->first_date_registeration[$i];
                $collateral_detail->file                        = $file_name;
                $collateral_detail->status                      = 'using';
                $collateral_detail->licence_no                  = $request->licence_no[$i];
                $collateral_detail->licence_date                = $request->licence_date[$i];
                $collateral_detail->north                       = $request->north[$i];
                $collateral_detail->south                       = $request->south[$i];
                $collateral_detail->west                        = $request->west[$i];
                $collateral_detail->east                        = $request->east[$i];
                $collateral_detail->save();
            }
        }

        $notification = array(
            'message'       => "Customer Create Success!.",
            'alert-type'    => 'success'
        );
        // if($request->submit=='save'){
        //     return redirect()->route('loan.create_with_customer',$customer_id->id??"")->with($notification);
        // }elseif($request->submit=='save_new'){
        //     return redirect()->route('guarantor.create_with_customer',$customer_id->id??"")->with($notification);
        // }
        return redirect()->back()->with($notification);
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
    public function customer_icloud_report(Request $request)
    {
        $date = date('Y-m-d');
        isset($request->date_from)?$date_from   = $request->date_from : $date_from = (date('Y-m-d', strtotime($date .'-1 year')));
        isset($request->date_to)?$date_to       = $request->date_to : $date_to = $date;
        $customer_id = '';
        $timeline_id = '';
        $identity_number    = $request->identity_number;
        $customers          = Customer::where('active', 1)->orderBy('name', 'ASC')->get();
        $customer           = [];
        foreach ($customers as $customers) {
            $customer[$customers->id] = $customers->name.' -- '.$customers->phone.' -- '.$customers->email;
        }

        $timelines = Timeline::orderBy('id', 'DESC')->get();
        $timeline  = [];
        foreach ($timelines as $timelines) {
            $timeline[$timelines->id] = $timelines->name;
        }
        $sales = Sale::where('customers.active', 1)
            ->where('sales.date', '>=', $date_from)
            ->where('sales.date', '<=', $date_to)
            ->whereNull('customers.deleted_at')
            ->join('customers', 'sales.customer_id', '=', 'customers.id');
        if($identity_number !='') {
            $sales = $sales->where('customers.identity_number', '=', $identity_number);
        }
        // ->whereNull('sales.icloud_username')
        if($request->customer_id != '') {
            $sales       = $sales->where('sales.customer_id', $request->customer_id);
            $customer_id = $request->customer_id;
        }
        if($request->timeline_id != '') {
            $sales       = $sales->where('sales.timeline_id', $request->timeline_id);
            $timeline_id = $request->timeline_id;
        }
        $sales = $sales->select('sales.id AS sale_id', 'sales.icloud_username', 'sales.date AS sale_date', 'sales.product_name', 'sales.serial', 'customers.*')->orderBy('sale_id', 'DESC')->get();
        return view('admin.customer.customer_icloud_report', compact('request', 'timeline', 'customer', 'sales'));
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $customer = Customer::find($id);
        if(!$customer) {
            $notification = array(
                'message'       => "Please Try again!.",
                'alert-type'    => 'error'
            );
            return redirect()->back()->with($notification);
        }
        $province = $this->get_province();
        $branches = Branch::orderBy('created_at', 'ASC')->get()->pluck('branch_name', 'id');
        return view('admin.customer.edit', compact('province', 'branches'))->with('customer', $customer);
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
        $this->validate($request, [
             'branch_id'         => 'required',
             'name'              => 'required',
             'name_kh'           => 'required',
             'gender'            => 'required',
             'phone'             => 'required',
             'identity_number'   => 'required|unique:customers,identity_number,'.$id.',id,deleted_at,NULL',
             'date_of_birth'     => 'required',
             'issued_by'         => 'required',
         ], [], [
             'branch_id'         => __('app.branch'),
             'name'              =>'Customer Name (English)',
             'name_kh'           =>'Customer Name (Khmer)',
             'gender'            => 'Sex',
             'phone'             =>'Phone Number',
             'identity_number'   =>'Identity Number/ Passport ID',
             'date_of_birth'     =>'Date of birth',
             'issued_by'         =>'Issued by',
         ]);
        $user_id            = Auth::user()->id;
        $customer           = Customer::find($id);
        $fiatured_new_name  = '';
        $photo              = $request->file('profile');
        if($request->hasFile('profile')) {
            if($customer->url) {
                if(file_exists((string)$customer->url)) {
                    unlink($customer->url);
                }
            }
            $fiatured_new_name=time().$photo->getClientOriginalName();
            $photo->move('images/customer/', $fiatured_new_name);
            $fiatured_new_name = 'images/customer/'.$fiatured_new_name;
        } else {
            $fiatured_new_name = $customer->url;
        }
        $identity_name  = '';
        $identity       = $request->file('identity');
        if($request->hasFile('identity')) {
            if($customer->identity) {
                if(file_exists((string)$customer->identity)) {
                    unlink($customer->identity);
                }
            }
            $identity_name=time().$identity->getClientOriginalName();
            $identity->move('images/customer/', $identity_name);
            $identity_name = 'images/customer/'.$identity_name;
        } else {
            $identity_name = $customer->identity;
        }
        $gender                 = $request->gender;
        $family_status          = $request->family_status;
        $customer_relation      = "";
        $customer_relation_sex  = "";
        if($family_status=="married") {
            if($gender=='male') {
                $customer_relation      = 'wife';
                $customer_relation_sex  = 'female';
            } elseif($gender=='female') {
                $customer_relation      = 'husband';
                $customer_relation_sex  = 'male';
            }
        }
        $cus_no = $this->getCusNo();
        $customer->update([
            'type'                      => $request->type,
            'branch_id'                 => $request->branch_id,
            'name_kh'                   => $request->name_kh,
            'name'                      => $request->name,
            'gender'                    => $request->gender,
            'date_of_birth'             => $request->date_of_birth,
            // 'cus_no'                    => $cus_no,

            'phone'                     => $request->phone,
            'company'                   => $request->company,
            'email'                     => $request->email,
            'identity_number'           => $request->identity_number,
            'identitycard_number_date'  => $request->identitycard_number_date,
            'issued_by'                 => $request->issued_by,
            'nationality'               => $request->nationality,
            'family_status'             => $request->family_status,
            'education_level'           => $request->education_level,
            'education_level_other'     => $request->education_level_other,
            'house_no'                  => $request->house_no,
            'street_no'                 => $request->street_no,
            'add_group'                 => $request->add_group,
            'province_id'               => $request->province_id,
            'district_id'               => $request->district_id,
            'commune_id'                => $request->commune_id,
            'village_id'                => $request->village_id,
            'lat'                       => $request->lat,
            'long'                      => $request->long,

            'personal_ownership'        => $request->personal_ownership,
            'facebook_name'             => $request->facebook_name,
            'facebook_link'             => $request->facebook_link,
            'work_company'              => $request->work_company,
            'work_role'                 => $request->work_role,
            'work_salary'               => isset($request->work_salary)?$request->work_salary:0,
            'work_house_no'             => $request->work_house_no,
            'work_street_no'            => $request->work_street_no,
            'work_group'                => $request->work_group,
            'work_province_id'          => $request->work_province_id,
            'work_district_id'          => $request->work_district_id,
            'work_commune_id'           => $request->work_commune_id,
            'work_village_id'           => $request->work_village_id,

            'business_occupation'       => $request->business_occupation,
            'business_term'             => $request->business_term,
            'business_house_no'         => $request->business_house_no,
            'business_street_no'        => $request->business_street_no,
            'business_group'            => $request->business_group,
            'business_province_id'      => $request->business_province_id,
            'business_district_id'      => $request->business_district_id,
            'business_commune_id'       => $request->business_commune_id,
            'business_village_id'       => $request->business_village_id,

            'address'                   => $request->address,
            'url'                       => $fiatured_new_name,
            'identity'                  => $identity_name,
            'description'               => $request->description,
            'updater_id'                => $user_id,
            //customer Relation
            'identity_type'             => $request->identity_type,
            'customer_relation'         => $customer_relation,
            'customer_relation_issued_by'           => $request->customer_relation_issued_by,
            'customer_relation_nationality'         => $request->customer_relation_nationality,
            'customer_relation_identity_number'     => $request->customer_relation_identity_number,
            'customer_relation_identity_created_at' => $request->customer_relation_date_of_birth?date('Y-m-d', strtotime($request->customer_relation_identity_created_at)):null,
            'customer_relation_identity_type'       => $request->customer_relation_identity_type,
            'customer_relation_date_of_birth'       => $request->customer_relation_date_of_birth?date('Y-m-d', strtotime($request->customer_relation_date_of_birth)):null,
            'customer_relation_sex'                 => $customer_relation_sex,
            'customer_relation_name_kh'             => $request->customer_relation_name_kh,
            'customer_relation_name_en'             => $request->customer_relation_name_en,
        ]);
        $notification = array(
            'message'       => "Customer Edit Success!.",
            'alert-type'    => 'success'
        );

        // if($request->submit=='save_new'){
        //     return redirect()->route('customers.create')->with($notification);
        // }else{
        //     return redirect()->route('customers')->with($notification);
        // }

        return redirect()->route('customers')->with($notification);
    }


    public function active($id)
    {
        $user_id  = Auth::user()->id;
        $customer = Customer::find($id);
        $customer->update([
            'active'        => 1,
            'updater_id'    => $user_id,
        ]);
        $notification = array(
            'message'       => "Customer Activate Success!.",
            'alert-type'    => 'success'
        );
        return redirect()->route('customers')->with($notification);
    }

    public function deactive($id)
    {
        $user_id  = Auth::user()->id;
        $customer = Customer::find($id);
        $customer->update([
            'active'        => 0,
            'updater_id'    => $user_id,
        ]);
        $notification = array(
            'message'       => "Customer Deactivate Success!.",
            'alert-type'    => 'success'
        );
        return redirect()->route('customers')->with($notification);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user_id  = Auth::user()->id;
        $customer = Customer::find($id);
        $sale     = Sale::where('customer_id', $id)->with('customer')->get()->first();
        if (!empty($sale)) {
            if ($sale->approve_status=='reject') {
                $sale->customer->deleter_id = Auth::id();
                $sale->customer->save();
                $sale->customer->delete();
                $notification = array(
                    'message'       => "Customer Delete Success!",
                    'alert-type'    => 'success'
                );
            } else {
                $notification = array(
                    'message'       => "Customer In Payment Procesing so u can not Delete this Customer!",
                    'alert-type'    => 'warning'
                );
            }
            return redirect()->route('customers')->with($notification);
        }
        $customer->update([
            'deleter_id' => $user_id,
        ]);
        $customer->delete();
        $notification = array(
            'message'       => "Customer Delete Success!",
            'alert-type'    => 'success'
        );
        return redirect()->route('customers')->with($notification);
    }

    public function getCusNo()
    {
        $prefix     = "CUS";
        $loan_no    = "";
        if($prefix!="") {
            $loan_no = IdGenerator::generate([
                'table'     => 'customers',
                'length'    => 7,
                'field'     =>  'cus_no',
                'prefix'    => $prefix]);
        }
        return $loan_no;
    }
}
