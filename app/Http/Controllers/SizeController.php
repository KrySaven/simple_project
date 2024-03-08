<?php

namespace App\Http\Controllers;

use App\Size;
use App\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SizeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search     = $request->search;
        $size = Size::where(function ($query) use ($search) {
            $query->orWhere('name', 'like', '%'.$search.'%');
        });
        $size = $size->orderBy('unit_id', 'ASC')

        ->paginate(20);
        return view('admin.size.index', compact('search', 'size'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $units = Unit::where('type', 'size')->pluck('name', 'id');
        return view('admin.size.create', compact('units'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user_id                = Auth::user()->id;

        $this->validate($request, [
          'name'      => 'required',
          'unit_id'   => 'required',
        ], [], [
            'name'              =>'Name (English)',
            'unit_id'           =>'Select Unit',
        ]);


        size::create([
            'unit_id' => $request->unit_id,
            'name' => $request->name,
            'name_kh' => $request->name_kh,
            'size' => $request->size,
            'description' => $request->description,
            'created_at' => now(),
            'creator_id' => $user_id,
        ]);


        $notification = array(
            'message'       => "Create Color Success !",
            'alert-type'    => 'success'
        );
        return redirect()->route('sizes')->with($notification);

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
    public function edit($id)
    {
        $units = Unit::where('type', 'size')->pluck('name', 'id');
        $row = Size::find($id);
        return view('admin.size.edit', compact('row', 'units'));

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
        $user_id    = Auth::user()->id;
        $row = Size::find($id);

        $this->validate($request, [
          'name'        => 'required',
          'unit_id'     => 'required',
        ], [], [
            'name'              =>'Name (English)',
            'unit_id'           =>'Select Unit',
        ]);
        $row->update([
            'unit_id'   => $request->unit_id,
            'name'      => $request->name,
            'name_kh'   => $request->name_kh,
            'size'      => $request->size,
            'description' => $request->description,
            'created_at' => now(),
            'creator_id' => $user_id,
        ]);


        $notification = array(
            'message'       => "Updated Size Success !",
            'alert-type'    => 'success'
        );
        return redirect()->route('sizes')->with($notification);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user_id    = Auth::user()->id;
        $row = Size::find($id);
        $row->delete();
        $row->update([
            'deleter_id' => $user_id,
        ]);
        $notification = array(
            'message'       => "Delete Color Success !",
            'alert-type'    => 'success'
        );
        return redirect()->route('sizes')->with($notification);

    }
}
