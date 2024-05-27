@extends('main')

@section('content')
<div class="row">
    <div class="col-xl-10 offset-xl-1">
        <div class="card my_card">
            <div class="card-header bg-transparent">
                <a href="{{route('product')}}" class="card-title mb-0 d-inline-flex align-items-center create_title">
                    <i class=" ri-arrow-left-s-line mr-3 primary-icon"></i>
                    <span class="create_sub_title">Product ကိုပြုပြင်မည်</span>
                </a>
            </div><!-- end card header -->
            <div class="card-body">
                <div class="row d-flex justify-content-center">
                    <div class="col-xl-12">
                        @if(Session::get('fail'))
                            <div class="alert alert-danger p-3 mb-3 text-center">
                                {{Session::get('fail')}}
                            </div>
                        @endif
                        <form method="POST" action="{{route('product.update', $product->id)}}" id="product_update" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-check form-switch form-switch-md form-switch-primary ms-2 mb-4 d-flex align-items-center">
                                        <input class="form-check-input mb-0" name="instock" type="checkbox" role="switch" id="SwitchCheck7" {{ old('instock',$product->instock) == 1 ? 'checked' : ''}} value="1">
                                        <label class="form-check-label mb-0" for="SwitchCheck7">Instock</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xl-6">
                                    <div class="mb-3">
                                        <label class="form-label mb-3">အမည်</label>
                                        <input type="text" class="form-control" name="name" autocomplete="off" value="{{$product->name}}">
                                    </div>
                                </div>
                                <div class="col-xl-6">
                                    <div class="mb-3">
                                        <label class="form-label mb-3">စျေးနှုန်း</label>
                                        <input type="number" class="form-control" name="price" autocomplete="off" value="{{$product->price}}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-xl-6">
                                    <div class="mb-3">
                                        <label for="category">အမျိူးအစား / Category</label>
                                        <select name="category_id" class="form-select mb-3" aria-label="Default select example" id='category'>
                                            <option selected disabled>အမျိူးအစား ရွေးပါ</option>
                                            @foreach ($categories as $category)
                                                <option value="{{$category->id}}" {{$category->id == $product->category_id ? 'selected' : ''}} data-subcategories="{{ json_encode($category->sub_category) }}">
                                                    {{$category->name}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-xl-6">
                                    <div class="mb-3">
                                        <label for="subcategory">အမျိုးအစား / Sub-category</label>
                                        <select name="subcategory_id" class="form-select mb-3" aria-label="Default select example" id='subcategory'>
                                            <option disabled>အမျိုးအစား ရွေးပါ</option>
                                            @if($product->subcategory_id)
                                                @foreach($selected_category->sub_category as $subcategory)
                                                    <option value="{{$subcategory->id}}" {{$subcategory->id == $product->subcategory_id ? 'selected' : ''}}>
                                                        {{$subcategory->name}}
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xl-6">
                                    <div class="mb-3">
                                        <label for="brand">အမှတ်တံဆိပ် / Brand</label>
                                        <select name="brand_id" class="form-select mb-3" aria-label="Default select example" id='brand'>
                                            <option selected disabled>အမှတ်တံဆိပ် ရွေးပါ</option>
                                            @foreach ($brands as $brand)
                                                <option value="{{$brand->id}}" {{$brand->id == $product->brand_id ? 'selected' : ''}}>
                                                    {{$brand->name}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                                @php
                                    $colors = App\Models\ProductColor::orderby('id','desc')->get();
                                    $sizes = App\Models\ProductSize::orderby('id','desc')->get();
                                @endphp
                                <div class="row">
                                    <div class="col-6">
                                        <label for="">အရောင် / Color </label>
                                        <select class="js-example-basic-multiple form-control" name="english_colors[]" multiple="multiple">
                                            @foreach ($colors as $color)
                                            <option value="{{$color->english_name}}" {{ in_array($color->english_name,$product->english_colors) ? 'selected' : '' }}>
                                                {{$color->english_name}}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-6">
                                        <label for="">Size</label>
                                        <select class="js-example-basic-multiple form-control" name="sizes[]" multiple="multiple">
                                            @foreach ($sizes as $size)
                                            <option value="{{$size->name}}" {{ in_array($size->name,$product->sizes) ? 'selected' : '' }}>
                                                {{$size->name}}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            <div class="mb-5 mt-3">
                                <label for="description" class="form-label">အကြောင်းအရာ / Description</label>
                                <textarea class="form-control" name="description" id="description" rows="8">{{$product->description}}</textarea>
                            </div>
                            <div class="form-group">
                                <label for="images">Images</label>
                                <div class="input-images" id="images"></div>
                            </div>

                            <div class="text-end submit-m-btn">
                                <button type="submit" class="submit-btn">ပြင်ဆင်မှုများကိုသိမ်းမည်</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
    {!! JsValidator::formRequest('App\Http\Requests\UpdateProductRequest', '#product_update') !!}
    <script src="{{ asset('assets/js/image-uploader.min.js') }}"></script>
    <script>
        $(document).ready(function() {
        $('#category').change(function() {
            var selectedCategory = $(this).find('option:selected');
            var subcategories = JSON.parse(selectedCategory.attr('data-subcategories'));
            var subcategorySelect = $('#subcategory');

            // Clear previous options
            subcategorySelect.empty();

            if (subcategories.length === 0) {
                subcategorySelect.append('<option selected disabled>No sub-categories</option>');
            } else {
                subcategorySelect.append('<option disabled>Select a Sub-category</option>');
                $.each(subcategories, function(index, subcategory) {
                    subcategorySelect.append('<option value="' + subcategory.id + '">' + subcategory.name + '</option>');
                });
            }
        });

        // Trigger change event on page load if a category is pre-selected
        $('#category').change();
    });
        $.ajax({
            url: `/product-images/${`{{ $product->id }}`}`
            }).done(function(response) {
            if( response ){
                $('.input-images').imageUploader({
                    preloaded: response,
                    imagesInputName: 'images',
                    preloadedInputName: 'old',
                    maxSize: 2 * 1024 * 1024,
                    maxFiles: 10
                });
            }
        });

        $(document).ready(function() {
             $('.js-example-basic-multiple').select2(
                {
                    width: '100%',
                    placeholder: "Select an Option",
                    allowClear: true
                }
             );
        });
    </script>
@endsection
