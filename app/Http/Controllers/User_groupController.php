<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User_group;
use Auth;
class User_groupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        $usergroups = User_group::NotDefaultUserGroup()->get();
        $datas['usergroups']= $usergroups;
        return view('admin.usergroup.index',$datas); 
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.usergroup.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'group_name'=>'required|unique:user_groups,group_name,NULL,id,deleted_at,NULL',  
        ]);
        $usergroup = new User_group();
        $usergroup->create([
            'group_name' => $request->group_name,
            'description'=> $request->description,
            'creator_id' => Auth::id()
        ]);
        $notification = array(
            'message'       => "User Group Created Success !", 
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
    public function edit($id)
    {
        $usergroup = User_group::find($id);
        return view('admin.usergroup.edit')->with('usergroup', $usergroup);
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
        $this->validate($request,[
            'group_name'=>'required|unique:user_groups,group_name,'.$id.',id,deleted_at,NULL'
        ]);
        $usergroup = User_group::find($id);
        $usergroup->update([
            'group_name' => $request->group_name,
            'description'=> $request->description,
            'creator_id' => Auth::id()
        ]);
        $notification = array(
            'message'       => "User Group Edit Success !", 
            'alert-type'    => 'success'
        );
        return redirect()->route('usergroups')->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id){
        $usergroup = User_group::find($id);
        $usergroup->delete();
        $notification = array(
            'message'       => "User Group Delete Success !", 
            'alert-type'    => 'success'
        );
        return redirect()->route('usergroups')->with($notification);
    }
}
