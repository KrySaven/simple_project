<?php

namespace App\Http\Controllers;

use App\Helpers\MyHelper;
use App\Products;
use App\Purchase;
use App\PurchaseReturn;
use App\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use ZipArchive;

class SupplierHomePage extends Controller
{
    public function dashboard()
    {
        return view('admin_supplier.layout_supplier.supplier_index');
    }

    public function purchaseOrderList(Request $request)
    {
        $suppliers = Supplier::pluck('name', 'id');
        $search     = $request->search;
        $date       = date('Y-m-d');
        $date_from  = $request->date_from;
        $date_to    = $request->date_to;
        $supplier_id   = $request->supplier_id;

        $purchases   = Purchase::where(function ($query) use ($search) {
            $query->orWhere('purchase_code', 'like', '%'.$search.'%')
                ->orWhere('status', 'like', '%'.$search.'%');
        }) ->with(['creator:id,name', 'supplier:id,name']);

        if($date_from != '' && $date_to) {
            $purchases = $purchases->whereDate('created_at', '>=', $date_from)
                                ->whereDate('created_at', '<=', $date_to);
        }
        if($supplier_id != null) {
            $purchases = $purchases->where('supplier_id', $supplier_id);
        }
        $purchases = $purchases->where('supplier_id', $request->user()->id)
            ->whereIn('status', ['Verified','Accepted','Rejected','Shipping'])
            ->orderBy('date', 'DESC')
            ->paginate(20);
        return view('admin_supplier.purchase_order_list', compact('purchases', 'search', 'suppliers', 'supplier_id'));
    }
    public function purchaseReturnList(Request $request)
    {
        $suppliers = Supplier::pluck('name', 'id');
        $search     = $request->search;
        $date       = date('Y-m-d');
        $date_from  = $request->date_from;
        $date_to    = $request->date_to;
        $supplier_id   = $request->supplier_id;

        $purchasesReturn   = PurchaseReturn::where(function ($query) use ($search) {
            $query->orWhere('purchase_code', 'like', '%'.$search.'%')
                ->orWhere('purchase_return_code', 'like', '%'.$search.'%');
        }) ->with(['creator:id,name', 'supplier:id,name']);

        if($date_from != '' && $date_to) {
            $purchasesReturn = $purchasesReturn->whereDate('created_at', '>=', $date_from)
                                ->whereDate('created_at', '<=', $date_to);
        }

        if($supplier_id != null) {
            $purchasesReturn = $purchasesReturn->where('supplier_id', $supplier_id);
        }
        $purchasesReturn = $purchasesReturn->where('supplier_id', $request->user()->id)
            ->whereIn('status', ['Verified','Accepted','Rejected'])
            ->orderBy('date', 'DESC')
            ->paginate(20);
        return view('admin_supplier.pages.purchase_return_list', compact('purchasesReturn', 'search', 'suppliers', 'supplier_id'));
    }

    public function purchaseDetail(Request $request, $id)
    {
        $purchase = Purchase::find($request->id);
        $purchaseDetail = $purchase->productDetails;
        return view('admin_supplier.purchaseDetail', compact('purchase', 'purchaseDetail'));
    }

    public function purchaseReturnDetail($id)
    {
        $purchaseReturn = PurchaseReturn::find($id);
        $purchaseReturnDetail = $purchaseReturn->productDetails;

        return view('admin_supplier.pages.purchase_retutn_detail', compact('purchaseReturn', 'purchaseReturnDetail'));
    }

    public function acceptPurchaseReturn($id)
    {
        $current_user_id = Auth::user()->id;
        $purchaseReturn = PurchaseReturn::find($id);
        if($purchaseReturn->status == "Verified") {
            $purchaseReturn->status = "Accepted";
            $purchaseReturn->accepted_by = $current_user_id;
            $purchaseReturn->accepted_date = now();
            $purchaseReturn->save();

            $notification = array(
               'message'       => __('app.accept_successfully'),
               'alert-type'    => 'success'
            );
            return redirect()->back()->with($notification);


        } else {
            $notification = array(
                'message'       => __('app.cannot_accept'),
                'alert-type'    => 'success'
            );
            return redirect()->back()->with($notification);
        }
    }


    public function acceptPurchase($id)
    {
        $current_user_id = Auth::user()->id;
        $purchase = Purchase::find($id);
        if($purchase->status == "Verified") {
            $purchase->status = "Accepted";
            $purchase->accepted_by = $current_user_id;
            $purchase->accepted_date = now();
            $purchase->save();
            $notification = array(
                'message'       => __('app.accept_successfully'),
                'alert-type'    => 'success'
            );
            return redirect()->back()->with($notification);


        } else {
            $notification = array(
                'message'       => __('app.cannot_accept'),
                'alert-type'    => 'success'
            );
            return redirect()->back()->with($notification);


        }
    }
    public function shipPurchase($id)
    {
        $current_user_id = Auth::user()->id;
        $purchase = Purchase::find($id);
        if($purchase->status == "Accepted") {
            $purchase->status = "Shipping";
            // $purchase->accepted_by = $current_user_id;
            // $purchase->accepted_date = now();
            $purchase->save();
            $notification = array(
                'message'       => "Ship Successfully",
                'alert-type'    => 'success'
            );
            return redirect()->back()->with($notification);


        } else {
            $notification = array(
                'message'       => __('app.cannot_accept'),
                'alert-type'    => 'success'
            );
            return redirect()->back()->with($notification);


        }
    }

    public static function downloadPurchase($id)
    {
        $purchase = Purchase::findOrFail($id);
        $files = $purchase->purchasePdfs;

        $zip = new ZipArchive;
        $fileName = 'zipFileName.zip';
        $has_file = false;
        if ($files) {
            foreach ($files as $value) {
                if(is_file(base_path($value->path))) {
                    $has_file = true;
                }
            }
        }


        if($has_file == true) {
            if ($zip->open(base_path($fileName), ZipArchive::CREATE) === true) {
                foreach ($files as $value) {
                    if(is_file(base_path($value->path))) {
                        $relativeNameInZipFile = basename($value->path);
                        $zip->addFile(base_path($value->path), $relativeNameInZipFile);
                    }
                }
                $zip->close();
            }

            // Download the generated zip
            return response()->download(base_path($fileName))->deleteFileAfterSend(true);

        } else {

            $notification = array(
               'message'       => __('app.no_files'),
               'alert-type'    => 'error'
           );
            return redirect()->back()->with($notification);

        }
    }

    public static function downloadPurchaseReturn($id)
    {
        $purchase = PurchaseReturn::findOrFail($id);
        $files = $purchase->purchasePdfs;

        $zip = new ZipArchive;
        $fileName = 'zipFileName.zip';
        $has_file = false;
        if ($files) {
            foreach ($files as $value) {
                if(is_file(base_path($value->path))) {
                    $has_file = true;
                }
            }

        }

        if($has_file == true) {
            if ($zip->open(base_path($fileName), ZipArchive::CREATE) === true) {
                foreach ($files as $value) {
                    if(is_file(base_path($value->path))) {
                        $relativeNameInZipFile = basename($value->path);
                        $zip->addFile(base_path($value->path), $relativeNameInZipFile);
                    }
                }
                $zip->close();
            }

            return response()->download(base_path($fileName))->deleteFileAfterSend(true);

        } else {

            $notification = array(
               'message'       => __('app.no_files'),
               'alert-type'    => 'error'
           );
            return redirect()->back()->with($notification);

        }
    }


}
