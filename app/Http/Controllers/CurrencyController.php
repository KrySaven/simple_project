<?php

namespace App\Http\Controllers;

use App\Currency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CurrencyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search     = $request->search;
        $currency   = currency::where(function ($query) use ($search) {
            $query->orWhere('name', 'like', '%'.$search.'%');
        });
        $currency = $currency->orderBy('id', 'DESC')->paginate(20);
        return view('admin.currency.index', compact('search', 'currency'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.currency.create');

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $user_id                = Auth::user()->id;
        $this->validate($request, [
          'name'   => 'required|unique:currencies,name,NULL,id,deleted_at,NULL',
        ], [], [
            'name'              =>'Name (English)',
        ]);

        $check_currency = Currency::where(function ($query) use ($request) {
            $query->orWhere('name', $request->name);
            if($request->name != '') {
                $query = $query->orWhere('name', $request->name);
            }
        })->first();
        if($check_currency) {
            $notification = array(
                'message'       => "Currency already exist!",
                'alert-type'    => 'warning'
            );
            return redirect()->back()->with($notification);
        }
        Currency::create([
            'name' => $request->name,
            'name_kh' => $request->name_kh,
            'symbol' => $request->symbol,
            'created_at' => now(),
            'creator_id' => $user_id,
        ]);


        $notification = array(
            'message'       => "Create Currency Success !",
            'alert-type'    => 'success'
        );
        return redirect()->route('currencies')->with($notification);

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

        $row = Currency::find($id);
        return view('admin.currency.edit', compact('row'));

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
        $row = Currency::find($id);
        $this->validate($request, [
          'name'   => 'required|unique:currencies,name,'.$id.'NULL,id,deleted_at,NULL',
        ], [], [
            'name'              =>'Name (English)',
        ]);
        $row->update([
            'name' => $request->name,
            'name_kh' => $request->name_kh,
            'symbol' => $request->symbol,
            'updated_at' => now(),
            'updater_id' => $user_id,
        ]);


        $notification = array(
            'message'       => "Update Currency Success !",
            'alert-type'    => 'success'
        );
        return redirect()->route('currencies')->with($notification);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user_id   = Auth::user()->id;
        $row = Currency::find($id);
        $row->delete();
        $row->update([
            'deleter_id' => $user_id,
        ]);

        $notification = array(
            'message'       => "Delete Currency Success !",
            'alert-type'    => 'success'
        );
        return redirect()->route('currencies')->with($notification);

    }
}
