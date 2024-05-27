@extends('main')

@section('content')
<div class="row">
    <div class="col-xl-10 offset-xl-1">
        <div class="card my_card">
            <div class="card-header bg-transparent">
                <a href="{{route('product')}}" class="card-title mb-0 d-inline-flex align-items-center create_title">
                    <i class=" ri-arrow-left-s-line mr-3 primary-icon"></i>
                    <span class="create_sub_title">Product အသစ်ဖန်တီးမည်</span>
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
                        <form method="POST" action="{{route('product.store')}}" id="product_create" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-check form-switch form-switch-md form-switch-primary ms-2 mb-4 d-flex align-items-center">
                                        <input class="form-check-input mb-0" name="instock" type="checkbox" role="switch" id="SwitchCheck7" checked value="1">
                                        <label class="form-check-label mb-0" for="SwitchCheck7">Instock</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xl-6">
                                    <div class="mb-4">
                                        <label class="form-label">အမည်</label>
                                        <input type="text" class="form-control" name="name" autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-xl-6">
                                    <div class="mb-4">
                                        <label class="form-label">စျေးနှုန်း</label>
                                        <input type="number" class="form-control" name="price" autocomplete="off">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                {{-- <div class="col-xl-6">
                                    <div class="mb-4">
                                        <label for="category">အမျိူးအစား / Category</label>
                                        <select name="category_id" class="form-control" aria-label="Default select example" id='category'>
                                            <option selected disabled>Category ရွေးပါ</option>
                                            @foreach ($categories as $category)
                                                <option value="{{$category->id}}">
                                                    {{$category->name}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div> --}}
                                <div class="col-xl-6">
                                    <div class="mb-4">
                                        <label for="category">အမျိူးအစား / Category</label>
                                        <select name="category_id" class="form-control" aria-label="Default select example" id='category'>
                                            <option selected disabled>Category ရွေးပါ</option>
                                            @foreach ($categories as $category)
                                                <option value="{{$category->id}}" data-subcategories="{{ json_encode($category->sub_category) }}">
                                                    {{$category->name}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-xl-6">
                                    <div class="mb-4">
                                        <label for="subcategory">အမျိုးအစား / Sub-category</label>
                                        <select name="subcategory_id" class="form-control" aria-label="Default select example" id='subcategory'>
                                            <option selected disabled>Select a Category first</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-xl-6">
                                    <div class="mb-4">
                                        <label for="brand">အမှတ်တံဆိပ် / Brand</label>
                                        <select name="brand_id" class="form-control" aria-label="Default select example" id='brand'>
                                            <option selected disabled>Brand ရွေးပါ</option>
                                            @foreach ($brands as $brand)
                                                <option value="{{$brand->id}}">
                                                    {{$brand->name}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-6">
                                    <div class="mb-4">
                                        <label for="" class="form-label">အရောင် / Color</label>
                                            <select class="js-example-basic-multiple form-control" name="english_colors[]" multiple="multiple">
                                                @foreach ($colors as $color)
                                                <option value="{{$color->english_name}}">
                                                    {{$color->english_name}}
                                                </option>
                                                @endforeach
                                            </select>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="mb-4">
                                        <label for="">Size</label>
                                        <select class="js-example-basic-multiple form-control" name="sizes[]" multiple="multiple">
                                            @foreach ($sizes as $size)
                                            <option value="{{$size->name}}">
                                                {{$size->name}}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-4">
                                <label for="description" class="form-label">အကြောင်းအရာ / Description</label>
                                <textarea class="form-control" name="description" id="description" rows="8"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="images">Images</label>
                                <div class="input-images" id="images"></div>
                            </div>

                            <div class="text-end submit-m-btn">
                                <button type="submit" class="submit-btn">Product အသစ်ပြုလုပ်မည်</button>
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
    {!! JsValidator::formRequest('App\Http\Requests\StoreProductRequest', '#product_create') !!}
    <script src="{{ asset('assets/js/image-uploader.min.js') }}"></script>
    <script>
        $(".input-images").imageUploader({
            maxSize: 2 * 1024 * 1024,
            maxFiles: 10,
        });
        $(document).ready(function() {
             $('.js-example-basic-multiple').select2(
                {
                    width: '100%',
                    placeholder: "Select an Option",
                    allowClear: true
                }
             );

            $('#category').change(function() {
                var selectedCategory = $(this).find('option:selected');
                var subcategories = JSON.parse(selectedCategory.attr('data-subcategories'));
                var subcategorySelect = $('#subcategory');

                // Clear previous options
                subcategorySelect.empty();

                if (subcategories.length === 0) {
                    subcategorySelect.append('<option selected disabled>No sub-categories</option>');
                } else {
                    subcategorySelect.append('<option selected disabled>Sub-category ရွေးပါ</option>');
                    $.each(subcategories, function(index, subcategory) {
                        subcategorySelect.append('<option value="' + subcategory.id + '">' + subcategory.name + '</option>');
                    });
                }
            });
        });
    </script>
@endsection
