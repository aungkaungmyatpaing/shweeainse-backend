@extends('main')

@section('content')
<div class="row">
    <div class="col-xl-10 offset-xl-1">
        <div class="card my_card">
            <div class="card-header bg-transparent">
                <a href="{{route('subcategory')}}" class="card-title mb-0 d-inline-flex align-items-center create_title">
                    <i class=" ri-arrow-left-s-line mr-3 primary-icon"></i>
                    <span class="create_sub_title">Category ကိုပြုပြင်မည်</span>
                </a>
            </div><!-- end card header -->
            <div class="card-body">
                <div class="row d-flex justify-content-center">
                    <div class="col-xl-9">
                        <form method="POST" action="{{route('subcategory.update', $subCategory->id)}}" id="sub_category_create" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="upload mb-3">
                                <div class="preview_img">
                                    @if ($subCategory->image)
                                        <img src="{{$subCategory->image}}" alt="" width=150 height=150 alt="">
                                    @endif
                                </div>
                                <div class="round">
                                  <input type="file" id="upload_img" name="image">
                                  <i class ="ri-camera-fill" style = "color: #fff;"></i>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="category">အမျိူးအစား / Category</label>
                                <select name="category_id" class="form-select mb-3" aria-label="Default select example" id='category'>
                                    <option selected disabled>အမျိူးအစား ရွေးပါ</option>
                                    @foreach ($categories as $category)
                                        <option value="{{$category->id}}" {{$category->id == $subCategory->category_id ? 'selected' : ''}}>
                                            {{$category->name}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="employeeName" class="form-label mb-3">အမည်</label>
                                <input type="text" class="form-control" name="name" value="{{$subCategory->name}}">
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
    {!! JsValidator::formRequest('App\Http\Requests\StoreSubCategoryRequest', '#sub_category_create') !!}
    <script>
        $(document).ready(function() {
            $('#upload_img').on('change', function() {
                 let file_length = document.getElementById('upload_img').files.length;
                 if(file_length > 0) {
                     $('.preview_img').html('');
                     for(i = 0; i < file_length ; i++) {
                         $('.preview_img').html('');
                         $('.preview_img').append(`<img src="${URL.createObjectURL(event.target.files[i])}" width=150 height =150/>`)
                     }
                 } else {
                     $('.preview_img').html(`<img src="{{ asset(config('app.companyInfo.logo')) }}" width=150 height=150 alt="">`);
                 }
             })
        })
    </script>
@endsection
