<?php

namespace App\Http\Controllers;

use App\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Helpers\MyHelper;

class UnitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $units = Unit::whereNull('deleted_at')->paginate('25');
        return view('admin.unit.index', compact('units'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.unit.create');
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
            'name' => 'required|unique:units|max:255',
            'type' => 'required',
        ]);
        // dd($request->all());
        Unit::create([
            'name'              => $request->name,
            'description'       => $request->description,
            'type'              => $request->type,
            'created_at'         => now(),
            'created_by'        => Auth::user()->id

        ]);

        $notification = array(
           'message'       => "Create Unit Success !",
           'alert-type'    => 'success'
        );
        return redirect()->route('unit.index')->with($notification);

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
    public function edit(Unit $unit)
    {
        return view('admin.unit.edit', ['row' => $unit]);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Unit $unit)
    {
        // dd($unit);
        $validate = $request->validate([
            'name'              => 'required|max:255|unique:units,name,'.$unit->id,
            'type'    => 'required',
        ]);
        $unit->update([
            'name'              => $request->name,
            'description'       => $request->description,
            'type'              => $request->type,
            'updated_at'        => now(),
            'updated_by'        => Auth::user()->id
        ]);

        $notification = array(
          'message'       => "Update Unit Success !",
          'alert-type'    => 'success'
        );
        return redirect()->route('unit.index')->with($notification);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Unit $unit)
    {
        // dd($unit->products);
        $unit_products = $unit->products;
        $unit_sizes = $unit->sizes;
        if (sizeof($unit_products) > 0 || sizeof($unit_sizes) > 0) {
            $message = [
                'icon' => 'error',
                'title' => __('app.can_not_delete')
            ];
            return MyHelper::responeDataJSON(null, '', 401, $message);
        } else {
            $unit->deleted_by = Auth::user()->id;
            $unit->save();
            $unit->delete();
            $message = [
                'icon' => 'success',
                'title' => __('app.success')
            ];
            return MyHelper::responeDataJSON(null, '', 200, $message);



        }



    }
}
