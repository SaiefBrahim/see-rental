@extends('layouts.admin.app')

@section('title',\App\CPU\translate('update_customer'))

@push('css_or_js')
    <link rel="stylesheet" href="{{asset('public/assets/admin')}}/css/custom.css"/>
@endpush

@section('content')
<div class="content container-fluid">
        <!-- Page Header -->
    <div class="row align-items-center mb-3">
        <div class="col-sm mb-2 mb-sm-0">
            <h1 class="page-header-title text-capitalize"><i
                    class="tio-edit"></i> {{\App\CPU\translate('update_customer')}}
            </h1>
        </div>
    </div>
        <!-- End Page Header -->
        <div class="row gx-2 gx-lg-3">
            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <div class="card">
                    <div class="card-body">
                        <form action="{{route('admin.customer.update',[$customer->id])}}" method="post" id="product_form"
                            enctype="multipart/form-data"  >
                            @csrf
                            <input type="hidden"   class="form-control" name="balance" min="0" step="0.01" value="{{ $customer->balance }}">
                                <div class="row pl-2" >
                                    <div class="col-12 col-sm-6">
                                        <div class="form-group">
                                            <label class="input-label" >{{\App\CPU\translate('customer_name')}} <span
                                                    class="input-label-secondary text-danger">*</span></label>
                                            <input type="text" name="name" class="form-control" value="{{ $customer->name }}"  placeholder="{{\App\CPU\translate('customer_name')}}" required>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6">
                                        <div class="form-group">
                                            <label class="input-label">{{\App\CPU\translate('mobile_no')}} <span
                                                    class="input-label-secondary text-danger">*</span></label>
                                            <input type="number" id="mobile" name="mobile" class="form-control" value="{{ $customer->mobile }}"  placeholder="{{\App\CPU\translate('mobile_no')}}" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row pl-2" >
                                    <div class="col-12 col-sm-6">
                                        <div class="form-group">
                                            <label class="input-label" >{{\App\CPU\translate('email')}}</label>
                                            <input type="email" name="email" class="form-control" value="{{ $customer->email }}"  placeholder="{{\App\CPU\translate('Ex_:_ex@example.com')}}" >
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6">
                                        <div class="form-group">
                                            <label class="input-label" >{{\App\CPU\translate('state')}}</label>
                                            <input type="text" name="state" class="form-control" value="{{ $customer->state }}"  placeholder="{{\App\CPU\translate('state')}}" >
                                        </div>
                                    </div>
                                </div>
                                <div class="row pl-2" >

                                    <div class="col-12 col-sm-6">
                                        <div class="form-group">
                                            <label class="input-label">{{\App\CPU\translate('city')}} </label>
                                            <input type="text"  name="city" class="form-control" value="{{ $customer->city }}"  placeholder="{{\App\CPU\translate('city')}}" >
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6">
                                        <div class="form-group">
                                            <label class="input-label">{{\App\CPU\translate('zip_code')}} </label>
                                            <input type="text"  name="zip_code" class="form-control" value="{{ $customer->zip_code }}"  placeholder="{{\App\CPU\translate('zip_code')}}" >
                                        </div>
                                    </div>
                                </div>
                                <div class="row pl-2" >

                                    <div class="col-12 col-sm-6">
                                        <div class="form-group">
                                            <label class="input-label">{{\App\CPU\translate('address')}} </label>
                                            <input type="text"  name="address" class="form-control" value="{{ $customer->address }}"  placeholder="{{\App\CPU\translate('address')}}" >
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6">
                                        <label>{{\App\CPU\translate('image')}}</label><small> ( {{\App\CPU\translate('ratio_1:1')}}  )( {{\App\CPU\translate('optional')}}  )</small>
                                        <div class="custom-file">
                                            <input type="file" name="image" id="customFileEg1" class="custom-file-input"
                                                accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*" >
                                            <label class="custom-file-label" for="customFileEg1">{{\App\CPU\translate('choose_file')}}</label>
                                        </div>
                                        <div class="form-group my-4">
                                            <center>
                                                <img class="img-one-cusu" id="viewer"
                                                    onerror="this.src='{{asset('public/assets/admin/img/400x400/img2.jpg')}}'"
                                                src="{{asset('storage/app/public/customer')}}/{{$customer['image']}}" alt="{{\App\CPU\translate('image')}}"/>
                                            </center>
                                        </div>
                                    </div>
                                </div>
                            <button type="submit" class="btn btn-primary">{{\App\CPU\translate('update')}}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('script_2')
    <script>
        "use strict";
        $(document).on('ready', function () {
            @php($country=$customer->country)
            $("#country option[value='{{$country}}']").attr('selected', 'selected').change();
        })
    </script>
    <script src={{asset("public/assets/admin/js/global.js")}}></script>
@endpush
