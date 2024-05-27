<?php

namespace App\Http\Controllers\Backend;

use Exception;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductSize;
use App\Models\ProductColor;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;

class ProductController extends Controller
{
    public $productImageArray = [];
    /**
     * product listing view
     *
     * @return void
     */
    public function listing()
    {
        return view('backend.products.index');
    }

    /**
     * Product create
     *
     * @return void
     */
    public function create()
    {
        $categories = Category::with('sub_category')->orderBy('id', 'desc')->get();
        $brands = Brand::orderBy('id', 'desc')->get();
        $colors = ProductColor::orderBy('id','desc')->get();
        $sizes = ProductSize::orderBy('id','desc')->get();
        return view('backend.products.create', compact('categories', 'brands','colors','sizes'));
    }

     /**
     * Product Store
     *
     * @param Request $request
     * @return void
     */
    public function store(StoreProductRequest $request)
    {
        // dd($request);
        DB::beginTransaction();
        try {
            $cate = Category::find($request->category_id);
            if ($cate && $cate->sub_category->count() > 0) {
                if (!$request->has('subcategory_id')) {
                    return redirect()->back()->with('fail', 'Selected category has sub-categories, Please select a sub_category!');
                }
            }
            $product = new Product();
            $product->name = $request->name;
            $product->price = $request->price;

            $product->category_id = $request->category_id ?? null;
            $product->sub_category_id = $request->input('subcategory_id');
            $product->brand_id = $request->brand_id ?? null;
            $product->description = $request->description;

            $product->english_colors = $request->english_colors ?? [];

            if($request->english_colors){
                foreach($request->english_colors as $en_color){
                    $myanmar_colors[] = ProductColor::select('myanmar_name')->where('english_name',$en_color)->get()->value('myanmar_name');
                }
            }
            $product->myanmar_colors = $myanmar_colors ?? [];


            $product->sizes = $request->sizes ?? [];

            $product->instock = $request->instock;
            $product->save();

            if ($request->hasFile('images')) {
                $this->_createProductImages($product->id, $request->file('images'));
            }

            DB::commit();
            return redirect()->route('product')->with('created', 'Product Created Successfully');
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Product detail
     *
     */
    public function detail(Product $product){
        $product->with('brand','category','images')->first();
        $data = [
            'product'=>$product,
        ];

        return view('backend.products.detail')->with($data);
    }

    /**
   * Create Review Images
   */
  private function _createProductImages($productId, $files)
  {
      foreach ($files as $image) {
          $this->productImageArray[] = [
              'product_id'      => $productId,
              'path'           => $image->store('products'),
              'created_at'     => now(),
              'updated_at'     => now(),
          ];
      }

      ProductImage::insert($this->productImageArray);
  }

    /**
     * Product edit
     *
     * @param StoreProductRequest $request
     * @param [type] $id
     * @return void
     */
    public function edit(Product $product)
    {
        $categories = Category::orderBy('id', 'desc')->get();
        $brands = Brand::orderBy('id', 'desc')->get();
        return view('backend.products.edit', compact('product', 'categories', 'brands'));
    }

    /**
     * Update Product
     *
     * @param [type] $id
     * @param StoreProductRequest $request
     * @return void
     */
    public function update(Product $product, UpdateProductRequest $request)
    {
        if (empty($request->old) && empty($request->images)) {
            return redirect()->back()->with('fail', 'Product Image is required');
        }

        DB::beginTransaction();
        try {
            $cate = Category::find($request->category_id);
            if ($cate && $cate->sub_category->count() > 0) {
                if (!$request->has('subcategory_id')) {
                    return redirect()->back()->with('fail', 'Selected category has sub-categories, Please select a sub_category!');
                }
            }
            $product->name = $request->name;
            $product->price = $request->price;

            $product->category_id = $request->category_id ?? null;
            $product->sub_category_id = $request->input('subcategory_id');
            $product->brand_id = $request->brand_id ?? null;

            $product->english_colors = $request->english_colors ?? [];

            if($request->english_colors){
                foreach($request->english_colors as $en_color){
                    $myanmar_colors[] = ProductColor::select('myanmar_name')->where('english_name',$en_color)->get()->value('myanmar_name');
                }
            }
            $product->myanmar_colors = $myanmar_colors ?? [];

            $product->sizes = $request->sizes ?? [];
            $product->description = $request->description;
            $product->instock = $request->instock;

            $product->update();

            // old image file delete
            if ($request->has('old')) {
                $files = $product->images()->whereNotIn('id', $request->old)->get();## oldimg where not in request old
                if (count($files) > 0) { ## delete oldimg where not in request old
                    foreach ($files as $file) {
                        $oldPath = $file->getRawOriginal('path') ?? '';
                        Storage::delete($oldPath);
                    }

                    $product->images()->whereNotIn('id', $request->old)->delete();
                }
            }

            if ($request->hasFile('images')) {
                $this->_createProductImages($product->id, $request->file('images'));
            }

            DB::commit();
            return redirect()->route('product')->with('updated', 'Product Updated Successfully');
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

     /**
     * Product destroy
     *
     * @param [type] $id
     * @return void
     */
    public function destroy(Product $product)
    {
        $product->update(['status'=> '0']);
        return 'success';
    }

     /**
     * ServerSide
     *
     * @return void
     */
    public function serverSide()
    {
        $product = Product::with('brand','category','image')->active()->orderBy('id','desc');
        return datatables($product)
        ->addColumn('image', function ($each) {
            $image = $each->image;
            return '<img src="'.$image->path.'" class="thumbnail_img"/>';
        })
        ->addColumn('category', function ($each) {
            return $each->category->name ?? '---';
        })
        ->addColumn('sub_category', function ($each) {
            return $each->sub_category->name ?? '---';
        })
        ->addColumn('brand', function ($each) {
            return $each->brand->name ?? '---';
        })
        ->editColumn('price',function($each){
            return number_format($each->price,).' MMK';
        })
        ->editColumn('instock',function($each){
            if($each->instock == 1){
                $instock = '<div class="badge badge-soft-success">instock</div>';
            }else{
                $instock = '<div class="badge badge-soft-danger">out of stock</div>';
            }
            return $instock;
        })
        ->addColumn('action', function ($each) {

            $show_icon = '<a href="'.route('product.detail', $each->id).'" class="detail_btn btn btn-sm btn-info"><i class="ri-eye-fill btn_icon_size"></i></a>';
            $edit_icon = '<a href="'.route('product.edit', $each->id).'" class="btn btn-sm btn-success edit_btn"><i class="mdi mdi-square-edit-outline btn_icon_size"></i></a>';
            $delete_icon = '<a href="#" class="btn btn-sm btn-danger delete_btn" data-id="'.$each->id.'"><i class="mdi mdi-trash-can-outline btn_icon_size"></i></a>';
            return '<div class="action_icon d-flex gap-3">'. $show_icon .$edit_icon. $delete_icon .'</div>';
        })
        ->rawColumns(['category','sub_category', 'instock', 'brand', 'action', 'image'])
        ->toJson();
    }

    /**
     * Product images
     *
     * @return void
     */
    public function images(Product $product)
    {
        $oldImages = [];
        foreach ($product->images as $img) {
            $oldImages[] = [
            'id'  => $img->id,
            'src' => $img->path,
          ];
        }

        return response()->json($oldImages);
    }
}
