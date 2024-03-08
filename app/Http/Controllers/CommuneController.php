<?php

namespace App\Http\Controllers;

use App\Commune;
use App\District;
use App\Http\Controllers\Controller;
use App\Province;
use Illuminate\Http\Request;
use Auth;
use Validator;
use Response;
use DB;

class CommuneController extends Controller
{
    
    public function index()
    {
        $communes = Commune::orderBy('com_id', 'ASC')->paginate(100);
        return view('admin.commune.index', compact('communes'));
    }

    // public function get_district(){
    //     $district  = District::get();
    //     $districts = [];
    //     foreach ($district as $districtss) {
    //         $districts[$districtss->district_id] = $districtss->district_name;
    //     }
    //     return $districts;
    // }

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
        $provinces = $this->get_province();
        // $districts = District::orderBy('created_at', 'ASC')->get()->pluck('district_name', 'dis_id');
        // $districts=$this->get_district();
        return view('admin.commune.create',compact('provinces'));
    }

    public function store(Request $request)
    {
        $validate = [
            'pro_id'            => 'required',
            'district_id'       => 'required',
            'commune_name'      => 'required',
            'commune_namekh'    => 'required',
            'code'              => 'required',
            'modify_date'       => 'required'
        ];
        $validator = Validator::make($request->all(),$validate)
        ->setAttributeNames([
            'pro_id'            => 'Province',
            'district_id'       => 'District',
            'commune_name'      => 'Commune English Name',
            'commune_namekh'    => 'Commune Khmer Name',
            'code'              => 'Code',
            'modify_date'       => 'Modify Code'
        ]);
        if($validator->fails()){
            return Response::json(array(
                'success' => false,
                'errors'  => $validator->getMessageBag()->toArray()
            ), 422);
        }
        $commune=Commune::create([
            'pro_id'            => $request->pro_id,
            'district_id'       => $request->district_id,
            'commune_name'      => $request->commune_name,
            'commune_namekh'    => $request->commune_namekh,
            'code'              => $request->code,
            'modify_date'       => $request->mofify_date
        ]);
        $notification = array(
            'message'       => "Created Success !", 
            'alert-type'    => 'success',
            'status'        =>true,
        );
        return response()->json($notification);
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $commune=Commune::find($id);
        if(!$commune){
            $notification = array(
                'message'       => "Please Try again!.",
                'alert-type'    => 'error',
                'status'        => true,
            );
            return redirect()->back()->with($notification);
        }
        $provinces = Province::orderBy('created_at', 'ASC')->get()->pluck('province_kh_name', 'province_id');
        $districts = District::orderBy('created_at', 'ASC')->get()->pluck('district_namekh', 'dis_id');
      
        // dd($district);
        // $district = $commune->district;
        // dd($district);
        // $districts = $commune->district->district_namekh;
        // $province = $district->province->province_kh_name;
        // dd($district);
        // $province = $this->get_province();
        // dd($province,$districts);
        return view('admin.commune.edit', compact('districts','provinces','commune'));
    }

    public function update(Request $request,$id)
    {
        // dd($request->all());
        // $communes = $commune->update($request->all());
        // if($communes){
        //     //created by 
        //     $commune->updated_by = Auth::id();
        //     $commune->save();
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

        $validate = [
            'pro_id'            => 'required',
            'district_id'       => 'required',
            'commune_name'      => 'required',
            'commune_namekh'    => 'required',
            'code'              => 'required',
            'modify_date'       => 'required'
        ];
        $validator = Validator::make($request->all(),$validate)
        ->setAttributeNames([
            'pro_id'            => 'Province',
            'district_id'       => 'District',
            'commune_name'      => 'Commune English Name',
            'commune_namekh'    => 'Commune Khmer Name',
            'code'              => 'Code',
            'modify_date'       => 'Modify Code'
        ]);
        if($validator->fails()){
            return Response::json(array(
                'success' => false,
                'errors'  => $validator->getMessageBag()->toArray()
            ), 422);
        }
        Commune::find($id)->update([
            'pro_id'            => $request->pro_id,
            'district_id'       => $request->district_id,
            'commune_name'      => $request->commune_name,
            'commune_namekh'    => $request->commune_namekh,
            'code'              => $request->code,
            'modify_date'       => $request->mofify_date
        ]);
        $notification = array(
            'message'       => "Updated Success !", 
            'alert-type'    => 'success',
            'status'        => true,
        );
        return Response::json($notification);
    }

    public function destroy(Commune $commune)
    {
        $commune->deleted_by = Auth::id();
        $commune->save();
        $communes = $commune->delete();
        if($communes){
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
