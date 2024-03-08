<?php

namespace App\Http\Controllers;

use App\Category;
use App\Color;
use App\ProductColor;
use App\ProductFile;
use App\Products;
use App\Unit;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // dd($request->all());
        $categories = Category::pluck('name', 'id');
        $units = Unit::pluck('name', 'id');

        $search     = $request->search;
        $date       = date('Y-m-d');
        $date_from  = $request->date_from;
        $date_to    = $request->date_to;
        $category   = $request->category_id;
        $unit      = $request->unit;
        $product   = Products::where(function ($query) use ($search) {
            $query->orWhere('name', 'like', '%'.$search.'%')
            ->orWhere('name_kh', 'like', '%'.$search.'%')
            ->orWhere('price', 'like', '%'.$search.'%')
            ->orWhere('description', 'like', '%'.$search.'%');
        });

        if($date_from != '' && $date_to) {
            $product = $product->whereDate('created_at', '>=', $date_from)
                                 ->whereDate('created_at', '<=', $date_to);
        }
        if($category != null) {
            $product = $product->where('category_id', $category);
        }
        if($unit != null) {
            $product = $product->where('unit_id', $unit);
        }
        $product = $product->orderBy('id', 'DESC')->paginate(20)->withPath('products?date_from='.$date_from.'date_to='.$date_to.'&search='.$search);
        return view('admin.product.index', compact('search', 'request', 'date_from', 'date_to', 'categories', 'units'))->with('product', $product);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $colors = Color::pluck('name', 'id');
        $categories = Category::pluck('name', 'id');
        $units = Unit::pluck('name', 'id');
        return view('admin.product.create', compact('colors', 'categories', 'units'));
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
        $this->validate($request, [
            'name'              => 'required|unique:products,name',
            'price'             => 'required',
            'category_id'       => 'required',
            'unit_id'           => 'required',
            'code_product'      => 'required',
            'color_id'          => 'required',
            'pdfFiles'          => 'array',
            'pdfFiles.*'        => 'mimes:pdf|max:2048', // 2MB = 2048KB
            'image'             => 'max:2048',
        ], [], [
            'name'               =>'Prodcut Name (English)',
            'price'              =>'Price',
            'category_id'        =>'Category Required',
            'unit_id'            =>'Unit Required',
            'code_product'       =>'Product code',
            'color_id'           =>'Color',
            'pdfFiles.*.max:2048'      => 'PDF file Must be Under 2M',
        ]);

        // dd($request->all());

        $check_product = Products::where(function ($query) use ($request) {
            $query->orWhere('name', $request->name);
            if($request->name != '') {
                $query = $query->orWhere('name', $request->name);
            }
            if($request->name_kh != '') {
                $query = $query->orWhere('name_kh', $request->name_kh);
            }
        })->first();
        if($check_product) {
            $notification = array(
                'message'       => "The Product is already exist!",
                'alert-type'    => 'warning'
            );
            return redirect()->back()->with($notification);
        }
        $photo = $request->file('image');


        DB::transaction(function () use ($request, $photo) {
            // IMAGE UPLOAD
            $fiatured_new_name  = '';
            if($request->hasFile('image')) {
                $fiatured_new_name  =time().$photo->getClientOriginalName();
                $photo->move('images/product_image/', $fiatured_new_name);
                $fiatured_new_name  = 'images/product_image/'.$fiatured_new_name;
            }


            $pro_no = $this->getProductNo();
            $productId = Products::create([
                'pro_no'            => $pro_no,
                'name'              => $request->name,
                'name_kh'           => $request->name_kh,
                'price'             => $request->price,
                'description'       => $request->description,
                'source_image'      => $request->source_image,
                'code_product'      => $request->code_product,
                'category_id'       => $request->category_id,
                'unit_id'           => $request->unit_id,
                'image'             => $fiatured_new_name,
                'created_by'        => Auth::user()->id,
                'created_at'        => now()
            ])->id;

            if(sizeof($request->color_id) > 0) {
                foreach ($request->color_id as $key => $color) {
                    ProductColor::create([
                        'product_id'        => $productId,
                        'color_id'          => $color,
                    ]);
                }
            }

            // UPLOAD MULTIPLE PDF
            $i = 0;
            if($request->pdfFiles && count($request->pdfFiles) > 0) {
                foreach ($request->pdfFiles as $req) {
                    $i++;
                    $image = $req;
                    $newimage = $image->getClientOriginalName();
                    $newimage = strtotime(date('Y-m-d H:i:s')).'_'.$i.'_'.str_replace(' ', '_', $newimage);
                    $image->move('images/product_files', $newimage);

                    $filenametostore =  $newimage ;
                    $image_path = './images/product_files' . $filenametostore;

                    ProductFile::create([
                        'product_id' => $productId,
                        'path' => 'images/product_files/'.$filenametostore,
                    ]);
                }
            }

        });

        $notification = array(
            'message'       => "Product Created Success!.",
            'alert-type'    => 'success'
        );
        return redirect()->route('products')->with($notification);

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
        $categories = Category::pluck('name', 'id');
        $units = Unit::pluck('name', 'id');

        $row = Products::find($id);
        $product_color = ProductColor::get();
        $selecteds = ProductColor::where('product_id', $id)->pluck('color_id')->toArray();
        // dd($selecteds);
        $colors = Color::get();
        $pdfs = $row->pdfFiles ? count($row->pdfFiles) : 0;
        // dd($pdf);
        return view('admin.product.edit', compact('row', 'selecteds', 'colors', 'categories', 'units', 'pdfs'));
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
        // dd($request->all());
        $row = Products::find($id);
        $this->validate($request, [
          'name'             => 'required|unique:products,name,'.$id.'NULL,id,deleted_at,NULL',
          'price'            => 'required',
          'category_id'      => 'required',
          'unit_id'          => 'required',
          'code_product'     => 'required',
          'color_id'        => 'required',
          'pdfFiles'          => 'array',
          'pdfFiles.*'        => 'mimes:pdf|max:2048', // 2MB = 2048KB
        ], [], [
          'name'              =>'Prodcut Name (English)',
          'price'             =>'Price',
          'category_id'       =>'Category Required',
          'unit_id'           =>'Unit Required ',
          'code_product'      =>'Product code',
          'color_id'          =>'Color',
          'pdfFiles.*.max:2048'      => 'PDF file Must be Under 2M',
        ]);
        // dd($request->all());

        $fiatured_new_name  = '';
        $photo             = $request->file('image');
        if($request->hasFile('image')) {
            if($row->image) {
                if(file_exists((string)$row->image)) {
                    unlink($row->image);
                }
            }

            $fiatured_new_name = time().$photo->getClientOriginalName();
            $photo->move('images/product_image/', $fiatured_new_name);
            $fiatured_new_name  = 'images/product_image/'.$fiatured_new_name;
        } else {
            $fiatured_new_name = $row->image;
        }

        $pro_no = $this->getProductNo();

        $row->update([
            'pro_no'            => $pro_no,
            'name'              => $request->name,
            'name_kh'           => $request->name_kh,
            'price'             => $request->price,
            'description'       => $request->description,
            'category_id'       => $request->category_id,
            'unit_id'       => $request->unit_id,
            'source_image'      => $request->source_image,
            'code_product'      => $request->code_product,
            'image'             => $fiatured_new_name,
            'updated_by'        => Auth::user()->id,
            'updated_at'        => now()
        ]);

        if(sizeof($request->color_id) > 0) {
            ProductColor::where('product_id', $id)->delete();
            foreach ($request->color_id as $key => $color) {
                ProductColor::create([
                    'product_id'        => $id,
                    'color_id'          => $color,
                ]);
            }
        }



        // UPDATE FILE PDF UPLOAD
        $i = 0;

        if($request->pdfFiles && count($request->pdfFiles) > 0) {
            $pdfs = $row->pdfFiles;
            foreach ($pdfs as $pdf) {
                if (file_exists((string)$pdf->path)) {
                    $pdf->delete();
                    unlink($pdf->path);
                }
            }

            $productPdfs = ProductFile::where('product_id', $id)->get();
            foreach ($productPdfs as $productPdf) {
                $file_path = $productPdf->path;
                File::delete($file_path);
            }

            foreach ($request->pdfFiles as $req) {
                $i++;
                $image = $req;
                $newimage = $image->getClientOriginalName();
                $newimage = strtotime(date('Y-m-d H:i:s')).'_'.$i.'_'.str_replace(' ', '_', $newimage);
                $image->move('images/product_files', $newimage);

                $filenametostore =  $newimage ;
                $image_path = './images/product_files' . $filenametostore;
                ProductFile::create([
                    'product_id' => $id,
                    'path' => 'images/'.$filenametostore,
                ]);
            }
        }



        $notification = array(
            'message'       => "Product Created Success!.",
            'alert-type'    => 'success'
        );

        return redirect()->route('products')->with($notification);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user_id    = Auth::user()->id;
        $row        = Products::find($id);
        $row->delete();
        $row->update([
            'deleted_by' => $user_id,
        ]);
        $notification = array(
            'message'       => "Delete Color Success !",
            'alert-type'    => 'success'
        );
        return redirect()->back()->with($notification);
    }

    public function getProductNo()
    {
        $prefix     = "PRO";
        $pro_no    = "";
        if($prefix!="") {
            $pro_no = IdGenerator::generate([
                'table'     => 'products',
                'length'    => 7,
                'field'     =>  'pro_no',
                'prefix'    => $prefix]);
        }
        return $pro_no;
    }
}
