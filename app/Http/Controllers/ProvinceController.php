<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Province;
use Auth;

class ProvinceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $provinces = Province::orderBy('province_id', 'ASC')->get();
        return view('admin.province.index', compact('provinces'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // return view('admin.province.create', compact('province'));
        return view('admin.province.create');
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
            'province_en_name'  => 'required',
            'province_kh_name'  => 'required',
            'code'              => 'required',
            'modify_date'       => 'required'
        ],[],[
            'province_en_name'  => 'Province English Name',
            'province_kh_name'  => 'Province Khmer Name',
            'code'              => 'Code',
            'modify_date'       => 'Modify Code'
        ]);
        // $provice = Province::create($request->all());
        $user_id = Auth::user()->id;
        $provice = Province::create([
            'code'              => $request->code,
            'province_en_name'  => $request->province_en_name,
            'province_kh_name'  => $request->province_kh_name,
            'modify_date'       => $request->modify_date,
            'user_id'           => $user_id
        ]);
        $provinces_is=$provice->province_id;
        // if($provice){
        //     //created by 
        //     $provice->created_by = Auth::id();
        //     $provice->save();
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
        $notification = array(
            'message'       => "Created Success !", 
            'alert-type'    => 'success'
        );
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

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Province $province)
    {
        $provinces = Province::orderBy('created_at', 'ASC')->get()->pluck('province_en_name', 'province_id');
        return view('admin.province.edit', compact('provinces', 'province'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        // $provices = $province->update($request->all());
        // if($provices){
        //     //created by 
        //     $province->updated_by = Auth::id();
        //     $province->save();
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
            'province_en_name'  => 'required',
            'province_kh_name'  => 'required',
            'code'              => 'required',
            'modify_date'       => 'required'
        ],[],[
            'province_en_name'  => 'Province English Name',
            'province_kh_name'  => 'Province Khmer Name',
            'code'              => 'Code',
            'modify_date'       => 'Modify Code'
        ]);
        $provice=Province::find($id);
        $user_id = Auth::user()->id;
        $provice->update([
            'code'              => $request->code,
            'province_en_name'  => $request->province_en_name,
            'province_kh_name'  => $request->province_kh_name,
            'modify_date'       => $request->modify_date,
            'user_id'           => $user_id,
        ]);
        $notification = array(
            'message'    => "Province Edit Success!.",
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Province $Province)
    {
        $Province->deleted_by = Auth::id();
        $Province->save();
        $provices = $Province->delete();
        if($provices){
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
