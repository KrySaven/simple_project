<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Supplier;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SupplierAdminController extends Controller
{

    use AuthenticatesUsers;
    protected $redirectTo = 'supplier/dashboard';


    public function __construct()
    {
        $this->middleware('guest:supplieradmin')->except('logout');
    }

    public function redirectTo()
    {
        return 'supplier/dashboard';
    }

    protected function guard()
    {
        return \Auth::guard('supplieradmin');
    }

    public function adminLogin()
    {
        return view('auth/supplier_login');
    }

    protected function loggedOut(Request $request)
    {
        return redirect()->route('supplier.login');
    }
}
