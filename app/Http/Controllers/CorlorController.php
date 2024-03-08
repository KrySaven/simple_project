<?php

namespace App\Http\Controllers;

use App\Color;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CorlorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search     = $request->search;
        $color   = Color::where(function ($query) use ($search) {
            $query->orWhere('name', 'like', '%'.$search.'%');
        });
        $color = $color->orderBy('id', 'DESC')->paginate(20);
        return view('admin.color.index', compact('search', 'color'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.color.create');
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
          'name'   => 'required|unique:colors,name,NULL,id,deleted_at,NULL',
        ], [], [
            'name'              =>'Name (English)',
        ]);

        $check_color = Color::where(function ($query) use ($request) {
            $query->orWhere('name', $request->name);
            if($request->name != '') {
                $query = $query->orWhere('name', $request->name);
            }
        })->first();
        if($check_color) {
            $notification = array(
                'message'       => "Color already exist!",
                'alert-type'    => 'warning'
            );
            return redirect()->back()->with($notification);
        }
        Color::create([
            'name' => $request->name,
            'name_kh' => $request->name_kh,
            'color_code' => $request->color_code,
            'created_at' => now(),
            'creator_id' => $user_id,
        ]);


        $notification = array(
            'message'       => "Create Color Success !",
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

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $row = Color::find($id);
        return view('admin.color.edit', compact('row'));

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
        $row = Color::find($id);
        $user_id                = Auth::user()->id;
        $this->validate($request, [
            'name'   => 'required|unique:colors,name,'.$id.'NULL,id,deleted_at,NULL',
          ], [], [
              'name'   =>'Name (English)',
          ]);


        $row->update([
            'name' => $request->name,
            'name_kh' => $request->name_kh,
            'color_code' => $request->color_code,
            'updated_at' => now(),
            'updater_id' => $user_id,
        ]);

        $notification = array(
            'message'       => "Updated Color Success !",
            'alert-type'    => 'success'
        );
        return redirect()->route('colors')->with($notification);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user_id                = Auth::user()->id;
        $row = Color::find($id);
        $row->delete();
        $row->update([
            'deleter_id' => $user_id,
        ]);
        $notification = array(
            'message'       => "Delete Color Success !",
            'alert-type'    => 'success'
        );
        return redirect()->back()->with($notification);

    }
}
