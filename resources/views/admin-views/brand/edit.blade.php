@extends('layouts.admin.app')

@section('title',\App\CPU\translate('update_brand'))

@push('css_or_js')
    <link rel="stylesheet" href="{{asset('public/assets/admin')}}/css/custom.css"/>
@endpush

@section('content')
<div class="content container-fluid">
        <!-- Page Header -->
        <div class="">
            <div class="row align-items-center mb-3">
                <div class="col-sm mb-2 mb-sm-0">
                    <h1 class="page-header-title d-flex align-items-center g-2px text-capitalize">
                        <i class="tio-edit"></i>
                        <span>{{\App\CPU\translate('update_brand')}}</span>
                    </h1>
                </div>
            </div>
        </div>
        <!-- End Page Header -->
        <div class="row gx-2 gx-lg-3">
            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
               <div class="card">
                   <div class="card-body">
                        <form action="{{route('admin.brand.update',[$brand['id']])}}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-12 col-sm-6">
                                    <div class="form-group">
                                        <label for="">{{\App\CPU\translate('brand_name')}}</label>
                                        <input type="text" name="name" class="form-control" value="{{ $brand->name }}">
                                        <input name="position" value="0" class="d-none">
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6">
                                    <label>{{\App\CPU\translate('image')}}</label><small class="text-danger">* ( {{\App\CPU\translate('ratio_1:1')}}  )</small>
                                    <div class="custom-file">
                                        <input type="file" name="image" id="customFileEg1" class="custom-file-input"
                                            accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                        <label class="custom-file-label" for="customFileEg1">{{\App\CPU\translate('choose')}} {{\App\CPU\translate('file')}}</label>
                                    </div>
                                </div>
                                <div class="col-12 from_part_2">
                                    <div class="form-group">
                                        <center>
                                            <img class="img-one-br" id="viewer"
                                                src="{{asset('storage/app/public/brand')}}/{{$brand['image']}}" alt="{{\App\CPU\translate('image')}}"/>
                                        </center>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">{{\App\CPU\translate('submit')}}</button>
                        </form>
                   </div>
               </div>
            </div>

        </div>
    </div>
@endsection

@push('script_2')
    <script src={{asset("public/assets/admin/js/global.js")}}></script>
@endpush
