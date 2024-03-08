<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User_group;
use App\Helpers\MyHelper;
use App\User;
use App\permision;
// use App\Project;
use Session;
use Illuminate\Support\Facades\Auth;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function addUser()
    {
        $action = 'add';
        $user_groups = UserGroup::get();
        return view('settings.user_form')->with(compact('action','user_groups'));
    }

    public function showUser()
    {

        $users = User::orderBy('id','desc')->get();
        return view('settings.user_show')->with(compact('users'));
    }

    public function changePassword()
    {
        $action = 'add';
        $user_groups = UserGroup::get();
        return view('settings.change_password')->with(compact('action','user_groups'));
    }

    public function saveChangePassword(Request $request)
    {

        $this->validate($request,[
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user_id = Auth::User()->id;
        $user = User::where('id',$user_id)->first();
        $user->password = bcrypt($request->password);
        $user->save();

        Session::flash('success','Password has been changed.');
        return redirect()->route('home');


    }

    public function saveUser(Request $request)
    {

      $action = $request->action;
       $this->validate($request,[

            'name' => 'required|string|max:255',
            'user_group_id' => 'required',
       ]);

       switch ($action) {
           case 'add':

           $this->validate($request,[
                'password' => 'required|string|min:6|confirmed',
                'email' => 'required|string|email|max:255|unique:users',
           ]);

              User::create([
                'name' => $request->name,
                'email' => $request->email,
                'user_group_id' => $request->user_group_id,
                'password' => bcrypt($request->password),
                'user_group_id' => $request->user_group_id,
            ]);

            
               break;

           case 'edit':
               $user_id = $request->user_id;
               $user = User::where('id',$user_id)->first();
               $user->name = $request->name;
               $user->email = $request->email;
               $user->user_group_id = $request->user_group_id;

               if($request->password != '')
               {
                    $this->validate($request,[
                        'password' => 'required|string|min:6|confirmed',
                    ]);

                    $user->password = bcrypt($request->password);
               }

               $user->save();
               break;
           
           default:
               # code...
               break;
       }


       return redirect()->route('setting.user');

    }

    public function deleteUser($id)
    {
        User::where('id',$id)->delete();
        return redirect()->route('setting.user');
    }


    public function editUser($id)
    {
        $action = 'edit';
        $user = User::where('id',$id)->first();
        $user_groups = UserGroup::get();
        return view('settings.user_form')->with(compact('action','user_groups','user'));
    }


    public function showUserGroup()
    {
        $user_group = user_group::orderBy('id','desc')->get();
        return view('settings.user_group_show')->with(compact('user_group'));
    }


    public function addUserGroup()
    {
        $action = 'add';
        return view('settings.user_group_form')->with(compact('action'));
    }

    public function editUserGroup($id)
    {
        $user_group = UserGroup::where('id',$id)->first();
        $action = 'edit';

        return view('settings.user_group_form')->with(compact('action','user_group'));
    }

    public function saveUserGroup(Request $request)
    {
        $this->validate($request,[
            'name' => 'required'

        ]);

        switch ($request->action) {
            case 'add':
                

            UserGroup::create([
                'name' => $request->name,
                'note' => $request->note
            ]);

                break;

            case 'edit':
                

            $user_group = UserGroup::where('id',$request->id)->first();
            $user_group->name = $request->name;
            $user_group->note = $request->note;
            $user_group->save();
                break;
            
            default:
          
                break;
        }


        return redirect()->route('setting.user_group.show');



    }

    public function deleteUserGroup($id)
    {
        $user_group = UserGroup::where('id',$id)->first();
        $user_group->delete();

        return redirect()->route('setting.user_group.show');
    }



    //--------------user permision

    public function addUserPermision($id='')
    {
        // return ($id);
        $user_group = $id;
        $permision_data = User_group::where('id',$id)->first()->permisions;

        $permisions = array();
        foreach($permision_data as $row)
        {
            $permisions = array_merge($permisions, [$row->name => 'checked']);
        }

        // dd($permisions);
        
        return view('admin.settings.user_permision_form')->with(compact('user_group','permisions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
