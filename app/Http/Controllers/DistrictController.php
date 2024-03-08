<?php

namespace App\Http\Controllers;

use App\District;
use App\Http\Controllers\Controller;
use App\Province;
use Illuminate\Http\Request;
use Auth;

class DistrictController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $districts = District::orderBy('dis_id', 'ASC')->paginate(100);
        return view('admin.district.index', compact('districts'));
    }

    public function get_province(){
        $province  = Province::get();
        $provinces = [];
        foreach ($province as $provin) {
            $provinces[$provin->province_id] = $provin->province_kh_name;
        }
        return $provinces;
    }
    
    public function create()
    {
        // $provinces = Province::orderBy('created_at', 'ASC')->get()->pluck('province_en_name', 'province_id');
        $provinces=$this->get_province();
        return view('admin.district.create', compact('provinces'));
       
    }

    
    public function store(Request $request)
    {
        // $district = District::create($request->all());
        // if($district){
        //     //created by 
        //     $district->created_by = Auth::id();
        //     $district->save();
        //     $notification = array(
        //         'message'       => "Created Success !", 
        //         'alert-type'    => 'success'
        //     );
        // }else{
        //     $notification = array(
        //         'message'       => "Created Fail !", 
        //         'alert-type'    => 'error'
        //     );
        // }

        $this->validate($request, [
            'pro_id'            => 'required',
            'district_name'     => 'required',
            'district_namekh'   => 'required',
            'code'              => 'required',
            'modify_date'       => 'required'
        ],[],[
            'pro_id'            => 'Province',
            'district_name'     => 'District English Name',
            'district_namekh'   => 'District Khmer Name',
            'code'              => 'Code',
            'modify_date'       => 'Modify Code'
        ]);
        $user_id = Auth::user()->id;
        $district = District::create([
            'pro_id'            => $request->pro_id,
            'code'              => $request->code,
            'district_name'     => $request->district_name,
            'district_namekh'   => $request->district_namekh,
            'modify_date'       => $request->modify_date,
            'user_id'           => $request->$user_id
        ]);
        $district_id=$district->dis_id;
        $notification = array(
            'message'       => "Created Success !", 
            'alert-type'    => 'success'
        );
        return redirect()->back()->with($notification);
    }

    
    public function show($id)
    {
        //
    }

    
    public function edit(District $district)
    {
        $provinces = Province::orderBy('created_at', 'ASC')->get()->pluck('province_en_name', 'province_id');
        return view('admin.district.edit', compact('provinces', 'district'));
    }

    
    public function update(Request $request,$id)
    {
        // $districts = $district->update($request->all());
        // if($districts){
        //     //created by 
        //     $district->updated_by = Auth::id();
        //     $district->save();
        //     $notification = array(
        //         'message'       => "Updated Success !", 
        //         'alert-type'    => 'success'
        //     );
        // }else{
        //     $notification = array(
        //         'message'       => "Updated Fail !", 
        //         'alert-type'    => 'error'
        //     );
        // }

        $this->validate($request, [
            'pro_id'            => 'required',
            'district_name'     => 'required',
            'district_namekh'   => 'required',
            'code'              => 'required',
            'modify_date'       => 'required'
        ],[],[
            'pro_id'            => 'Province',
            'district_name'     => 'District English Name',
            'district_namekh'   => 'District Khmer Name',
            'code'              => 'Code',
            'modify_date'       => 'Modify Code'
        ]);

        $district=District::find($id);
        $district->update([
            'pro_id'            => $request->pro_id,
            'code'              => $request->code,
            'district_name'     => $request->district_name,
            'district_namekh'   => $request->district_namekh,
            'modify_date'       => $request->modify_date,
            // 'user_id'           => $request->$user_id
        ]);
        $notification = array(
            'message'       => "Updated Success !", 
            'alert-type'    => 'success'
        );
        return redirect()->back()->with($notification);
    }

    
    public function destroy(District $district)
    {
        $district->deleted_by = Auth::id();
        $district->save();
        $districts = $district->delete();
        if($districts){
            $notification = array(
                'message'       => "Deleted Success !", 
                'alert-type'    => 'success'
            );
        }else{
            $notification = array(
                'message'       => "Deleted Fail !", 
                'alert-type'    => 'error'
            );
        }
        return redirect()->back()->with($notification);
    }
}
