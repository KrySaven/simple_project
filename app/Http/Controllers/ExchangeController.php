<?php

namespace App\Http\Controllers;

use App\Currency;
use App\Exchange;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExchangeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search     = $request->search;
        $exchange   = Exchange::where(function ($query) use ($search) {
            $query->orWhere('rate', 'like', '%'.$search.'%');
        });
        $exchange = $exchange->orderBy('id', 'DESC')->paginate(20);
        return view('admin.exchange.index', compact('search', 'exchange'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $currencies = Currency::pluck('name');
        return view('admin.exchange.create', compact('currencies'));
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
          'rate'   => 'required'
        ], [], [
            'rate'              =>'Rate',
        ]);

        Exchange::create([
            'currency_id'   => $request->currency_id,
            'name'          => $request->name,
            'name_kh'          => $request->name_kh,
            'rate'          => $request->rate,
            'created_at'    => now(),
            'creator_id'    => $user_id,
        ]);


        $notification = array(
            'message'       => "Create Exchange Success !",
            'alert-type'    => 'success'
        );
        return redirect()->route('exchanges')->with($notification);

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
        $row = Exchange::find($id);
        $currencies = Currency::pluck('name');

        return view('admin.exchange.edit', compact('row', 'currencies'));
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
        // dd($id, $request->all());
        $user_id                = Auth::user()->id;
        $row = Exchange::find($id);
        $this->validate($request, [
           'rate'                => 'required'
        ], [], [
            'rate'              =>'Rate',
        ]);

        $row->update([
            'currency_id'   => $request->currency_id,
            'name'          => $request->name,
            'name_kh'          => $request->name_kh,
            'rate'          => $request->rate,
            'updated_at'    => now(),
            'updater_id'    => $user_id,
        ]);

        $notification = array(
            'message'       => "Update Exchange Success !",
            'alert-type'    => 'success'
        );
        return redirect()->route('exchanges')->with($notification);
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
        $row = Exchange::find($id);
        $row->delete();
        $row->update([
            'deleter_id' => $user_id,
        ]);

        $notification = array(
            'message'       => "Delete Exchange Success !",
            'alert-type'    => 'success'
        );
        return redirect()->route('exchanges')->with($notification);

    }
}
