<?php

namespace App\Http\Controllers;

use App\Exchange;
use App\Helpers\MyHelper;
use App\Products;
use App\PurchaseDetail;
use App\PurchaseReturn;
use App\PurchaseReturnDetail;
use App\PurchaseReturnPDF;
use App\Size;
use App\Supplier;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class PurchaseReturnController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search     = $request->search;
        $date       = date('Y-m-d');
        $date_from  = $request->date_from;
        $date_to    = $request->date_to;
        $purchases   = PurchaseReturn::where(function ($query) use ($search) {
            $query->orWhere('amount', 'like', '%' . $search . '%')
                ->orWhere('discount', 'like', '%' . $search . '%')
                ->orWhere('status', 'like', '%' . $search . '%');
        })
            ->with(['creator:id,name', 'supplier:id,name'])
            ->orderBy('date', 'DESC')
            ->paginate(20);
        // dd($purchases);
        return view('admin.purchase-return.index', compact('purchases', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $pr_code = $this->getPurchaseReturnNo();
        $suppliers  = Supplier::pluck('name', 'id');
        $all_products = Products::pluck('name', 'id');
        return view('admin.purchase-return.create', compact('suppliers', 'all_products', 'pr_code'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

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
            'purchase_return_code'      => 'required|unique:purchase_return,purchase_return_code',
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
            // dd($request->all());
            DB::transaction(function () use ($request, $current_user_id) {
                $purchaseReturn = PurchaseReturn::create([
                    'purchase_return_code'  => $request->purchase_return_code,
                    'generate_code'         => $request->generate_code,
                    'supplier_id'           => $request->supplier_id,
                    'date'                  => date('Y-m-d', strtotime($request->date)),
                    'status'                => 'Pending',
                    'amount'                => $request->amount,
                    'source_image'          => $request->source_image,
                    'purchase_code'         => $request->purchase_code,
                    'discount'              => $request->discount,
                    'total_qty'             => $request->total_qty,
                    'grand_total'           => $request->grand_total,
                    'created_by'            => $current_user_id,
                    'created_at'            => now(),
                ]);

                // UPLOAD MULTIPLE PDF

                $i = 0;
                if($request->pdfFiles && count($request->pdfFiles) > 0) {
                    foreach ($request->pdfFiles as $req) {
                        $i++;
                        $image = $req;
                        $newimage = $image->getClientOriginalName();
                        $newimage = strtotime(date('Y-m-d H:i:s')).'_'.$i.'_'.str_replace(' ', '_', $newimage);
                        $image->move('images/purchase-return-pdfs', $newimage);

                        $filenametostore =  $newimage ;
                        $image_path = './images/purchase-return-pdfs' . $filenametostore;

                        PurchaseReturnPDF::create([
                            'purchase_return_id' => $purchaseReturn->id,
                            'path' => 'images/purchase-return-pdfs/'.$filenametostore,
                        ]);
                    }
                }

                // STORE DETAIL PRODUCT
                $this->insertPurchaseReturnDetails($purchaseReturn, $request, $current_user_id);
            });
        } catch (\Throwable $th) {
            throw $th;
        }

        $messages = [
            'icon' => 'success',
            'title' => __('message.success')
        ];

        $data = [
            'reload_url' => route('purchase_returns'),
        ];
        MyHelper::responeDataJSON($data, '', 200, $messages);
    }

    public function insertPurchaseReturnDetails($purchaseReturn, $request, $current_user_id)
    {
        foreach ($request->product_ids as $index => $product_id) {

            $purchase_detail_id = PurchaseReturnDetail::create([
                'purchase_return_id' => $purchaseReturn->id,
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
                'purchase_code'  => $request->purchase_code,
                'source_image'   => $request->source_image,
            ]);
        }
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
        $data = [];
        $pReturn        = PurchaseReturn::findOrFail($id);
        $data = [
            'pReturn'   => $pReturn,
            'pRDetail'  => $pReturn->productDetails,
            'suppliers' => Supplier::pluck('name', 'id'),
            'pdfs'      => $pReturn->purchaseReturnPDFs ? count($pReturn->purchaseReturnPDFs) : 0,
        ];
        // dd($pReturn->purchaseReturnPDFs);
        return view('admin.purchase-return.edit', compact('data'));
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
        $pReturn        = PurchaseReturn::findOrFail($id);
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
            'purchase_return_code'      => 'required|unique:purchase_return,purchase_return_code,'.$id,
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
        // dd($request->all());
        try {
            DB::transaction(function () use ($request, $current_user_id, $pReturn, $id) {
                $pReturn->purchase_return_code   = $request->purchase_return_code;
                $pReturn->generate_code   = $request->generate_code;
                $pReturn->supplier_id   = $request->supplier_id;
                $pReturn->date          = date('Y-m-d', strtotime($request->date));
                $pReturn->status        = 'Pending';
                $pReturn->amount        = $request->amount;
                $pReturn->discount      = $request->discount;
                $pReturn->grand_total   = $request->grand_total;
                $pReturn->created_by    = $current_user_id;
                $pReturn->source_image  = $request->source_image;
                $pReturn->purchase_code  = $request->purchase_code;
                $pReturn->total_qty     = $request->total_qty;
                $pReturn->created_at    = now();
                $pReturn->save();


                // UPDATE FILE PDF UPLOAD
                $i = 0;

                if($request->pdfFiles && count($request->pdfFiles) > 0) {
                    $pdfs = $pReturn->purchaseReturnPDFs;
                    foreach ($pdfs as $pdf) {
                        if (file_exists((string)$pdf->path)) {
                            $pdf->delete();
                            unlink($pdf->path);
                        }
                    }

                    $productPdfs = PurchaseReturnPDF::where('purchase_return_id', $id)->get();
                    foreach ($productPdfs as $productPdf) {
                        $file_path = $productPdf->path;
                        File::delete($file_path);
                    }

                    foreach ($request->pdfFiles as $req) {
                        $i++;
                        $image = $req;
                        $newimage = $image->getClientOriginalName();
                        $newimage = strtotime(date('Y-m-d H:i:s')).'_'.$i.'_'.str_replace(' ', '_', $newimage);
                        $image->move('images/purchase-return-pdfs', $newimage);

                        $filenametostore =  $newimage ;
                        $image_path = './images/purchase-return-pdfs' . $filenametostore;
                        PurchaseReturnPDF::create([
                            'purchase_return_id' => $id,
                            'path' => 'images/'.$filenametostore,
                        ]);
                    }
                }



                PurchaseReturnDetail::where('purchase_return_id', $id)->delete();

                $this->insertPurchaseReturnDetails($pReturn, $request, $current_user_id);

            });
        } catch (\Throwable $th) {
            throw $th;
        }

        $messages = [
            'icon' => 'success',
            'title' => __('message.success')
        ];

        $data = [
            'reload_url' => route('purchase_returns'),
        ];
        MyHelper::responeDataJSON($data, '', 200, $messages);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */


    public function searchQuery(Request $request)
    {
        $data = Products::select("name")
            ->where("name", "LIKE", "%{$request->get('query')}%")
            ->get();
        return response()->json($data);
    }

    public function autoCompleteProduct(Request $request)
    {
        $search = $request->search;
        $items = [];
        if ($search != '') {
            $items = Products::where('pro_no', 'like', '%' . $search . '%')
                ->orWhere('name', 'like', '%' . $search . '%')
                ->orWhere('name_kh', 'like', '%' . $search . '%')
                ->orWhere('code_product', 'like', '%' . $search . '%')
                ->select('products.*')
                ->with(['colors:id,name', 'unit:id,name,type'])
                ->limit(25)
                ->get();
        }
        $rows = $items;
        // dd($rows);
        $data = array();
        foreach ($rows as $key => $row) {
            if ($row->unit->type == 'size' && sizeof($row->unit->sizes) > 0 && sizeof($row->colors) > 0) {
                if ($row->unit->sizes) {
                    $data[] = array(
                        'label' => $row->code_product . ' / ' . $row->name,
                        'name'  => $row->name,
                        'code'  => $row->code_product,
                        'type'  => $row->unit->type ?? 'n/a',
                        'id'    => $row->id
                    );
                }
            }
            if ($row->unit->type == 'unique' && sizeof($row->colors) > 0) {
                $data[] = array(
                    'label' => $row->code_product . ' / ' . $row->name,
                    'name'  => $row->name,
                    'code'  => $row->code_product,
                    'type'  => $row->unit->type ?? 'n/a',
                    'id'    => $row->id
                );
            }
        }
        return $data;
    }

    public function searchItemProduct(Request $request)
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
        return view('admin.purchase-return.row_item', compact('result', 'colors', 'index', 'sizes'))->render();
    }

    public function getPurchaseReturnNo()
    {
        $prefix     = "PR";
        $pro_no    = "";
        if ($prefix != "") {
            $pro_no = IdGenerator::generate([
                'table'     => 'purchase_return',
                'length'    => 7,
                'field'     =>  'generate_code',
                'prefix'    => $prefix
            ]);
        }
        return $pro_no;
    }

    private function saveProductDetials($purchaseReturn, $request, $current_user_id)
    {

        foreach ($request->product_ids as $index => $product_id) {
            $products[$product_id]         = [
                'purchase_return_id'    => $purchaseReturn->id,
                'product_id'            => $request->product_ids[$index] ?? 0,
                'color_id'              => $request->color_ids[$index] ?? 0,
                'size_id'               => $request->size_ids[$index] ?? 0,
                'price'                 => $request->unit_prices[$index] ?? 0,
                'qty'                   => $request->qtys[$index] ?? 0,
                'amount'                => $request->total_prices[$index] ?? 0,
                'discount'              => 0,
                'total'                 => $request->total_prices[$index] ?? 0,
                'creator_id'            => $current_user_id,
                'created_at'            => now(),
            ];
        }
        PurchaseDetail::upsert($products);
    }

    public function verify(Request $request)
    {
        $current_user_id = Auth::user()->id;
        $purchaseReturn = PurchaseReturn::find($request->id);
        if($purchaseReturn->status == "Pending") {
            $purchaseReturn->status = "Verified";
            $purchaseReturn->verifier = $current_user_id;
            $purchaseReturn->verify_date = now();
            $purchaseReturn->save();
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
        $purchaseReturn = PurchaseReturn::find($request->id);
        $current_user_id = Auth::user()->id;
        // dd($purchaseReturn)
        if($purchaseReturn->status == "Pending" && $purchaseReturn->verifier == null) {
            $purchaseReturn->delete();
            $purchaseReturn->deleted_by = $current_user_id;
            $purchaseReturn->save();
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
    public function detail(Request $request)
    {
        $purchaseReturn = PurchaseReturn::find($request->id);
        // $gg = $purchaseReturn->with(['creator:id,name', 'supplier:id,name']);
        $purchaseReturnDetail = $purchaseReturn->productDetails;
        // dd($purchaseReturn);
        return view('admin.purchase-return.detail', compact('purchaseReturn', 'purchaseReturnDetail'));
    }



}
