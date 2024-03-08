<?php

namespace App\Http\Middleware;

use Closure;
use App\Permission;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Session;
use App\Siteprofile;
class GroupPermision
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(Auth::user()->is_active == 0){
            return redirect()->route('home');
        }
        if(!session::get('siteprofile')){ 
            $siteprofile = Siteprofile::get()->first();
            $session = Session()->put('siteprofile',$siteprofile);
        }
        $route = Route::currentRouteName();
        $group = Auth::User()->usergroup;
        // dd($group);
        if($group == 'null' || $group == ''){
            return redirect()->route('home');
        }
        $group_name = $group->group_name??"";
        if($group_name == 'Super Admin' || $group_name == 'SUPERADMIN' || $group_name == 'super admin'){
            return $next($request);
        }
        $permision = Permission::where('name',$route)->where('user_group_id',$group->id)->first();
        if(!$permision){
            $notification = array(
                'message' => "Sorry, You don't have any permision to perform the action. Please contact administrator for help.", 
                'alert-type' => 'warning'
            );
            return redirect()->back()->with($notification);
        }
        return $next($request);
    }



}
