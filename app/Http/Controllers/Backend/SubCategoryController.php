<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSubCategoryRequest;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SubCategoryController extends Controller
{
    public function index()
    {
        return view('backend.sub-categories.index');
    }

    public function create()
    {
        $categories = Category::orderBy('id', 'desc')->get();
        return view('backend.sub-categories.create', compact('categories'));
    }

    public function store(StoreSubCategoryRequest $request)
    {
        $subcategory = new SubCategory();
        $subcategory->name = $request->name;
        $subcategory->category_id = $request->category_id;
        if ($request->hasFile('image')) {
        $subcategory->image = $request->file('image')->store('sub-categories');
        }
        $subcategory->save();

        return redirect()->route('subcategory')->with('created', 'SubCategory created Successfully');
    }

    public function edit(SubCategory $subCategory)
    {
        $categories = Category::orderBy('id', 'desc')->get();
        return view('backend.sub-categories.edit', compact('categories','subCategory'));

    }

    public function update(StoreSubCategoryRequest $request, SubCategory $subCategory)
    {
        $subCategory->name = $request->name;
        $subCategory->category_id = $request->category_id;
        if ($request->hasFile('image')) {
            $oldImage = $subCategory->getRawOriginal('image') ?? '';
            Storage::delete($oldImage);
            $subCategory->image = $request->file('image')->store('sub-categories');
        }
        $subCategory->update();

        return redirect()->route('subcategory')->with('updated', 'SubCategory Updated Successfully');
    }

    public function destroy(SubCategory $subCategory)
    {
        $oldImage = $subCategory->getRawOriginal('image') ?? '';
        Storage::delete($oldImage);

        $subCategory->delete();

        return 'success';
    }

    public function serverSide()
    {
        $subcategory = SubCategory::with('category')->withCount('product')->orderBy('id','desc');
        return datatables($subcategory)
        ->addColumn('category', function ($each) {
            return $each->category->name ?? '---';
        })
        ->addColumn('image', function ($each) {
            return '<img src="'.$each->image.'" class="thumbnail_img"/>';
        })
        ->addColumn('action', function ($each) {
            $edit_icon = '<a href="'.route('subcategory.edit', $each->id).'" class="btn btn-sm btn-success mr-3 edit_btn"><i class="mdi mdi-square-edit-outline btn_icon_size"></i></a>';
            $delete_icon = '<a href="#" class="btn btn-sm btn-danger delete_btn" data-id="'.$each->id.'"><i class="mdi mdi-trash-can-outline btn_icon_size"></i></a>';

            return '<div class="action_icon">'.$edit_icon. $delete_icon .'</div>';
        })
        ->rawColumns(['image', 'action','category'])
        ->toJson();
    }
}
