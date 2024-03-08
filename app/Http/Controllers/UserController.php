<?php

namespace App\Http\Controllers;

use App\User;
use App\Branch;
use App\User_group;
use App\UserBranch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    public function index(){
        $users = User::withBranch()
                ->has('usergroup')
                ->active()
                ->notDefaultUser()
                ->get();
        return view('admin.users.index',compact(
            'users'
        ));
    }

    
    public function create(){ 
        $user_groups    = User_group::pluck('group_name', 'id');
        $branches       = Branch::withPermission()->orderBy('created_at', 'ASC')->get()->pluck('branch_name', 'id');
        return view('admin.users.create', compact(
            'user_groups', 
            'branches'
        ));
    }

    
    public function store(Request $request){
        $this->validate($request,[
            'group_id'  => 'required',
            'branch_id' => 'required',
            'name'      => 'required|string|max:255',
            'email'     => 'required|string|email|max:255|unique:users',
            'phone'     => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:9',
            'password'  => 'required|string|min:6|confirmed',
        ]);
        $fiatured_new_name  = '';
        $photo              = $request->file('profile');
        if($request->hasFile('profile')){
            $fiatured_new_name=time().$photo->getClientOriginalName();
            $photo->move('images/user/',$fiatured_new_name);
            $fiatured_new_name = 'images/user/'.$fiatured_new_name;
        }
        // dd($request->is_co);
        DB::transaction(function() use($request, $fiatured_new_name){
            $user=User::create([
                'group_id'      => $request->group_id,
                'name'          => $request->name,
                'name_kh'       => $request->name_kh,
                'email'         => $request->email,
                'phone'         => $request->phone,
                'is_co'         => $request->is_co,
                'password'      => Hash::make($request->password),
                'profile'       => $fiatured_new_name,

            ]);
            $branchs = $request->branch_id;
            if(!empty($branchs)){
                foreach($branchs as $branch){
                    UserBranch::create([
                        'user_id'       => $user->id??'',
                        'branch_id'     => $branch,
                        'created_by'    => Auth::id()
                    ]);
                }
            }
        });
        $notification = array(
            'message' => "User Created Success !", 
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }
    public function changepassword_user(Request $request){
        $validator = \Validator::make($request->all(), [
            'password' => 'required|string|min:6',
            ],[],
            [
                'password'=>'Password User',
            ]
        );
        if ($validator->fails())
        {
            return response()->json(['errors'=>$validator->errors()]);
        }
        $user = User::find($request->change_id);
        $user->update([
            'password' => Hash::make($request->password),
        ]);
        return response()->json(['success'=>'Change Password User Success !']);
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
    public function edit($id){   
        $user_groups    = User_group::pluck('group_name', 'id');
        $branches       = Branch::orderBy('created_at', 'ASC')->get()->pluck('branch_name', 'id');
        $user           = User::find($id);
        $user_branches  = $user->UserBranch->pluck('branch_id');
        return view('admin.users.edit', compact(
            'user_groups', 
            'branches', 
            'user',
            'user_branches'
        ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function change($id){
        $User = User::find($id);
        return view('admin.users.changepassword')->with('users', $User);
    }

    public function update(Request $request, $id){
        $this->validate($request,[
            'group_id'  =>'required|integer',
            'branch_id' =>'required',
            'name'      => 'required|string|max:255',
            'email'     => 'required|string|email|max:255|unique:users,email,'.$id.',id',
            'phone'     => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:9',
        ]);
        $user               = User::find($id);
        $fiatured_new_name  = '';
        $photo              = $request->file('profile');
          if($request->hasFile('profile')){
            if($user->profile){
                if(file_exists((string)($user->profile))){   
                    unlink($user->profile);             
                } 
            }
                $fiatured_new_name=time().$photo->getClientOriginalName();
                $photo->move('images/user/',$fiatured_new_name);
                $fiatured_new_name = 'images/user/'.$fiatured_new_name;
        }else{
            $fiatured_new_name = $user->profile;
        }
        DB::transaction(function() use($request, $fiatured_new_name, $user){
            $user->update([
                'group_id'  => $request->group_id,
                'name'      => $request->name,
                'name_kh'   => $request->name_kh,
                'email'     => $request->email,
                'phone'     => $request->phone,
                'is_co'     => $request->is_co,
                'profile'   => $fiatured_new_name,
            ]);
            $branchs = $request->branch_id;
            if(!empty($branchs)){
                $old_user_branch = UserBranch::where(['user_id' => $user->id])->whereNotIn('branch_id', $branchs)->delete();
                foreach($branchs as $branch){
                    $matchThese = [
                        'user_id'       => $user->id??'N/A',
                        'branch_id'     => $branch
                    ];
                    UserBranch::updateOrCreate([
                        'user_id'       => $user->id??'',
                        'branch_id'     => $branch,
                        'created_by'    => Auth::id()
                    ]);
                }
            }
        });
        $notification = array(
            'message'       => "User Edit Success !", 
            'alert-type'    => 'success'
        );
        return redirect()->route('users')->with($notification);
    }
    public function changepassword(Request $request, $id){
        if($request->password != $request->password_confirm ){
            $this->validate($request,[
              'password' => 'required|string|min:6|confirmed',
          ]);  
          }else{
            $this->validate($request,[
                'password' => 'required|string|min:6',
            ]); 
            $user = User::find($id);
            $user->update([
                'password'=>Hash::make($request->password),
    
            ]);
                return redirect()->route('home');
          }
       
        // dd($request->all());
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id){
        $user = User::find($id);
        $user->update(['is_active'=>0]);
        $notification = array(
            'message'       => "User Delete Success !", 
            'alert-type'    => 'success'
        );
        return redirect()->route('users')->with($notification);
    }
}
