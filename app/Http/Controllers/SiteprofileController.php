<?php

namespace App\Http\Controllers;

use App\Siteprofile;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;

class SiteprofileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $siteprofile            = Siteprofile::get()->first();
        $data['siteprofile']    = $siteprofile;
        return view('admin.settings.siteprofile',$data);
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
        $this->validate($request, [
            'site_name'     => 'required',
            'company'       => 'required',
            'phone'         => 'required',
            'email'         => 'required',
            'address'       => 'required',
            

        ],[],[
            'site_name'     => 'Site Name',
            'company'       => 'Company Name',
            'phone'         => 'Phone Number',
            'email'         => 'E-mail',
            'address'       => 'Address',
        ]);
        $user_id    = Auth::user()->id;
        $add_by     = Auth::user()->name;
        $site_name  = $request->site_name;
        $company    = $request->company;
        $phone      = $request->phone;
        $email      = $request->email;
        $address    = $request->address;
        $owner_name = $request->owner_name;
        $map        = $request->map;
        $facebook   = $request->facebook;
        $line       = $request->line;
        $site_name_kh   = $request->site_name_kh;
        $company_kh     = $request->company_kh;
        $owner_name_kh  = $request->owner_name_kh;
        $corent_date    = date('Y-m-d');
        $siteprofile    = Siteprofile::get()->first();
        if($siteprofile){
                $logo_name = '';
                $logos=$request->file('logo');
                  if($request->hasFile('logo')){
                    if($siteprofile->logo){
                        if(file_exists((string)($siteprofile->logo))){   
                            unlink($siteprofile->logo);             
                        } 
                    }
                        $logo_name=time().$logos->getClientOriginalName();
                        $logos->move('images/siteprofile/',$logo_name);
                        $logo_name = 'images/siteprofile/'.$logo_name;
                }else{
                    $logo_name = $siteprofile->logo;
                }
                $icon_name = '';
                $icons=$request->file('icon');
                  if($request->hasFile('icon')){
                    if($siteprofile->icon){
                        if(file_exists((string)($siteprofile->icon))){   
                            unlink($siteprofile->icon);             
                        } 
                    }
                        $icon_name=time().$icons->getClientOriginalName();
                        $icons->move('images/siteprofile/',$icon_name);
                        $icon_name = 'images/siteprofile/'.$icon_name;
                }else{
                    $icon_name = $siteprofile->icon;
                }

            $siteprofile->update([
                    'site_name'   => $site_name,
                    'company'     => $company,
                    'owner_name'  => $owner_name,
                    'phone'       => $phone,
                    'user_id'     => $user_id,
                    'add_by'      => $add_by,
                    'email'       => $email,
                    'address'     => $address,
                    'logo'        => $logo_name,
                    'icon'        => $icon_name,
                    'updater_id'  => $user_id,
                    'facebook'    => $facebook,
                    'map'         => $map,
                    'line'        => $line,
                    'site_name_kh'=> $site_name_kh,
                    'company_kh'  => $company_kh,
                    'owner_name_kh'=>$owner_name_kh,
                ]);
        }else{
            $logo_name = '';
            $logos=$request->file('logo');
              if($request->hasFile('logo')){
                    $logo_name=time().$logos->getClientOriginalName();
                    $logos->move('images/siteprofile/',$logo_name);
                    $logo_name = 'images/siteprofile/'.$logo_name;
                }
            $icon_name = '';
            $icons=$request->file('icon');
              if($request->hasFile('icon')){
                    $icon_name=time().$icons->getClientOriginalName();
                    $icons->move('images/siteprofile/',$icon_name);
                    $icon_name = 'images/siteprofile/'.$icon_name;
                }
            $sale = Siteprofile::create([
                'site_name'     => $site_name,
                'company'       => $company,
                'phone'         => $phone,
                'user_id'       => $user_id,
                'add_by'        => $add_by,
                'email'         => $email,
                'address'       => $address,
                'logo'          => $logo_name,
                'icon'          => $icon_name,
                'site_name_kh'  => $site_name_kh,
                'company_kh'    => $company_kh,
                'owner_name_kh' =>$owner_name_kh,
                'creator_id'    => $user_id,
            ]);
        }

        $notification = array(
            'message'       => "Site Profile Create Success!",
            'alert-type'    => 'success'
        );
        $siteprofile = Siteprofile::get()->first();
        $session = Session()->put('siteprofile',$siteprofile);
        return redirect()->back()->with($notification);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Siteprofile  $siteprofile
     * @return \Illuminate\Http\Response
     */
    public function show(Siteprofile $siteprofile)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Siteprofile  $siteprofile
     * @return \Illuminate\Http\Response
     */
    public function edit(Siteprofile $siteprofile)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Siteprofile  $siteprofile
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Siteprofile $siteprofile)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Siteprofile  $siteprofile
     * @return \Illuminate\Http\Response
     */
    public function destroy(Siteprofile $siteprofile)
    {
        //
    }
}
