<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Province;
use App\District;
use App\Commune;
use App\Village;
use Form;

class AddressController extends Controller
{
    public function get_province(){
        $province = Province::get();
        $provinces = [];
        foreach ($province as $provin) {
            $provinces[$provin->province_id] = $provin->province_kh_name;
        }
        return $provinces;
    }
    function get_districts(Request $request){
    	$province_id    = $request->provice_id;
        $district_id    = $request->district_id;
        $select_class   = $request->select_class;
    	$diss           = District::where('pro_id',$province_id)->get()->pluck('district_namekh','dis_id');
        $data           = Form::select($select_class,$diss, $district_id, ['class'=>'form-control show-tick '.$select_class,'data-live-search'=>'true','placeholder'=>'-- District / Khan --','id'=>$select_class]);

        $res = [

            'district' => (String) $data
        ];
        return response()->json($res);
    }

    function get_communes(Request $request){
        $district_id    = $request->district_id;
    	$commune_id     = $request->commune_id;
        $select_class   = $request->select_class;
    	$comm           = Commune::where('district_id', $district_id)->get()->pluck('commune_namekh','com_id');
    	$data           = Form::select($select_class,$comm, $commune_id, ['class'=>'form-control show-tick '.$select_class,'data-live-search'=>'true','placeholder'=>'-- Commune / Sangkat --','id'=>$select_class]);
    	$res = [

            'commune' => (String) $data
        ];
        return response()->json($res);
    }

    function get_villages(Request $request){
    	$commune_id     = $request->commune_id;
        $village_id     = $request->village_id;
        $select_class   = $request->select_class;
    	$vill           = Village::where('commune_id', $commune_id)->get()->pluck('village_namekh','vill_id');
        $data           = Form::select($select_class,$vill, $village_id, ['class'=>'form-control show-tick '.$select_class,'data-live-search'=>'true','placeholder'=>'-- Village / Borey --','id'=>$select_class]);
    	$res = [

            'village' => (String) $data
        ];
        return response()->json($res);
    }
}
