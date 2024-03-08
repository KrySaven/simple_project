<?php

namespace App\Http\Controllers;

use App\Province;
use App\District;
use App\Commune;
use App\Http\Controllers\Controller;
use App\Village;
use Illuminate\Http\Request;
use Auth;
use Validator;
use Response;

class VillageController extends Controller
{
    
    public function index()
    {
        $villages = Village::orderBy('vill_id', 'ASC')->paginate(100);
        return view('admin.village.index', compact('villages'));
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
        // $communes = Commune::orderBy('created_at', 'ASC')->get()->pluck('commune_name', 'com_id');
        $provinces = $this->get_province();
        return view('admin.village.create',compact('communes','provinces'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        // $village = Village::create($request->all());
        // if($village){
        //     //created by 
        //     $village->created_by = Auth::id();
        //     $village->save();
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
        
        $validate = [
            'pro_id'            => 'required',
            'district_id'       => 'required',
            'commune_id'        => 'required',
            'village_name'      => 'required',
            'village_namekh'    => 'required',
            'code'              => 'required',
            'modify_date'       => 'required'
        ];
        
        $validator = Validator::make($request->all(),$validate)
        ->setAttributeNames([
            'pro_id'            => 'Province',
            'district_id'       => 'District',
            'commune_id'        => 'Commune',
            'village_name'      => 'Village English Name',
            'village_namekh'    => 'Village Khmer Name',
            'code'              => 'Code',
            'modify_date'       => 'Modify Code'
        ]);
        if($validator->fails()){
            return Response::json(array(
                'success' => false,
                'errors'  => $validator->getMessageBag()->toArray()
            ), 422);
        }
        $village=Village::create([
            'pro_id'            => $request->pro_id,
            'dis_id'            => $request->dis_id,
            'commune_id'        => $request->commune_id,
            'village_name'      => $request->village_name,
            'village_namekh'    => $request->village_namekh,
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

    public function edit(Village $village)
    {
        $provinces = Province::orderBy('created_at', 'ASC')->get()->pluck('province_kh_name', 'province_id');
        $districts = District::orderBy('created_at', 'ASC')->get()->pluck('district_namekh', 'dis_id');
        $communes = Commune::orderBy('created_at', 'ASC')->get()->pluck('commune_name', 'com_id');
        return view('admin.village.edit', compact('provinces','districts','communes', 'village'));
    }

    public function update(Request $request,$id)
    {
        // $villages = $village->update($request->all());
        // if($villages){
        //     //created by 
        //     $village->updated_by = Auth::id();
        //     $village->save();
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
            'commune_id'        => 'required',
            'village_name'      => 'required',
            'village_namekh'    => 'required',
            'code'              => 'required',
            'modify_date'       => 'required'
        ];
        $validator = Validator::make($request->all(),$validate)
        ->setAttributeNames([
            'pro_id'            => 'Province',
            'district_id'       => 'District',
            'commune_id'        => 'Commune',
            'village_name'      => 'Village English Name',
            'village_namekh'    => 'Village Khmer Name',
            'code'              => 'Code',
            'modify_date'       => 'Modify Code'
        ]);
        if($validator->fails()){
            return Response::json(array(
                'success' => false,
                'errors'  => $validator->getMessageBag()->toArray()
            ), 422);
        }

        Village::find($id)->update([
            'pro_id'            => $request->pro_id,
            'dis_id'            => $request->dis_id,
            'com_id'            => $request->com_id,
            'village_name'      => $request->village_name,
            'village_namekh'    => $request->village_namekh,
            'code'              => $request->code,
            'modify_date'       => $request->mofify_date
        ]);
        $notification = array(
            'message'       => "Updated Success !", 
            'alert-type'    => 'success',
            'status'        => true,
        );
        return Response::json($notification);

        // return redirect()->back()->with($notification);
    }

    public function destroy(Village $village)
    {
        $village->deleted_by = Auth::id();
        $village->save();
        $villages = $village->delete();
        if($villages){
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
