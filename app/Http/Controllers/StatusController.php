<?php

namespace App\Http\Controllers;

use App\Helpers\MyHelper;
use App\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StatusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $rows = Status::whereNull('deleted_by')->paginate(20);
        return view('admin.status.index', compact('rows'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {


    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
          'name' => 'required|unique:status,name',
        ]);
        // dd($request->all());
        $slug = $this->makeSlug($request->name);
        Status::create([
            'name'              => $request->name,
            'name_kh'           => $request->name_kh,
            'slug'              => $slug,
            'created_at'        => now(),
            'created_by'        => Auth::user()->id

        ]);

        $notification = array(
            'message'       => "Create Status Success !",
            'alert-type'    => 'success'
        );
        return redirect()->route('status.index')->with($notification);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $row = Status::findOrfail($request->id);

        $data = [
            'name' => $row->name,
            'name_kh' => $row->name_kh,
        ];

        MyHelper::responeDataJSON($data);
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
        // dd($request->id);
        $validate = $request->validate([
          'name' => 'required|unique:status,name,'.$id,
        ]);
        $row = Status::findOrfail($request->id);
        $slug = $this->makeSlug($row->name);
        $row->update([
            'name'              => $request->name,
            'name_kh'           => $request->name_kh,
            'slug'              => $slug,
            'updated_at'        => now(),
            'updated_by'        => Auth::user()->id

        ]);

        $notification = array(
            'message'       => "Update Status Success !",
            'alert-type'    => 'success'
        );
        return redirect()->route('status.index')->with($notification);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $row = Status::findOrFail($id);
        $row->delete();

        $row->update([
            'deleted_by' => Auth::user()->id
        ]);

        $notification = array(
           'message'       => "Delete Status Success !",
           'alert-type'    => 'success'
        );

        return redirect()->route('status.index')->with($notification);

    }

     public function makeSlug($string, $delimiter = '-')
    {
        $string = preg_replace("/[~`{}.'\"\!\@\#\$\%\^\&\*\(\)\_\=\+\/\?\>\<\,\[\]\:\;\|\\\]/", "", $string);
        $string = preg_replace("/[\/_|+ -]+/", $delimiter, $string);
        $slug = strtolower($string);
        return $slug;
    }
}
