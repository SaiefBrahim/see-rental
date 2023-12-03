@extends('layouts.admin.app')

@section('title',\App\CPU\translate('category_update'))

@push('css_or_js')
    <link rel="stylesheet" href="{{asset('public/assets/admin')}}/css/custom.css"/>
@endpush

@section('content')
<div class="content container-fluid">
        <!-- Page Header -->
        <div class="">
            <div class="row align-items-center mb-3">
                <div class="col-sm mb-2 mb-sm-0">
                    <h1 class="page-header-title text-capitalize"><i class="tio-edit"></i> {{\App\CPU\translate('category_update')}}</h1>
                </div>
            </div>
        </div>
        <!-- End Page Header -->
        <div class="row gx-2 gx-lg-3">
            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <div class="card">
                    <div class="card-body">
                        <form action="{{route('admin.category.update',[$category['id']])}}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-12 col-sm-6">
                                    <div class="form-group lang_form">
                                        <label class="input-label" for="exampleFormControlInput1">{{\App\CPU\translate('name')}} </label>
                                        <input type="text" name="name" value="{{$category['name']}}" class="form-control" placeholder="{{\App\CPU\translate('new_category')}}" required>
                                    </div>


                                    <input name="position" value="0" class="d-none">
                                </div>
                                @if ($category['parent_id'] == 0)
                                    <div class="col-12 col-sm-6 from_part_2">
                                        <div class="form-group">
                                            <label>{{\App\CPU\translate('image')}}</label><small class="text-danger">* ( {{\App\CPU\translate('ratio_1:1')}}  )</small>
                                            <div class="custom-file">
                                                <input type="file" name="image" id="customFileEg1" class="custom-file-input"
                                                    accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                                <label class="custom-file-label" for="customFileEg1">{{\App\CPU\translate('choose')}} {{\App\CPU\translate('file')}}</label>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="col-12 from_part_2">
                                        <center>
                                            <img class="img-one-catu" id="viewer"
                                            onerror="this.src='{{asset('public/assets/admin/img/400x400/img2.jpg')}}'"
                                                src="{{asset('storage/app/public/category')}}/{{$category['image']}}" alt=""/>
                                        </center>
                                    </div>
                                @endif
                            </div>
                            <button type="submit" class="btn btn-primary">{{\App\CPU\translate('update')}}</button>
                        </form>
                    </div>
                </div>
            </div>
            <!-- End Table -->
        </div>
    </div>
@endsection

@push('script_2')
    <script src={{asset("public/assets/admin/js/global.js")}}></script>
@endpush
