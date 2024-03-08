<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryCotroller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search     = $request->search;
        $category   = Category::where(function ($query) use ($search) {
            $query->orWhere('name', 'like', '%'.$search.'%');
        });
        $category = $category->orderBy('id', 'DESC')->paginate(20);
        return view('admin.category.index', compact('search', 'category'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.category.create');
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
          'name'   => 'required|unique:categories,name,NULL,id,deleted_at,NULL',
        ], [], [
            'name'              =>'Name (English)',
        ]);

        $check_category = Category::where(function ($query) use ($request) {
            $query->orWhere('name', $request->name);
            if($request->name != '') {
                $query = $query->orWhere('name', $request->name);
            }
        })->first();
        if($check_category) {
            $notification = array(
                'message'       => "Category already exist!",
                'alert-type'    => 'warning'
            );
            return redirect()->back()->with($notification);
        }
        Category::create([
            'name' => $request->name,
            'name_kh' => $request->name_kh,
            'description' => $request->description,
            'created_at' => now(),
            'creator_id' => $user_id,
        ]);


        $notification = array(
            'message'       => "Create Category Success !",
            'alert-type'    => 'success'
        );
        return redirect()->route('categories')->with($notification);

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
        $row = Category::find($id);
        return view('admin.category.edit', compact('row'));

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

        $user_id                = Auth::user()->id;
        $row = Category::find($id);
        $this->validate($request, [
          'name'   => 'required|unique:categories,name,'.$id.'NULL,id,deleted_at,NULL',
        ], [], [
            'name'              =>'Name (English)',
        ]);
        $row->update([
            'name' => $request->name,
            'name_kh' => $request->name_kh,
            'description' => $request->description,
            'updated_at' => now(),
            'updater_id' => $user_id,
        ]);


        $notification = array(
            'message'       => "Update Category Success !",
            'alert-type'    => 'success'
        );
        return redirect()->route('categories')->with($notification);

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
        $row = Category::find($id);
        $row->delete();
        $row->update([
            'deleter_id' => $user_id,
        ]);

        $notification = array(
            'message'       => "Delete Category Success !",
            'alert-type'    => 'success'
        );
        return redirect()->route('categories')->with($notification);

    }
}
