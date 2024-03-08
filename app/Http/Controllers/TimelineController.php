<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Timeline;
use App\Timline_detail;
use App\Sale;
class TimelineController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = $request->search;
        $timeline = Timeline::where(function($query) use ($search){
            $query->orWhere('name', 'like', '%'.$search.'%');
        });
        $timeline = $timeline->orderBy('id','DESC')->paginate(20)->withPath('timelines?search='.$search);
        return view('admin.timeline.index',compact('search','request'))->with('timeline',$timeline);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.timeline.create');
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
            'name' => 'required|unique:timelines,name,NULL,id,deleted_at,NULL',
            'duration_type_generate' => 'required',
            'duration_genrate'  => 'required|numeric|min:0|not_in:0',
            'first_payment'  => 'required|numeric|max:100',

        ],[],[
            'name'=>'Timeline Name',
            'duration_type_generate'=>'Payment Type',
            'duration_genrate'=>'Duration',
            'first_payment'=>'First Payment',
        ]);
        $user_id = Auth::user()->id;
        $timeline = Timeline::create([
            'name' => $request->name,
            'payment_type' =>  $request->duration_type_generate,
            'duration' =>  $request->duration_genrate,
            'first_payment' =>  $request->first_payment,
            'description' => $request->description,
            'creator_id' => $user_id,
        ]);
        $timeline_id = $timeline->id;
        $duration_type = $request->duration_type;
        for ($i=0; $i <= count($duration_type)-1; $i++) {
            $timeline_detail = Timline_detail::create([
                'timeline_id' => $timeline_id,
                'duration_type' => $duration_type[$i],
                'percentage' => $request->percentage[$i],
                'duration' => $request->duration[$i],
                'creator_id' => $user_id,
            ]);
        }
        
        $notification = array(
            'message' => "Timeline Create Success!.",
            'alert-type' => 'success'
        );
        return redirect()->route('timelines')->with($notification);
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
    public function get_sample_row(Request $request)
    {
        $timeline_id = $request->timeline_id;

        $this->validate($request, [
            'name' => 'required|unique:timelines,name,'.$timeline_id.',id,deleted_at,NULL',
            'duration_type_generate' => 'required',
            'duration_genrate'  => 'required|numeric|min:0|not_in:0',
            'first_payment'  => 'required|numeric|max:100',
            // 'address' => 'required',

        ],[],[
            'name'=>'Timeline Name',
            'duration_type_generate'=>'Payment Type',
            'duration_genrate'=>'Duration',
            'first_payment'=>'First Payment',
            // 'address'=>'Address',
        ]);

        $duration_type = $request->duration_type_generate;
        $duration = $request->duration_genrate;
        $first_payment = $request->first_payment;

        $generate_timeline = $this->generate_timeline($duration_type,$duration,$first_payment);
        // $data =  view('admin.timeline.sample_tr',compact('duration_type','duration','first_payment','generate_timeline'));
         return response()->json([
            'table' => view('admin.timeline.sample_tr')->with(compact('duration_type','duration','first_payment','generate_timeline'))->render()
        ]);

    }

    public function generate_timeline($payment_type = 'Month',$duration = 0,$first_payment = 0){
        $percentage = 100;
        $data_arr = [];
        $of_seet  = 0;
        if($first_payment > 0){
            $percentage = 100 - $first_payment;
            $percentage = str_replace(',', '', number_format($percentage,2));
            $data_arr[] = [
                        'payment_type'  => $payment_type,
                        'duration'      => 1,
                        'percentage_pay'  => $first_payment,
                        ];
            $duration = $duration -1;
            $of_seet = $duration;
        }else{
            $of_seet = $duration -1;
        }
        $resullt = $percentage / $duration;
        $total_per = 0;
        $add_to_full  = 0;
        $resullt = str_replace(',', '', number_format($resullt,2));
        
        for ($i=0; $i < $duration; $i++) { 
            $data_arr[] = [
                        'payment_type'  => $payment_type,
                        'duration'      => 1,
                        'percentage_pay'  => $resullt,
                        ];
            $total_per += $resullt;
        }
        $total_per = $total_per + $first_payment;
        if($total_per < 100){
            $add_to_full = 100 - $total_per;
            $add_to_full = str_replace(',', '', number_format($add_to_full,2));
            $data_arr[$of_seet]['percentage_pay'] += $add_to_full;
        }
        if($total_per > 100){
            $add_to_full = $total_per - 100;
            $add_to_full = str_replace(',', '', number_format($add_to_full,2));
            $data_arr[$of_seet]['percentage_pay'] -= $add_to_full;
        }
        return $data_arr;
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $timeline = Timeline::find($id);
        return view('admin.timeline.edit',compact('timeline'));

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
        $this->validate($request, [
            'name' => 'required|unique:timelines,name,'.$id.',id,deleted_at,NULL',
            'duration_type_generate' => 'required',
            'duration_genrate'  => 'required|numeric|min:0|not_in:0',
            'first_payment'  => 'required|numeric|max:100',

        ],[],[
            'name'=>'Timeline Name',
            'duration_type_generate'=>'Payment Type',
            'duration_genrate'=>'Duration',
            'first_payment'=>'First Payment',
        ]);
        $timeline = Timeline::find($id);
        $timeline_id = $id;
        $user_id = Auth::user()->id;
        $delete_td = Timline_detail::where('timeline_id',$id)->delete();
        $create_id = $timeline->creator_id;
        $created_at = $timeline->created_at;
        $timeline->update([
            'name' => $request->name,
            'payment_type' =>  $request->duration_type_generate,
            'duration' =>  $request->duration_genrate,
            'first_payment' =>  $request->first_payment,
            'description' => $request->description,
            'updater_id' => $user_id,
        ]);
        $duration_type = $request->duration_type;
        for ($i=0; $i <= count($duration_type)-1; $i++) {
            $timeline_detail = Timline_detail::create([
                'timeline_id' => $timeline_id,
                'duration_type' => $duration_type[$i],
                'percentage' => $request->percentage[$i],
                'duration' => $request->duration[$i],
                'creator_id' => $create_id,
                'created_at' => $created_at,
                'updater_id' => $user_id,
            ]);
        }
        
        $notification = array(
            'message' => "Timeline Update Success!.",
            'alert-type' => 'success'
        );
        return redirect()->route('timelines')->with($notification);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
        $check_is_sale = Sale::where('timeline_id',$id)->count();
        if($check_is_sale > 0 ){ 
            $notification = array(
                'message' => "You can't Delete this Timeline!",
                'alert-type' => 'warning'
            );
            return redirect()->back()->with($notification);
        }
        $user_id = Auth::user()->id;
        $timeline = Timeline::find($id);
        $timeline->update([
            'deleter_id' => $user_id,
        ]);
        $timeline->delete();
        $delete_td = Timline_detail::where('timeline_id',$id)->delete();
        $notification = array(
            'message' => "Timeline Delete Success!",
            'alert-type' => 'success'
        );
        return redirect()->route('timelines')->with($notification);
    }
}
