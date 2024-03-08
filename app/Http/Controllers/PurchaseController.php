<?php

namespace App\Http\Controllers;

use App\Exchange;
use App\Helpers\MyHelper;
use App\Products;
use App\Purchase;
use App\PurchaseDetail;
use App\PurchaseHistory;
use App\PurchasePDF;
use App\PurchaseProductSize;
use App\Rules\ArrayOneNotNull;
use App\Size;
use App\Status;
use App\Supplier;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $suppliers = Supplier::pluck('name','id');
        $search     = $request->search;
        $date       = date('Y-m-d');
        $date_from  = $request->date_from;
        $date_to    = $request->date_to;
        $supplier_id   = $request->supplier_id;
        $purchases   = Purchase::where(function ($query) use ($search) {
            $query->orWhere('amount', 'like', '%' . $search . '%')
                ->orWhere('discount', 'like', '%' . $search . '%')
                ->orWhere('status', 'like', '%' . $search . '%')
                ->orWhere('created_at', 'like', '%' . $search . '%');
        })->with(['creator:id,name', 'supplier:id,name']);

        if($date_from != '' && $date_to) {
            $purchases = $purchases->whereDate('created_at', '>=', $date_from)
                                ->whereDate('created_at', '<=', $date_to);
        }

        if($supplier_id){
            $purchases = $purchases->where('supplier_id',$supplier_id);
        }

       $purchases = $purchases->orderBy('date', 'DESC')
        ->paginate(20);

        return view('admin.purchase_order.index', compact(
            'purchases',
            'search',
            'date_from',
            'date_to',
            'suppliers',
            'supplier_id',
        ));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $p_code = $this->getPurchaseNo();
        $suppliers  = Supplier::pluck('name', 'id');
        $all_products = Products::pluck('name', 'id');
        return view('admin.purchase_order.create', compact('suppliers', 'all_products', 'p_code'));
    }

    public function getPurchaseNo()
    {
        $prefix     = "PO";
        $pro_no    = "";
        if ($prefix != "") {
            $pro_no = IdGenerator::generate([
                'table'     => 'purchases',
                'length'    => 7,
                'field'     =>  'p_no',
                'prefix'    => $prefix
            ]);
        }
        return $pro_no;
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'supplier_id'       => 'required',
            'date'              => 'required',
            'amount'            => 'required',
            'discount'          => 'required',
            'grand_total'       => 'required',
            'product_ids'       => 'bail|required|array',
            'qtys'              => 'required|array',
            'qtys.*'            => 'required',
            'color_ids'         => 'required|array',
            'color_ids.*'       => 'required',
            'size_ids'          => 'array',
            'size_ids.*'        => 'required',
            'unit_prices'       => 'required|array',
            'total_prices'      => 'required|array',
            'purchase_code'     => 'required|unique:purchases|max:255',
            'pdfFiles'          => 'array',
            'pdfFiles.*'        => 'mimes:pdf|max:2048',
        ], [
            'color_ids.*.required'  => 'Please select a color.',
            'size_ids.*.required'   => 'Please select a size.',
            'qtys.*.required'       => 'Please insert qty.',
            'pdfFiles.*.max:2048'      => 'PDF file Must be Under 2M',
        ]);
        // dd($request->all());
        if ($validator->fails()) {
            MyHelper::responeDataJSON(null, '', 401, $validator->getMessageBag());
        }
        $current_user_id = Auth::user()->id;
        // dd($request->all());
        try {
            //code...
            DB::transaction(function () use ($request, $current_user_id) {
                $purchase = Purchase::create([
                    'p_no'        => $request->p_no,
                    'purchase_code'   => $request->purchase_code,
                    'supplier_id' => $request->supplier_id,
                    'date'        => date('Y-m-d', strtotime($request->date)),
                    'status'      => 'Pending',
                    'amount'      => $request->amount,
                    'discount'    => $request->discount,
                    'grand_total' => $request->grand_total,
                    'source_image' => $request->source_image,
                    'total_qty'    => $request->total_qty,
                    'created_by'  => $current_user_id,
                    'created_at'  => now(),
                ]);
                // UPLOAD MULTIPLE PDF
                // dd($request->pdfFiles);
                $i = 0;
                if($request->pdfFiles && count($request->pdfFiles) > 0) {
                    foreach ($request->pdfFiles as $req) {
                        $i++;
                        $image = $req;
                        $newimage = $image->getClientOriginalName();
                        $newimage = strtotime(date('Y-m-d H:i:s')).'_'.$i.'_'.str_replace(' ', '_', $newimage);
                        $image->move('images/purchase-pdfs/', $newimage);

                        $filenametostore =  $newimage ;
                        $image_path = './images/purchase-pdfs/'. $filenametostore;

                        PurchasePDF::create([
                            'purchase_id'   => $purchase->id,
                            'path'          => 'images/purchase-pdfs/'.$filenametostore,
                        ]);

                    }
                }

                $this->insertPurchaseDetails($purchase, $request, $current_user_id);
                $purchaseHistory = [
                    'purchase_id'   => $purchase->id,
                    'status'        => $purchase->status,
                    'date'          => date('Y-m-d', strtotime($request->date)),
                    'created_at'    => now(),
                    'created_by'    => $current_user_id
                ];

                PurchaseHistory::insert($purchaseHistory);
            });
        } catch (\Throwable $th) {
            throw $th;
        }

        $messages = [
            'icon' => 'success',
            'title' => __('message.success')
        ];

        $data = [
            'reload_url' => route('purchases'),
        ];
        MyHelper::responeDataJSON($data, '', 200, $messages);
    }

    public function insertPurchaseDetails($purchase, $request, $current_user_id)
    {
        foreach ($request->product_ids as $index => $product_id) {

            $purchase_detail_id = PurchaseDetail::create([
                'purchase_id' => $purchase->id,
                'product_id'  => $request->product_ids[$index] ?? 0,
                'color_id'    => $request->color_ids[$index] ?? 0,
                'size_id'     => $request->size_ids[$index] ?? 0,
                'price'       => $request->unit_prices[$index] ?? 0,
                'qty'         => $request->qtys[$index] ?? 0,
                'amount'      => $request->total_prices[$index] ?? 0,
                'discount'    => 0,
                'total'       => $request->total_prices[$index] ?? 0,
                'creator_id'  => $current_user_id,
                'created_at'  => now(),
            ]);
        }
    }

    public function edit($id)
    {
        $data = [];
        $purchase        = Purchase::findOrFail($id);
        $data = [
            'purchase'  => $purchase,
            'purchaseDetail' => $purchase->productDetails,
            'suppliers' => Supplier::pluck('name', 'id'),
            'pdfs'      => $purchase->purchasePdfs ? count($purchase->purchasePdfs) : 0,
        ];
        return view('admin.purchase_order.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $purchase        = Purchase::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'supplier_id'       => 'required',
            'date'              => 'required',
            'amount'            => 'required',
            'discount'          => 'required',
            'grand_total'       => 'required',
            'product_ids'       => 'bail|required|array',
            'qtys'              => 'required|array',
            'qtys.*'            => 'required',
            'color_ids'         => 'required|array',
            'color_ids.*'       => 'required',
            'size_ids'          => 'array',
            'size_ids.*'        => 'required',
            'unit_prices'       => 'required|array',
            'total_prices'      => 'required|array',
            'purchase_code'     => 'required|unique:purchases,purchase_code,'.$id,
            'pdfFiles'          => 'array',
            'pdfFiles.*'        => 'mimes:pdf|max:2048',
        ], [
            'color_ids.*.required'  => 'Please select a color.',
            'size_ids.*.required'   => 'Please select a size.',
            'qtys.*.required'       => 'Please insert qty.',
            'pdfFiles.*.max:2048'      => 'PDF file Must be Under 2M',
        ]);

        if ($validator->fails()) {
            MyHelper::responeDataJSON(null, '', 401, $validator->getMessageBag());
        }

        $current_user_id = Auth::user()->id;
        try {
            DB::transaction(function () use ($request, $current_user_id, $purchase, $id) {
                $purchase->p_no          = $request->p_no;
                $purchase->supplier_id   = $request->supplier_id;
                $purchase->date          = date('Y-m-d', strtotime($request->date));
                $purchase->status        = 'Pending';
                $purchase->amount        = $request->amount;
                $purchase->discount      = $request->discount;
                $purchase->grand_total   = $request->grand_total;
                $purchase->total_qty     = $request->total_qty;
                $purchase->created_by    = $current_user_id;
                $purchase->purchase_code = $request->purchase_code;
                $purchase->source_image  = $request->source_image;
                $purchase->created_at    = now();
                $purchase->save();
                PurchaseDetail::where('purchase_id', $id)->delete();
                $this->insertPurchaseDetails($purchase, $request, $current_user_id);

                // UPDATE FILE PDF UPLOAD
                $i = 0;
                if($request->pdfFiles && count($request->pdfFiles) > 0) {
                    $pdfs = $purchase->purchasePdfs;
                    foreach ($pdfs as $pdf) {
                        if (file_exists((string)$pdf->path)) {
                            $pdf->delete();
                            unlink($pdf->path);
                        }
                    }

                    $productPdfs = PurchasePDF::where('purchase_id', $id)->get();
                    foreach ($productPdfs as $productPdf) {
                        $file_path = $productPdf->path;
                        File::delete($file_path);
                    }

                    foreach ($request->pdfFiles as $req) {
                        $i++;
                        $image = $req;
                        $newimage = $image->getClientOriginalName();
                        $newimage = strtotime(date('Y-m-d H:i:s')).'_'.$i.'_'.str_replace(' ', '_', $newimage);
                        $image->move('images/purchase-pdfs/', $newimage);

                        $filenametostore =  $newimage ;
                        $image_path = './images/purchase-pdfs/' . $filenametostore;
                        PurchasePDF::create([
                            'purchase_id' => $id,
                            'path' => 'images/'.$filenametostore,
                        ]);
                    }

                }

            });

        } catch (\Throwable $th) {
            throw $th;
        }

        $messages = [
            'icon' => 'success',
            'title' => __('message.success')
        ];

        $data = [
            'reload_url' => route('purchases'),
        ];
        MyHelper::responeDataJSON($data, '', 200, $messages);
    }


    public function searchItemProductPurchaseOrder(Request $request)
    {
        $index  = $request->index;
        $data   = $request->data;
        $result = new Products();
        $result = $result->where('code_product', $request->searchValue)
            ->join('units', 'units.id', 'products.unit_id')
            ->select(
                'products.*',
                'units.type AS type',
                'units.name AS unit',
            )->first();
        $colors = $result->colors->pluck('name', 'id');
        $sizes  = Size::where('unit_id', $result->unit_id)->pluck('name', 'id') ?? null;
        return view('admin.purchase_order.row_item', compact('result', 'colors', 'index', 'sizes'))->render();
    }

    public function detail(Request $request)
    {
        $purchase = Purchase::find($request->id);
        $purchaseDetail = $purchase->productDetails;
        return view('admin.purchase_order.detail', compact('purchase', 'purchaseDetail'));
    }

    public function verify(Request $request)
    {
        $current_user_id = Auth::user()->id;
        $purchase = Purchase::find($request->id);
        if($purchase->status == "Pending") {
            $purchase->status = "Verified";
            $purchase->verifier = $current_user_id;
            $purchase->verify_date = now();
            $purchase->save();
            $message = [
                'icon' => 'success',
                'title' => __('app.verify_successfully')
            ];
            return MyHelper::responeDataJSON(null, '', 200, $message);

        } else {
            $message = [
                    'icon' => 'error',
                    'title' => __('app.cannot_verify')
                ];
            return MyHelper::responeDataJSON(null, '', 200, $message);

        }
    }

    public function destroy(Request $request)
    {

        // dd($request->all());
        $purchase = Purchase::find($request->id);
        $current_user_id = Auth::user()->id;
        if($purchase->status == "Pending" && $purchase->verifier == null) {
            $purchase->delete();
            $purchase->deleted_by = $current_user_id;
            $purchase->save();
            $message = [
                'icon' => 'success',
                'title' => __('app.delete_success')
            ];
            return MyHelper::responeDataJSON(null, '', 200, $message);
        } else {
            $message = [
                    'icon' => 'error',
                    'title' => __('app.cannot_delete')
                ];
            return MyHelper::responeDataJSON(null, '', 401, $message);

        }

    }


    public function prioriryProduct(Request $request)
    {
        $status = Status::pluck('name', 'id');
        $purchase = Purchase::find($request->id);
        $purchaseDetail = $purchase->productDetails;
        return view('admin.purchase_order.priority_product', compact('purchase', 'purchaseDetail', 'status'));
    }
    public function statusPriorityDetail(Request $request)
    {

        $row_detail = PurchaseDetail::findOrFail($request->item_detail_id);
        $row_detail->update([
            'status_date'   => now(),
            'status_id'     => $request->purchase_status_id
        ]);
        $message = [
                  'icon' => 'error',
                  'title' => __('app.success')
              ];
        return MyHelper::responeDataJSON(null, '', 200, $message);

    }
    public function purchaseDetailNote(Request $request, $p_detail_id)
    {

        return view('admin.purchase_order.purchase_detail_note', compact('p_detail_id'));
        // $row_detail = PurchaseDetail::findOrFail($request->item_detail_id);
        // $row_detail->update([
        //     'status_date'   => now(),
        //     'status_id'     => $request->purchase_status_id
        // ]);
        // $message = [
        //           'icon' => 'error',
        //           'title' => __('app.success')
        //       ];
        // return MyHelper::responeDataJSON(null, '', 200, $message);

    }


}
