<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

use App\Http\Controllers\LoanReportController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\PurchaseReturnController;
use App\Http\Controllers\purchasing\UnitController;
use App\Http\Controllers\StatusController;
use App\Purchase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

// Route::post('web-supplier-login', ['as'=>'web-login','uses'=>'Auth\AuthController@webLoginPost']);

Auth::routes();
Route::get('/locale/{locale}', function ($locale) {
    Session::put('locale', $locale);
    return redirect()->back();
});

Route::get('/supplier-login', ['uses' => 'SupplierAdminController@adminLogin', 'as' => 'supplier.login']);

Route::post('/web-supplier-login', ['uses' => 'SupplierAdminController@login', 'as' => 'supp.loginfn']);

Route::get('/regster', 'HomeController@index')->name('home');
Route::get('/', 'HomeController@index')->name('home');
Route::get('/home', 'HomeController@index')->name('home');
Route::get('/home', 'HomeController@index')->name('list_master');
Route::get('/env', 'HomeController@index')->name('home');
Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function () {
    Route::post('/admin-logout', ['uses' => 'SupplierAdminController@logout', 'as' => 'logOut']);
    // ===========Site Profirle =========
    Route::post('/siteprofile/store', ['uses' => 'SiteprofileController@store', 'as' => 'siteprofile.store']);
    // =========usergroup==============
    Route::post('/usergroup/store', ['uses' => 'User_groupController@store', 'as' => 'usergroup.store']);
    Route::post('/usergroup/update/{id}', ['uses' => 'User_groupController@update', 'as' => 'usergroup.update']);
    // =========user==============
    Route::post('/user/store', ['uses' => 'UserController@store', 'as' => 'user.store']);
    Route::post('/user/update/{id}', ['uses' => 'UserController@update', 'as' => 'user.update']);
    Route::get('/user/change/{id}', ['uses' => 'UserController@change', 'as' => 'user.change']);
    Route::post('/user/changepassword/{id}', ['uses' => 'UserController@changepassword', 'as' => 'user.changepassword']);
    //  =======branch==========
    // Route::post('/branch/store', ['uses' => 'BranchController@store', 'as' => 'branch.store']);
    // Route::post('/branch/update/{branch}', ['uses' => 'BranchController@update', 'as' => 'branch.update']);
    // Route::get('/branch/delete_file', ['uses' => 'BranchController@deleteFile', 'as' => 'branch.delete_file']);

    // ==========expenses============
    // Route::post('/expense/store', ['uses' => 'ExpenseController@store', 'as' => 'expense.store']);
    // Route::post('/expense/update/{id}', ['uses' => 'ExpenseController@update', 'as' => 'expense.update']);


    // ==========expenses============
    // Route::post('/group_expense/store', ['uses' => 'GroupExpenseController@store', 'as' => 'group_expense.store']);
    // Route::post('/group_expense/update/{id}', ['uses' => 'GroupExpenseController@update', 'as' => 'group_expense.update']);

    // ==========Income============
    // Route::post('/income/store', ['uses' => 'IncomeController@store', 'as' => 'income.store']);
    // Route::post('/income/update/{id}', ['uses' => 'IncomeController@update', 'as' => 'income.update']);
    // ==========Investment============
    // Route::post('/investment/store', ['uses' => 'InvestmentController@store', 'as' => 'investment.store']);
    // Route::post('/investment/update/{id}', ['uses' => 'InvestmentController@update', 'as' => 'investment.update']);

    // ==========Group Income============
    // Route::post('/group_income/store', ['uses' => 'GroupIncomeController@store', 'as' => 'group_income.store']);
    // Route::post('/group_income/update/{id}', ['uses' => 'GroupIncomeController@update', 'as' => 'group_income.update']);

    //  =======Customer==========
    Route::post('/customer/store', ['uses' => 'CustomerController@store', 'as' => 'customer.store']);
    Route::post('/customer/update/{id}', ['uses' => 'CustomerController@update', 'as' => 'customer.update']);
    //  =======Guarantor==========
    // Route::post('/guarantor/store', ['uses' => 'GuarantorController@store', 'as' => 'guarantor.store']);
    // Route::post('/guarantor/update/{id}', ['uses' => 'GuarantorController@update', 'as' => 'guarantor.update']);
    //  =======Salesman==========
    // Route::post('/salesman/store', ['uses' => 'SalesmanController@store', 'as' => 'salesman.store']);
    // Route::post('/salesman/update/{id}', ['uses' => 'SalesmanController@update', 'as' => 'salesman.update']);
    //  =======Dealer==========
    // Route::post('/dealer/store', ['uses' => 'DealerController@store', 'as' => 'dealer.store']);
    // Route::post('/dealer/update/{id}', ['uses' => 'DealerController@update', 'as' => 'dealer.update']);
    // ============// ========Timeline ==============
    // Route::post('/timeline/store', ['uses' => 'TimelineController@store', 'as' => 'timeline.store']);
    // Route::post('/timeline/update/{id}', ['uses' => 'TimelineController@update', 'as' => 'timeline.update']);
    // Route::get('/get_sample_row', ['uses' => 'TimelineController@get_sample_row', 'as' => 'get_sample_row']);
    // ========= Sale ============
    // Route::post('/sale/store', ['uses' => 'SaleController@store', 'as' => 'sale.store']);
    // Route::post('/sale/update/{id}', ['uses' => 'SaleController@update', 'as' => 'sale.update']);
    // Route::post('/sale/confirm_delete/{id}', ['uses' => 'SaleController@confirm_delete', 'as' => 'sale.confirm_delete']);
    // Route::get('/get_timeline', ['uses' => 'SaleController@get_timeline', 'as' => 'get_timeline']);
    // Route::get('/sale_detail', ['uses' => 'SaleController@sale_detail', 'as' => 'sale_detail']);
    Route::get('/sale/print_payment', ['uses' => 'SaleController@print_payment', 'as' => 'sale.print_payment']);
    // Route::get('/sale/view_payment', ['uses' => 'SaleController@view_payment', 'as' => 'sale.view_payment']);
    // Route::get('/sale/view_follow_up', ['uses' => 'SaleController@view_follow_up', 'as' => 'sale.view_follow_up']);
    // Route::get('/sale/cancel_payment', ['uses' => 'SaleController@cancel_payment', 'as' => 'sale.cancel_payment']);
    // Route::get('/sale/get_confirm_delete/{id}', ['uses' => 'SaleController@get_confirm_delete', 'as' => 'sale.get_confirm_delete']);
    // Route::post('/sale/save_complete_payment/{id}', ['uses' => 'SaleController@save_complete_payment', 'as' => 'sale.save_complete_payment']);
    // Route::post('/sale/save_paid_off_payment/{id}', ['uses' => 'SaleController@save_paid_off_payment', 'as' => 'sale.save_paid_off_payment']);
    // Route::get('/sale/search_customer', ['uses' => 'SaleController@search_customer', 'as' => 'sale.search_customer']);
    // Route::post('/sale/save_customer_icloud', ['uses' => 'SaleController@save_customer_icloud', 'as' => 'sale.save_customer_icloud']);
    //===============Car Leasing=============

    // Route::get('/car-leasing/list', ['uses' => 'CarLeasingController@index', 'as' => 'carleasing.index']);
    // Route::get('/car-leasing/create', ['uses' => 'CarLeasingController@create', 'as' => 'carleasing.create']);
    // Route::post('/car-leasing/store', ['uses' => 'CarLeasingController@store', 'as' => 'carleasing.store']);
    // Route::get('/car-leasing/edit/{id}', ['uses' => 'CarLeasingController@edit', 'as' => 'carleasing.edit']);
    // Route::post('/car-leasing/update/{id}', ['uses' => 'CarLeasingController@update', 'as' => 'carleasing.update']);
    // Route::get('/car-leasing/destroy/{id}', ['uses' => 'CarLeasingController@destroy', 'as' => 'carleasing.destroy']);
    // Route::get('/car-leasing/contract/{id}', ['uses' => 'CarLeasingController@contract', 'as' => 'carleasing.contract']);
    // Route::get('/car_leasing/schedules', ['uses' => 'CarLeasingController@get_payment_schedule', 'as' => 'get_payment_schedule']);
    // Route::get('/car_leasing/payment/{id}', ['uses' => 'CarLeasingController@payment', 'as' => 'carleasing.payment']);
    // Route::get('/carleasing_detail', ['uses' => 'CarLeasingController@carleasing_detail', 'as' => 'carleasing_detail']);
    //  =======branchdealer==========
    // Route::post('/branchdealer/store', ['uses' => 'BranchdealerController@store', 'as' => 'branchdealer.store']);
    // Route::post('/branchdealer/update/{id}', ['uses' => 'BranchdealerController@update', 'as' => 'branchdealer.update']);
    //  =======Loan==========
    // Route::post('/invoice/store', ['uses' => 'LoanController@store', 'as' => 'loan.store']);
    // Route::post('/invoice/update/{id}', ['uses' => 'TheLoanController@update', 'as' => 'loan.update']);
    //  =======Repay Loan==========
    // Route::post('/payment/store_repay', ['uses' => 'LoanController@store_repay', 'as' => 'loan.store_repay']);
    // Route::post('/payment/update_repay/{id}', ['uses' => 'LoanController@update_repay', 'as' => 'loan.update_repay']);

    Route::get('/get_branchdealer', ['uses' => 'LoanController@get_branchdealer', 'as' => 'get_branchdealer']);


    // ======= JournalController  Credit============
    // Route::post('/credit/store', ['uses' => 'JournalController@store', 'as' => 'credit.store']);
    // Route::post('/credit/update/{id}', ['uses' => 'JournalController@update', 'as' => 'credit.update']);
    // ======= JournalController Debit ============
    // Route::post('/debit/store', ['uses' => 'JournalController@store_debit', 'as' => 'debit.store']);
    // Route::post('/debit/update/{id}', ['uses' => 'JournalController@update_debit', 'as' => 'debit.update']);
    // ======= JournalController Debit ============
    // Route::post('/statement/store', ['uses' => 'JournalController@store_statement', 'as' => 'statement.store']);
    // Route::post('/statement/update/{id}', ['uses' => 'JournalController@update_statement', 'as' => 'statement.update']);
    Route::get('/get_districts', ['uses' => 'AddressController@get_districts', 'as' => 'get_districts']);
    Route::get('/get_communes', ['uses' => 'AddressController@get_communes', 'as' => 'get_communes']);
    Route::get('/get_villages', ['uses' => 'AddressController@get_villages', 'as' => 'get_villages']);
    // Loan
    // Route::group(['prefix' => 'loan', 'as' => 'loan.'], function () {
    //     Route::post('store', ['as' => 'store', 'uses' => 'TheLoanController@store']);
    //     Route::post('update/{loan}', ['as' => 'update', 'uses' => 'TheLoanController@update']);
    //     Route::get('get-collateral', ['as' => 'get_collateral','uses' => 'CollateralController@get_collateral']);

    //     Route::get('preview-schedule', ['as' => 'preview_schedule', 'uses' => 'TheLoanController@preview_schedule']);
    // });
    // Collateral
    // Route::post('collateral-store', ['uses' => 'CollateralController@store','as' => 'collateral.store']);
    // Route::post('collateral-update/{id}', ['uses' => 'CollateralController@update','as' => 'collateral.update']);
    // Route::get('preview-collateral', ['uses' => 'TheLoanController@preview_collateral','as' => 'preview_collateral']);
    // Route::post('collateral-return/{id}', ['uses' => 'CollateralController@store_return_collateral','as' => 'store_return_collateral']);
    // Route::get('remove-collateral-details/{id}', ['uses' => 'CollateralController@remove_collateral_detail','as' => 'remove_collateral_detail']);
    // Preview Contract
    // Route::get('preview-contract', ['uses' => 'TheLoanController@preview_contract','as' => 'preview_contract']);
    // Print Receipt
    Route::get('print-receipt/{id}', ['uses' => 'PaymentController@print_receipt','as' => 'print_receipt']);
    // Payment Store
    // Route::group(['prefix' => 'loan', 'as' => 'loan.'], function(){
    // Route::group(['prefix' => 'loan/payment', 'as' => 'loan.payment.'], function () {
    //     Route::post('{payment}/store', ['as' => 'store', 'uses' => 'PaymentController@store']);
    //     Route::post('{payment_transaction}/reversepayment', ['as' => 'reverse_payment_store','uses' => 'PaymentController@reverse_payment']);
    // });

    Route::middleware(['group_permision'])->group(function () {
        //  ===============Site Profirle ==============
        Route::get('/siteprofile', ['uses' => 'SiteprofileController@index', 'as' => 'siteprofiles']);
        //============ User Group ===========
        Route::get('/usergroup', ['uses' => 'User_groupController@index', 'as' => 'usergroups']);
        Route::get('/usergroup/create', ['uses' => 'User_groupController@create', 'as' => 'usergroup.create']);
        Route::get('/usergroup/edit/{id}', ['uses' => 'User_groupController@edit', 'as' => 'usergroup.edit']);
        Route::get('/usergroup/destroy/{id}', ['uses' => 'User_groupController@destroy', 'as' => 'usergroup.destroy']);
        // =========user==============
        Route::get('/user', ['uses' => 'UserController@index', 'as' => 'users']);
        Route::get('/user/create', ['uses' => 'UserController@create', 'as' => 'user.create']);
        Route::get('/user/edit/{id}', ['uses' => 'UserController@edit', 'as' => 'user.edit']);
        Route::get('/user/destroy/{id}', ['uses' => 'UserController@destroy', 'as' => 'user.destroy']);
        Route::post('/user/changepassword_user', ['uses' => 'UserController@changepassword_user', 'as' => 'user.changepassword_user']);
        //  =======branch==========
        Route::get('/branch', ['uses' => 'BranchController@index', 'as' => 'branchs']);
        Route::get('/branch/create', ['uses' => 'BranchController@create', 'as' => 'branch.create']);
        Route::get('/branch/edit/{branch}', ['uses' => 'BranchController@edit', 'as' => 'branch.edit']);
        Route::get('/branch/destroy/{branch}', ['uses' => 'BranchController@destroy', 'as' => 'branch.destroy']);

        //  =======expenses==========
        Route::get('/expense', ['uses' => 'ExpenseController@index', 'as' => 'expenses']);
        Route::get('/expense/create', ['uses' => 'ExpenseController@create', 'as' => 'expense.create']);
        Route::get('/expense/edit/{id}', ['uses' => 'ExpenseController@edit', 'as' => 'expense.edit']);
        Route::get('/expense/destroy/{id}', ['uses' => 'ExpenseController@destroy', 'as' => 'expense.destroy']);

        Route::get('/expense/expense_report', ['uses'=>'ExpenseController@report_expense','as'=>'expense_report']);

        //  =======Income==========
        Route::get('/income', ['uses' => 'IncomeController@index', 'as' => 'incomes']);
        Route::get('/income/create', ['uses' => 'IncomeController@create', 'as' => 'income.create']);
        Route::get('/income/edit/{id}', ['uses' => 'IncomeController@edit', 'as' => 'income.edit']);
        Route::get('/income/destroy/{id}', ['uses' => 'IncomeController@destroy', 'as' => 'income.destroy']);
        Route::get('/income/income_report', ['uses'=>'IncomeController@report_income','as'=>'income_report']);
        //  =======group expenses==========
        Route::get('/group_expense', ['uses' => 'GroupExpenseController@index', 'as' => 'group_expense']);
        Route::get('/group_expense/create', ['uses' => 'GroupExpenseController@create', 'as' => 'group_expense.create']);
        Route::get('/group_expense/edit/{id}', ['uses' => 'GroupExpenseController@edit', 'as' => 'group_expense.edit']);
        Route::get('/group_expense/destroy/{id}', ['uses' => 'GroupExpenseController@destroy', 'as' => 'group_expense.destroy']);
        //  =======group expenses==========
        Route::get('/group_income', ['uses' => 'GroupIncomeController@index', 'as' => 'group_incomes']);
        Route::get('/group_income/create', ['uses' => 'GroupIncomeController@create', 'as' => 'group_income.create']);
        Route::get('/group_income/edit/{id}', ['uses' => 'GroupIncomeController@edit', 'as' => 'group_income.edit']);
        Route::get('/group_income/destroy/{id}', ['uses' => 'GroupIncomeController@destroy', 'as' => 'group_income.destroy']);
        //  =======Investments==========
        Route::get('/investment', ['uses' => 'InvestmentController@index', 'as' => 'investments']);
        Route::get('/investment/create', ['uses' => 'InvestmentController@create', 'as' => 'investment.create']);
        Route::get('/investment/edit/{id}', ['uses' => 'InvestmentController@edit', 'as' => 'investment.edit']);
        Route::get('/investment/destroy/{id}', ['uses' => 'InvestmentController@destroy', 'as' => 'investment.destroy']);

        //  =======Customer==========
        Route::get('/customers', ['uses' => 'CustomerController@index', 'as' => 'customers']);
        Route::get('/customer/create', ['uses' => 'CustomerController@create', 'as' => 'customer.create']);
        Route::get('/customer/edit/{id}', ['uses' => 'CustomerController@edit', 'as' => 'customer.edit']);
        Route::get('/customer/destroy/{id}', ['uses' => 'CustomerController@destroy', 'as' => 'customer.destroy']);
        Route::get('/customer/active/{id}', ['uses' => 'CustomerController@active', 'as' => 'customer.active']);
        Route::get('/customer/deactive/{id}', ['uses' => 'CustomerController@deactive', 'as' => 'customer.deactive']);
        Route::get('/customer/customer_icloud_report', ['uses' => 'CustomerController@customer_icloud_report', 'as' => 'customer.customer_icloud_report']);
        //  =======Customer==========
        //  =======Suppliers==========
        Route::get('/suppliers', ['uses' => 'SupplierController@index', 'as' => 'suppliers']);
        Route::get('/supplier/create', ['uses' => 'SupplierController@create', 'as' => 'supplier.create']);
        Route::get('/supplier/edit/{id}', ['uses' => 'SupplierController@edit', 'as' => 'supplier.edit']);
        Route::get('/supplier/destroy/{id}', ['uses' => 'SupplierController@destroy', 'as' => 'supplier.destroy']);
        Route::get('/supplier/active/{id}', ['uses' => 'SupplierController@active', 'as' => 'supplier.active']);
        Route::get('/supplier/deactive/{id}', ['uses' => 'SupplierController@deactive', 'as' => 'supplier.deactive']);
        Route::post('/supplier/store', ['uses' => 'SupplierController@store', 'as' => 'supplier.store']);
        Route::post('/supplier/update/{id}', ['uses' => 'SupplierController@update', 'as' => 'supplier.update']);
        Route::get('/supplier/supplier-change/{id}', ['uses' => 'SupplierController@supplier_change', 'as' => 'supplier.supplier-change']);
        Route::post('/supplier/changepassword', ['uses' => 'SupplierController@changepassword', 'as' => 'supplier.changepassword']);

        //  =======Suppliers==========
        Route::get('/exchanges', ['uses' => 'ExchangeController@index', 'as' => 'exchanges']);
        Route::get('/exchange/create', ['uses' => 'ExchangeController@create', 'as' => 'exchange.create']);
        Route::get('/exchange/edit/{id}', ['uses' => 'ExchangeController@edit', 'as' => 'exchange.edit']);
        Route::get('/exchange/destroy/{id}', ['uses' => 'ExchangeController@destroy', 'as' => 'exchange.destroy']);
        Route::post('/exchange/store', ['uses' => 'ExchangeController@store', 'as' => 'exchange.store']);
        Route::post('/exchange/update/{id}', ['uses' => 'ExchangeController@update', 'as' => 'exchange.update']);
        //  =======Color==========
        //  =======Category==========
        Route::get('/categories', ['uses' => 'CategoryCotroller@index', 'as' => 'categories']);
        Route::get('/category/create', ['uses' => 'CategoryCotroller@create', 'as' => 'category.create']);
        Route::get('/category/edit/{id}', ['uses' => 'CategoryCotroller@edit', 'as' => 'category.edit']);
        Route::get('/category/destroy/{id}', ['uses' => 'CategoryCotroller@destroy', 'as' => 'category.destroy']);
        Route::post('/category/store', ['uses' => 'CategoryCotroller@store', 'as' => 'category.store']);
        Route::post('/category/update/{id}', ['uses' => 'CategoryCotroller@update', 'as' => 'category.update']);
        //  =======Purchase Return ==========
        Route::get('/purchase-returns', ['uses' => 'PurchaseReturnController@index', 'as' => 'purchase_returns']);
        Route::get('/purchase-return/create', ['uses' => 'PurchaseReturnController@create', 'as' => 'purchase_return.create']);
        Route::get('/purchase-return/edit/{id}', ['uses' => 'PurchaseReturnController@edit', 'as' => 'purchase_return.edit']);
        Route::get('/purchase-return/destroy/{id}', ['uses' => 'PurchaseReturnController@destroy', 'as' => 'purchase_return.destroy']);
        Route::post('/purchase-return/store', ['uses' => 'PurchaseReturnController@store', 'as' => 'purchase_return.store']);
        Route::post('/purchase-return/update/{id}', ['uses' => 'PurchaseReturnController@update', 'as' => 'purchase_return.update']);
        Route::get('/purchase-return-verify', ['uses' => 'PurchaseReturnController@verify', 'as' => 'purchase_return.verify']);
        Route::get('/purchase-return-destroy', ['uses' => 'PurchaseReturnController@destroy', 'as' => 'purchase_return.destroy']);
        Route::get('/purchase-return-detail/{id}', ['uses' => 'PurchaseReturnController@detail', 'as' => 'purchase_return.detail']);


        //  =======Unit==========

        Route::resource('unit', 'UnitController')->except(['show']);

        //  =======Currency==========
        Route::get('/currencies', ['uses' => 'CurrencyController@index', 'as' => 'currencies']);
        Route::get('/currency/create', ['uses' => 'CurrencyController@create', 'as' => 'currency.create']);
        Route::get('/currency/edit/{id}', ['uses' => 'CurrencyController@edit', 'as' => 'currency.edit']);
        Route::get('/currency/destroy/{id}', ['uses' => 'CurrencyController@destroy', 'as' => 'currency.destroy']);
        Route::post('/currency/store', ['uses' => 'CurrencyController@store', 'as' => 'currency.store']);
        Route::post('/currency/update/{id}', ['uses' => 'CurrencyController@update', 'as' => 'currency.update']);
        //  =======Currency==========
        //  =======Color==========
        Route::get('/colors', ['uses' => 'CorlorController@index', 'as' => 'colors']);
        Route::get('/color/create', ['uses' => 'CorlorController@create', 'as' => 'color.create']);
        Route::get('/color/edit/{id}', ['uses' => 'CorlorController@edit', 'as' => 'color.edit']);
        Route::get('/color/destroy/{id}', ['uses' => 'CorlorController@destroy', 'as' => 'color.destroy']);
        Route::post('/color/store', ['uses' => 'CorlorController@store', 'as' => 'color.store']);
        Route::post('/color/update/{id}', ['uses' => 'CorlorController@update', 'as' => 'color.update']);
        //  =======Color==========
        //  =======Color==========
        Route::get('/sizes', ['uses' => 'SizeController@index', 'as' => 'sizes']);
        Route::get('/size/create', ['uses' => 'SizeController@create', 'as' => 'size.create']);
        Route::get('/size/edit/{id}', ['uses' => 'SizeController@edit', 'as' => 'size.edit']);
        Route::get('/size/destroy/{id}', ['uses' => 'SizeController@destroy', 'as' => 'size.destroy']);
        Route::post('/size/store', ['uses' => 'SizeController@store', 'as' => 'size.store']);
        Route::post('/size/update/{id}', ['uses' => 'SizeController@update', 'as' => 'size.update']);
        //  =======Color==========
        //  =======Products==========
        Route::get('/products', ['uses' => 'ProductController@index', 'as' => 'products']);
        Route::get('/product/create', ['uses' => 'ProductController@create', 'as' => 'product.create']);
        Route::get('/product/edit/{id}', ['uses' => 'ProductController@edit', 'as' => 'product.edit']);
        Route::get('/product/destroy/{id}', ['uses' => 'ProductController@destroy', 'as' => 'product.destroy']);
        Route::post('/product/store', ['uses' => 'ProductController@store', 'as' => 'product.store']);
        Route::post('/product/update/{id}', ['uses' => 'ProductController@update', 'as' => 'product.update']);
        Route::post('/product/verified/{id}', ['uses' => 'ProductController@update', 'as' => 'product.update']);
        //  =======Products==========

        Route::get('/purchases', ['uses' => 'PurchaseController@index', 'as' => 'purchases']);
        Route::get('/purchase/create', ['uses' => 'PurchaseController@create', 'as' => 'purchase.create']);
        Route::get('/purchase/create/addPurchaseItem', ['uses' => 'PurchaseController@addPurchaseItem', 'as' => 'purchase.createItem']);
        Route::post('/purchase/store', ['uses' => 'PurchaseController@store', 'as' => 'purchase.store']);
        Route::post('/purchase/update/{id}', ['uses' => 'PurchaseController@update', 'as' => 'purchase.update']);
        Route::get('/purchase/edit/{id}', ['uses' => 'PurchaseController@edit', 'as' => 'purchase.edit']);
        Route::get('/purchase/show/{id}', ['uses' => 'PurchaseController@show', 'as' => 'purchase.show']);
        Route::get('/purchase/verify/{id}', ['uses' => 'PurchaseController@verify', 'as' => 'purchase.verify']);
        Route::get('/purchase/detail/{id}', ['uses' => 'PurchaseController@detail', 'as' => 'purchase.detail']);
        Route::delete('/purchase/destroy/{id}', ['uses' => 'PurchaseController@destroy', 'as' => 'purchase.destroy']);
        Route::get('/purchase/priorty/{id}', ['uses' => 'PurchaseController@prioriryProduct', 'as' => 'purchase.priority']);
        Route::get('/purchase/priority-detail-status', ['uses' => 'PurchaseController@statusPriorityDetail', 'as' => 'purchase.priority_detail_status']);
        Route::get('/purchase-detail-note/{p_detail_id}', ['uses' => 'PurchaseController@purchaseDetailNote', 'as' => 'purchase.purchase_detail_note']);



        // ============Collateral==============
        // Route::get('/collateral', ['uses' => 'CollateralController@index','as' => 'collateral.index']);

        // Route::get('/collateral/create', ['uses' => 'CollateralController@create','as' => 'collateral.create']);
        // Route::get('/collateral/create/{customer_id}', ['uses' => 'CollateralController@create_with_customer','as' => 'collateral.create_with_customer']);
        // Route::get('/collateral-add-row', ['uses' => 'CollateralController@add_row','as' => 'add_row']);
        // Route::get('/collateral-add-row-in-customer', ['uses' => 'CollateralController@add_row_in_customer','as' => 'add_row_in_customer']);

        // Route::get('/collateral-add-land', ['uses' => 'CollateralController@add_land','as' => 'add_land']);
        // Route::get('/collateral/edit/{id}', ['uses' => 'CollateralController@edit','as' => 'collateral.edit']);
        // Route::get('/collateral/destroy/{id}', ['uses' => 'CollateralController@destroy','as' => 'collateral.destroy']);
        // Route::get('return-collateral', ['uses' => 'CollateralController@returnCollateral','as' => 'return_collateral']);
        // ============End Collateral==========

        // Route::get('/guarantors', ['uses' => 'GuarantorController@index', 'as' => 'guarantors']);
        // Route::get('/guarantor/create', ['uses' => 'GuarantorController@create', 'as' => 'guarantor.create']);
        // Route::get('/guarantor/edit/{id}', ['uses' => 'GuarantorController@edit', 'as' => 'guarantor.edit']);
        // Route::get('/guarantor/destroy/{id}', ['uses' => 'GuarantorController@destroy', 'as' => 'guarantor.destroy']);
        // Route::get('/guarantor/active/{id}', ['uses' => 'GuarantorController@active', 'as' => 'guarantor.active']);
        // Route::get('/guarantor/deactive/{id}', ['uses' => 'GuarantorController@deactive', 'as' => 'guarantor.deactive']);
        // Route::get('/guarantor/customer/create/{customer_id}', ['uses' => 'GuarantorController@create_with_customer', 'as' => 'guarantor.create_with_customer']);

        // ======== Salesman ==============
        // Route::get('/salesmans', ['uses' => 'SalesmanController@index', 'as' => 'salesmans']);
        // Route::get('/salesman/create', ['uses' => 'SalesmanController@create', 'as' => 'salesman.create']);
        // Route::get('/salesman/edit/{id}', ['uses' => 'SalesmanController@edit', 'as' => 'salesman.edit']);
        // Route::get('/salesman/destroy/{id}', ['uses' => 'SalesmanController@destroy', 'as' => 'salesman.destroy']);
        // Route::get('/salesman/active/{id}', ['uses' => 'SalesmanController@active', 'as' => 'salesman.active']);
        // Route::get('/salesman/deactive/{id}', ['uses' => 'SalesmanController@deactive', 'as' => 'salesman.deactive']);
        // Route::post('/salesman/changepassword', ['uses' => 'SalesmanController@changepassword', 'as' => 'salesman.changepassword']);
        // ==========Dealer==========
        // Route::get('/dealers', ['uses' => 'DealerController@index', 'as' => 'dealers']);
        // Route::get('/dealer/create', ['uses' => 'DealerController@create', 'as' => 'dealer.create']);
        // Route::get('/dealer/edit/{id}', ['uses' => 'DealerController@edit', 'as' => 'dealer.edit']);
        // Route::get('/dealer/destroy/{id}', ['uses' => 'DealerController@destroy', 'as' => 'dealer.destroy']);
        // Route::get('/dealer/active/{id}', ['uses' => 'DealerController@active', 'as' => 'dealer.active']);
        // Route::get('/dealer/deactive/{id}', ['uses' => 'DealerController@deactive', 'as' => 'dealer.deactive']);
        // Route::post('/dealer/changepassword', ['uses' => 'DealerController@changepassword', 'as' => 'dealer.changepassword']);

        // ========TimelineController ==============
        // Route::get('/timelines', ['uses' => 'TimelineController@index', 'as' => 'timelines']);
        // Route::get('/timeline/create', ['uses' => 'TimelineController@create', 'as' => 'timeline.create']);
        // Route::get('/timeline/edit/{id}', ['uses' => 'TimelineController@edit', 'as' => 'timeline.edit']);
        // Route::get('/timeline/destroy/{id}', ['uses' => 'TimelineController@destroy', 'as' => 'timeline.destroy']);
        // ============= Sale ============
        // Route::get('/sales', ['uses' => 'SaleController@index', 'as' => 'sales']);
        // Route::get('/sale_black_list', ['uses' => 'SaleController@sale_black_list', 'as' => 'sale_black_list']);
        // Route::get('/list_complete_sale', ['uses' => 'SaleController@list_complete_sale', 'as' => 'list_complete_sale']);
        // Route::get('/list_payment_late', ['uses' => 'SaleController@list_payment_late', 'as' => 'list_payment_late']);
        // Route::get('/sale/create', ['uses' => 'SaleController@create', 'as' => 'sale.create']);
        // Route::get('/sale/edit/{id}', ['uses' => 'SaleController@edit', 'as' => 'sale.edit']);
        // Route::get('/sale/destroy/{id}', ['uses' => 'SaleController@destroy', 'as' => 'sale.destroy']);
        // Route::get('/sale/payment/{id}', ['uses' => 'SaleController@payment', 'as' => 'sale.payment']);
        // Route::post('/sale/save_payment', ['uses' => 'SaleController@save_payment', 'as' => 'sale.save_payment']);
        // Route::post('/sale/save_follow_up', ['uses' => 'SaleController@save_follow_up', 'as' => 'sale.save_follow_up']);
        // Route::get('/sale/un_follow_up/{id}', ['uses' => 'SaleController@un_follow_up', 'as' => 'sale.un_follow_up']);
        // Route::post('/sale/save_cancel_payment', ['uses' => 'SaleController@save_cancel_payment', 'as' => 'sale.save_cancel_payment']);
        // Route::get('/sale/payment_timeline/{id}', ['uses' => 'SaleController@payment_timeline', 'as' => 'sale.payment_timeline']);
        // Route::get('/sale/contract/{id}', ['uses' => 'SaleController@contract', 'as' => 'sale.contract']);
        // Route::get('/sale/agreement/{id}', ['uses' => 'SaleController@agreement', 'as' => 'sale.agreement']);
        // Route::get('/sale/complete_sale/{id}', ['uses' => 'SaleController@complete_sale', 'as' => 'sale.complete_sale']);
        // Route::get('/sale/complete_payment/{id}', ['uses' => 'SaleController@complete_payment', 'as' => 'sale.complete_payment']);
        // Route::get('/sale/paid_off_payment/{id}', ['uses' => 'SaleController@paid_off_payment', 'as' => 'sale.paid_off_payment']);

        // Route::get('/sale/add_to_black_list/{id}', ['uses' => 'SaleController@add_to_black_list', 'as' => 'sale.add_to_black_list']);
        // Route::get('/sale/un_black_list/{id}', ['uses' => 'SaleController@un_black_list', 'as' => 'sale.un_black_list']);

        // Route::get('/sale/customer_icloud', ['uses' => 'SaleController@customer_icloud', 'as' => 'sale.customer_icloud']);
        // Route::get('/sale/is_change_penalty', ['uses' => 'SaleController@is_change_penalty', 'as' => 'sale.is_change_penalty']);

        // Completer Loan
        // Route::get('/loan/complete_loan/{id}', ['uses' => 'TheLoanController@complete_loan', 'as' => 'loan.complete_sale']);

        // ==========BranchDealer==========
        // Route::get('/branchdealers', ['uses' => 'BranchdealerController@index', 'as' => 'branchdealers']);
        // Route::get('/branchdealer/create', ['uses' => 'BranchdealerController@create', 'as' => 'branchdealer.create']);
        // Route::get('/branchdealer/edit/{id}', ['uses' => 'BranchdealerController@edit', 'as' => 'branchdealer.edit']);
        // Route::get('/branchdealer/destroy/{id}', ['uses' => 'BranchdealerController@destroy', 'as' => 'branchdealer.destroy']);
        //  =======Loan==========
        // Route::get('/invoices', ['uses' => 'LoanController@index', 'as' => 'loans']);
        // Route::get('/invoice/create', ['uses' => 'LoanController@create', 'as' => 'loan.create']);
        // Route::get('/invoice/edit/{id}', ['uses' => 'LoanController@edit', 'as' => 'loan.edit']);
        // Route::get('/invoice/destroy/{id}', ['uses' => 'LoanController@destroy', 'as' => 'loan.destroy']);
        //  =======Repay Loan==========
        // Route::get('/payments', ['uses' => 'LoanController@repay_index', 'as' => 'repay_loans']);
        // Route::get('/payment/create_repay', ['uses' => 'LoanController@create_repay', 'as' => 'loan.create_repay']);
        // Route::get('/payment/edit_repay/{id}', ['uses' => 'LoanController@edit_repay', 'as' => 'loan.edit_repay']);
        // Route::get('/payment/destroy_repay/{id}', ['uses' => 'LoanController@destroy_repay', 'as' => 'loan.destroy_repay']);
        //  =======Report ==========
        // Route::get('/invoice_report', ['uses' => 'ReportController@loand_report', 'as' => 'loand_report']);
        // Route::get('/payment_report', ['uses' => 'ReportController@repayloand_report', 'as' => 'repayloand_report']);
        // Route::get('/transaction_report', ['uses' => 'ReportController@transaction_report', 'as' => 'transaction_report']);
        // Route::get('/balance_report', ['uses' => 'ReportController@balance_report', 'as' => 'balance_report']);
        // Route::get('/credit_report', ['uses' => 'ReportController@credit_report', 'as' => 'credit_report']);
        // Route::get('/debit_report', ['uses' => 'ReportController@debit_report', 'as' => 'debit_report']);
        // Route::get('/statement_report', ['uses' => 'ReportController@statement_report', 'as' => 'statement_report']);
        // Route::get('/account_report', ['uses' => 'ReportController@account_report', 'as' => 'account_report']);
        // Route::get('/sales_report', ['uses' => 'ReportController@sales_report', 'as' => 'sales_report']);
        // Route::get('/loan-report', ['uses' => 'ReportController@loan_report','as' => 'loan_report']);
        // Route::get('/loan-approve-report', ['uses' => 'ReportController@loan_approve_report','as' => 'loan_approve_report']);
        // Route::get('/invest_sales_report', ['uses' => 'ReportController@invest_sales_report', 'as' => 'invest_sales_report']);
        // Route::get('/sale_payment_report', ['uses' => 'ReportController@sale_payment_report', 'as' => 'sale_payment_report']);
        // Route::get('/sale_dialy_payment_report', ['uses' => 'ReportController@sale_daily_payment_report', 'as' => 'sale_dialy_payment_report']);

        // Route::get('/customer_payment_report', ['uses' => 'ReportController@customer_payment_report', 'as' => 'customer_payment_report']);

        // Route::get('/customer_dialy_payment_report', ['uses' => 'ReportController@customer_dialy_payment_report', 'as' => 'customer_dialy_payment_report']);

        // Route::get('/customer_topay_report', ['uses' => 'ReportController@customer_topay_report', 'as' => 'customer_topay_report']);
        // Route::get('/daily_report', ['uses' => 'ReportController@daily_report', 'as' => 'daily_report']);
        // Route::get('/invoice_daily_report', ['uses' => 'ReportController@invoice_daily_report', 'as' => 'invoice_daily_report']);
        // Route::get('/profitandloss_report', ['uses' => 'ReportController@profitandloss_report', 'as' => 'profitandloss_report']);
        // Route::get('/channel_report', ['uses' => 'ReportController@channel_report', 'as' => 'channel_report']);
        // Route::get('/dealer_sale', ['uses' => 'ReportController@dealer_sale', 'as' => 'dealer_sale']);
        // Route::get('/co_report', ['uses' => 'ReportController@co_report', 'as' => 'co_report']);
        // Route::get('/daily_sale_report', ['uses' => 'ReportController@daily_sale_report', 'as' => 'daily_sale_report']);
        // Route::get('/investment_report', ['uses' => 'ReportController@investment_report', 'as' => 'investment_report']);
        // Route::get('/customer_data', ['uses' => 'ReportController@customer_data', 'as' => 'customer_data']);
        // Route::get('/export_customer_data', ['uses' => 'ReportController@export_customer_data', 'as' => 'export_customer_data']);
        // Route::get('/estimate_payment', ['uses' => 'ReportController@estimate_payment', 'as' => 'estimate_payment']);
        // Route::get('/estimate_payment_detail', ['uses' => 'ReportController@estimate_payment_detail', 'as' => 'estimate_payment_detail']);
        // Route::get('/customer-loan-statement', ['uses' => 'ReportController@customer_loan_statement_report','as' => 'customer_loan_statement_report']);
        // Route::get('/loan-pay-off-report', ['uses' => 'ReportController@pay_off_report','as' => 'pay_off_loan']);
        // Route::get('/collateral-report', ['uses' => 'ReportController@collateral_report','as' => 'collateral_report']);
        // ====carleasing report=====
        // Route::get('/carleasing_report', ['uses' => 'CarReportController@index', 'as' => 'carleasing_report']);


        // ======= JournalController Credit============
        // Route::get('/credits', ['uses' => 'JournalController@index', 'as' => 'credits']);
        // Route::get('/credit/create', ['uses' => 'JournalController@create', 'as' => 'credit.create']);
        // Route::get('/credit/edit/{id}', ['uses' => 'JournalController@edit', 'as' => 'credit.edit']);
        // Route::get('/credit/destroy/{id}', ['uses' => 'JournalController@destroy', 'as' => 'credit.destroy']);
        // ======= JournalController Credit============
        // Route::get('/debits', ['uses' => 'JournalController@index_debit', 'as' => 'debits']);
        // Route::get('/debit/create', ['uses' => 'JournalController@create_debit', 'as' => 'debit.create']);
        // Route::get('/debit/edit/{id}', ['uses' => 'JournalController@edit_debit', 'as' => 'debit.edit']);
        // Route::get('/debit/destroy/{id}', ['uses' => 'JournalController@destroy_debit', 'as' => 'debit.destroy']);
        // ======= JournalController statement============
        // Route::get('/statements', ['uses' => 'JournalController@index_statement', 'as' => 'statements']);
        // Route::get('/statement/create', ['uses' => 'JournalController@create_statement', 'as' => 'statement.create']);
        // Route::get('/statement/edit/{id}', ['uses' => 'JournalController@edit_statement', 'as' => 'statement.edit']);
        // Route::get('/statement/destroy/{id}', ['uses' => 'JournalController@destroy_statement', 'as' => 'statement.destroy']);

        //public holiday
        // Route::group(['prefix' => 'public-holiday', 'as' => 'publicHoliday.'], function () {
        //     Route::get('index', ['as' => 'index', 'uses' => 'PublicHolidayController@index']);
        //     Route::get('create', ['as' => 'create', 'uses' => 'PublicHolidayController@create']);
        //     Route::post('store', ['as' => 'store', 'uses' => 'PublicHolidayController@store']);
        //     Route::post('update/{publicHoliday}', ['as' => 'update', 'uses' => 'PublicHolidayController@update']);
        //     Route::get('edit/{publicHoliday}', ['as' => 'edit', 'uses' => 'PublicHolidayController@edit']);
        //     Route::get('destroy/{publicHoliday}', ['as' => 'destroy', 'uses' => 'PublicHolidayController@destroy']);
        // });

        //Province
        Route::group(['prefix' => 'province', 'as' => 'province.'], function () {
            Route::get('index', ['as' => 'index', 'uses' => 'ProvinceController@index']);
            Route::get('create', ['as' => 'create', 'uses' => 'ProvinceController@create']);
            Route::post('store', ['as' => 'store', 'uses' => 'ProvinceController@store']);
            Route::post('update/{province}', ['as' => 'update', 'uses' => 'ProvinceController@update']);
            Route::get('edit/{province}', ['as' => 'edit', 'uses' => 'ProvinceController@edit']);
            Route::get('destroy/{province}', ['as' => 'destroy', 'uses' => 'ProvinceController@destroy']);
        });

        //District
        Route::group(['prefix' => 'district', 'as' => 'district.'], function () {
            Route::get('index', ['as' => 'index', 'uses' => 'DistrictController@index']);
            Route::get('create', ['as' => 'create', 'uses' => 'DistrictController@create']);
            Route::post('store', ['as' => 'store', 'uses' => 'DistrictController@store']);
            Route::post('update/{district}', ['as' => 'update', 'uses' => 'DistrictController@update']);
            Route::get('edit/{district}', ['as' => 'edit', 'uses' => 'DistrictController@edit']);
            Route::get('destroy/{district}', ['as' => 'destroy', 'uses' => 'DistrictController@destroy']);
        });

        //Commune
        Route::group(['prefix' => 'commune', 'as' => 'commune.'], function () {
            Route::get('index', ['as' => 'index', 'uses' => 'CommuneController@index']);
            Route::get('create', ['as' => 'create', 'uses' => 'CommuneController@create']);
            Route::post('store', ['as' => 'store', 'uses' => 'CommuneController@store']);
            Route::post('update/{commune}', ['as' => 'update', 'uses' => 'CommuneController@update']);
            Route::get('edit/{commune}', ['as' => 'edit', 'uses' => 'CommuneController@edit']);
            Route::get('destroy/{commune}', ['as' => 'destroy', 'uses' => 'CommuneController@destroy']);
        });

        // village
        Route::group(['prefix' => 'village', 'as' => 'village.'], function () {
            Route::get('index', ['as' => 'index', 'uses' => 'VillageController@index']);
            Route::get('create', ['as' => 'create', 'uses' => 'VillageController@create']);
            Route::post('store', ['as' => 'store', 'uses' => 'VillageController@store']);
            Route::post('update/{village}', ['as' => 'update', 'uses' => 'VillageController@update']);
            Route::get('edit/{village}', ['as' => 'edit', 'uses' => 'VillageController@edit']);
            Route::get('destroy/{village}', ['as' => 'destroy', 'uses' => 'VillageController@destroy']);
        });

        // Payment Form
        Route::get('payment-form', ['uses' => 'PaymentController@payment_form','as' => 'payment_form']);
        //Loan



        // Route::group(['prefix' => 'loan', 'as' => 'loan.'], function () {
        //     // Loan Process
        //     Route::get('index', ['as' => 'index', 'uses' => 'TheLoanController@index']);
        //     Route::get('create', ['as' => 'create', 'uses' => 'TheLoanController@create']);
        //     Route::get('create/{customer_id}', ['as' => 'create_with_customer', 'uses' => 'TheLoanController@create_with_customer']);

        //     // Route::post('store', ['as' => 'store', 'uses' => 'TheLoanController@store']);
        //     // Route::post('update/{loan}', ['as' => 'update', 'uses' => 'TheLoanController@update']);
        //     Route::get('edit/{loan}', ['as' => 'edit', 'uses' => 'TheLoanController@edit']);
        //     Route::get('destroy/{loan}', ['as' => 'destroy', 'uses' => 'TheLoanController@destroy']);
        //     Route::get('show/{loan}', ['as' => 'show', 'uses' => 'TheLoanController@show']);
        //     Route::get('print/{loan}', ['as' => 'print', 'uses' => 'TheLoanController@print']);
        //     // Route::get('preview-schedule', ['as' => 'preview_schedule', 'uses' => 'TheLoanController@preview_schedule']);
        //     Route::get('payment/{loan}', ['as' => 'payment', 'uses' => 'TheLoanController@payment']);

        //     Route::get('reschedule/{loan}', ['as' => 'reschedule', 'uses' => 'TheLoanController@reschedule']);
        //     Route::post('save-reschedule/{loan}', ['as' => 'save_reschedule', 'uses' => 'TheLoanController@save_reschedule']);
        //     Route::get('waiting/approved/reschedule-loan', ['as' => 'waiting_approved_reschedule_loan', 'uses' => 'TheLoanController@waiting_approved_reschedule_loan']);
        //     Route::get('preview-reschedule-loan', ['as' => 'preview_reschedule_loan', 'uses' => 'TheLoanController@preview_reschedule_loan']);

        //     Route::get('print-reschedule/{loan}', ['as' => 'print_reschedule', 'uses' => 'TheLoanController@print_reschedule']);
        //     Route::post('reject/reschedule', ['as' => 'reject_reschedule', 'uses' => 'TheLoanController@reject_reschedule']);

        //     Route::post('reject/reschedule', ['as' => 'reject_reschedule', 'uses' => 'TheLoanController@reject_reschedule']);
        //     Route::post('reschedule-loan/approve', ['as' => 'approve_reschedule_laon', 'uses' => 'TheLoanController@approve_reschedule_laon']);
        //     Route::get('waiting/approve', ['as' => 'waiting_approve','uses' => 'TheLoanController@waiting_approve']);
        //     Route::post('loan/approve', ['as' => 'approve','uses' => 'TheLoanController@approve']);
        //     Route::post('loan/reject', ['as' => 'reject','uses' => 'TheLoanController@reject']);
        //     Route::get('pay-off/{loan_id}', ['as' => 'pay-off','uses' => 'TheLoanController@loanPayOff']);
        //     Route::get('transfer_loan/{loan}', ['as' => 'transfer_loan', 'uses' => 'TheLoanController@transfer_loan']);
        //     Route::post('save_transfer_loan/{loan}', ['as' => 'save_transfer_loan', 'uses' => 'TheLoanController@save_transfer_loan']);
        //     Route::get('multi_transfer_loan', ['as' => 'multi_transfer_loan', 'uses' => 'TheLoanController@multi_transfer_loan']);
        //     Route::post('save_multi_transfer_loan', ['as' => 'save_multi_transfer_loan', 'uses' => 'TheLoanController@save_multi_transfer_loan']);
        //     //Loan Payment
        //     Route::group(['prefix' => 'payment', 'as' => 'payment.'], function () {
        //         Route::get('{payment}/show', ['as' => 'show', 'uses' => 'PaymentController@show']);
        //         Route::get('{payment}/print', ['as' => 'print_payment', 'uses' => 'PaymentController@print_payment']);
        //         Route::get('{payment}/revers_payment/form', ['as' => 'reverse_payment_form','uses' => 'PaymentController@reverse_payment_form']);
        //     });
        //     //Colection Sheet
        //     Route::get('collectionsheet', ['as' => 'collectionsheet','uses' => 'CollectionSheetController@collection_sheet']);
        //     Route::group(['prefix' => 'collection-sheet', 'as' => 'collection-sheet.'], function () {
        //         Route::get('index', ['as' => 'index', 'uses' => 'CollectionSheetController@index']);
        //     });

        // });


    });
    Route::get('settings/user-permision/{id}', 'SettingController@addUserPermision')->name('setting.user_permision.add');
    Route::post('settings/user-permision/save', 'PermisionController@savePermision')->name('setting.user_permision.save');
    //Ajax Route
    Route::get('ajax/get-customer', ['uses' => 'GeneralController@customerByBranch'])->name('customer_by_branch');
    Route::get('ajax/get-customer_new', ['uses' => 'GeneralController@customerByBranchNew'])->name('customer_by_branch_new');
    // Route::get('ajax/get-dealer', ['uses' => 'GeneralController@dealerByBranch'])->name('dealer_by_branch');
    // Route::get('ajax/get-co', ['uses' => 'GeneralController@coByBranch'])->name('co_by_branch');
    Route::get('ajax/get-guarantor', ['uses' => 'GeneralController@guarantorByBranch'])->name('guarantor_by_branch');
    Route::get('ajax/get-loan', ['uses' => 'GeneralController@loanByCo'])->name('loan_by_co');
    Route::get('ajax/get-product', ['uses' => 'GeneralController@filterProduct'])->name('ajax.filter.filterProduct');
    Route::get('ajax/get-product-item', ['uses' => 'GeneralController@getProductItem'])->name('ajax.getProductItem');

    //public holiday
    // Route::group(['prefix' => 'public-holiday', 'as' => 'publicHoliday.'], function () {
    //     Route::post('update/{publicHoliday}', ['as' => 'update', 'uses' => 'PublicHolidayController@update']);
    // });
    //Loan
    // Route::group(['prefix' => 'loan', 'as' => 'loan.'], function () {
    //     Route::post('update/{loan}', ['as' => 'update', 'uses' => 'TheLoanController@update']);
    //     Route::post('save-pay-off/{loan_id}', ['as' => 'save-pay-off','uses' => 'TheLoanController@saveLoanPayOff']);
    // });
    //Group Expense
    // Route::get('ajax/get-group-expense', ['uses' => 'ExpenseController@groupExpense'])->name('branch.group_expense');
    // Route::get('ajax/get-group-icome', ['uses' => 'IncomeController@groupIcome'])->name('branch.group_income');
    //Print
    // Route::get('loan/contract/{sale}', ['uses' => 'LoanReportController@loanContract', 'as' => 'loan.contract']);
    // Route::get('loan/contracts/{id}', ['uses' => 'LoanReportController@loanContract1', 'as' => 'loan.contracts']);
    // Penalty
    // Route::get('loan/payment/penalty/{payment}', ['as' => 'loan.penalty', 'uses' => 'PaymentController@penalty']);

    //Contract
    // Route::get('contract3/{loan_id}', [LoanReportController::class, 'contract3'])->name('contract3');
    // Route::get('contract4/{loan_id}', [LoanReportController::class, 'contract4'])->name('contract4');
    // Route::get('contract5/{loan_id}', [LoanReportController::class, 'contract5'])->name('contract5');
    // Route::get('receipt', [LoanReportController::class, 'receipt'])->name('receipt');

    //Purchase Return
    Route::get('purchase-return/auto-complete', [PurchaseReturnController::class, 'autoCompleteProduct'])->name('autoCompleteProduct');
    Route::get('purchase-return/search-item', [PurchaseReturnController::class, 'searchItemProduct'])->name('searchItemProduct');
    Route::get('purchase-return/add-multiple-size', [PurchaseReturnController::class, 'addMultipleSize'])->name('addMultipleSize');
    Route::get('purchase-return/add-get-data-multiple-size', [PurchaseReturnController::class, 'getDataMultipleSize'])->name('getDataMultipleSize');
    //Purchase Order
    Route::get('purchase-order/search-item', [PurchaseController::class, 'searchItemProductPurchaseOrder'])->name('searchItemProductPurchaseOrder');

    Route::resource('status', 'StatusController');

});

Route::group(['prefix' => 'supplier','middleware' => 'assign.guard:supplieradmin', 'as' => 'supplier.'], function () {
    Route::post('/supplier-logout', ['uses' => 'SupplierAdminController@logout', 'as' => 'logOut']);
    Route::get('/dashboard', ['uses' => 'SupplierHomePage@dashboard', 'as' => 'dashboard']);
    Route::get('/purchase-order-list', ['uses' => 'SupplierHomePage@purchaseOrderList', 'as' => 'purchaseOrderList']);
    Route::get('/purchase/show/{id}', ['uses' => 'SupplierHomePage@purchaseDetail', 'as' => 'purchaseDetail']);

    Route::patch('/purchase/accept/{id}', ['uses' => 'SupplierHomePage@acceptPurchase', 'as' => 'accept']);
    Route::patch('/purchase/reject/{id}', ['uses' => 'SupplierHomePage@rejectPurchase', 'as' => 'reject']); // Not yet
    Route::patch('/purchase/ship/{id}', ['uses' => 'SupplierHomePage@shipPurchase', 'as' => 'ship']);
    Route::get('/purchase/download-purchase/{id}', ['uses' => 'SupplierHomePage@downloadPurchase', 'as' => 'downloadPurchase']);

    Route::get('/purchase-return-list', ['uses' => 'SupplierHomePage@purchaseReturnList', 'as' => 'purchaseReturnList']);
    Route::get('/purchase-return-detail/{id}', ['uses' => 'SupplierHomePage@purchaseReturnDetail', 'as' => 'purchaseReturnDetail']);
    Route::get('/purchase-return-accept/{id}', ['uses' => 'SupplierHomePage@acceptPurchaseReturn', 'as' => 'acceptPurchaseReturn']);
    Route::get('/purchase-return-accept/{id}', ['uses' => 'SupplierHomePage@search_priority', 'as' => 'search_priority']);
    Route::get('/purchase/download-purchase-return/{id}', ['uses' => 'SupplierHomePage@downloadPurchaseReturn', 'as' => 'downloadPurchaseReturn']);

    // =====

    Route::patch('/purchase-return/accept/{id}', ['uses' => 'SupplierHomePage@acceptPurchaseReturn', 'as' => 'acceptPurchaseReturn']);
    Route::patch('/purchase-return/reject/{id}', ['uses' => 'SupplierHomePage@rejectPurchaseReturn', 'as' => 'rejectPurchaseReturn']);
    Route::patch('/purchase-return/ship/{id}', ['uses' => 'SupplierHomePage@shipPurchaseReturn', 'as' => 'shipPurchaseReturn']);

});

//Route::get('test', function () {
//    $telegram_api_url = 'https://api.telegram.org/bot' . config('app.telegram_token');
////    $api_res = Http::get($telegram_api_url . '/getUpdates');
//    $api_res = Http::post($telegram_api_url . '/sendMessage', [
//        'chat_id' => 375015213,
//        'text' => 'Purchase order has been verified.',
//    ]);
//    dd($api_res->json());
//});

Route::get('now', function () {
    return response()->json(now());
});
