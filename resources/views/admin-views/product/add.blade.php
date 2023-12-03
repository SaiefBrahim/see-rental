@extends('layouts.admin.app')

@section('title',\App\CPU\translate('add_new_product'))

@push('css_or_js')
    {{-- <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{asset('public/assets/admin/css/tags-input.min.css')}}" rel="stylesheet"> --}}
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="">
            <div class="row align-items-center mb-3">
                <div class="col-sm mb-2 mb-sm-0">
                    <h1 class="page-header-title d-flex align-items-center g-2px text-capitalize">
                        <i class="tio-add-circle-outlined"></i>
                        <span>{{\App\CPU\translate('add_new_product')}}</span>
                    </h1>
                </div>
            </div>
        </div>
        <!-- End Page Header -->
        <div class="row gx-2 gx-lg-3">
            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <div class="card">
                    <div class="card-body">
                        <form action="{{route('admin.product.store')}}" method="post" id="product_form"
                              enctype="multipart/form-data">
                            @csrf

                            <div class="row pl-2">
                                <div class="col-12 col-sm-6">
                                    <div class="form-group">
                                        <label class="input-label"
                                               for="exampleFormControlInput1">{{\App\CPU\translate('name')}}
                                            <span class="input-label-secondary">*</span>
                                        </label>
                                        <input type="text" name="name" class="form-control" value="{{ old('name') }}"
                                               placeholder="{{\App\CPU\translate('product_name')}}" required>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6">
                                    <div class="form-group">
                                        <label class="input-label"
                                               for="exampleFormControlInput1">{{\App\CPU\translate('product_code_SKU')}}
                                            <span class="input-label-secondary">*</span>
                                            <a class="style-one-pro"
                                               onclick="document.getElementById('generate_number').value = getRndInteger()">{{\App\CPU\translate('generate_code')}}</a></label>
                                        <input type="text" minlength="5" id="generate_number" name="product_code"
                                               class="form-control" value="{{ old('product_code') }}"
                                               placeholder="{{\App\CPU\translate('product_code')}}" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row pl-2">
                                <div class="col-12 col-sm-6">
                                    <div class="form-group">
                                        <label class="input-label"
                                               for="exampleFormControlInput1">{{\App\CPU\translate('brand')}}</label>
                                        <select name="brand_id" class="form-control js-select2-custom">
                                            <option value="">---{{\App\CPU\translate('select')}}---</option>
                                            @foreach ($brands as $brand)
                                                <option
                                                    value="{{$brand['id']}}" {{ old('brand_id')==$brand['id']?'selected':''}}>{{$brand['name']}}</option>
                                            @endforeach

                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6">
                                    <div class="form-group">
                                        <label class="input-label"
                                               for="exampleFormControlInput1">{{\App\CPU\translate('quantity')}}
                                            <span class="input-label-secondary">*</span>
                                        </label>
                                        <input type="number" min="1" name="quantity" class="form-control"
                                               value="{{ old('quantity') }}"
                                               placeholder="{{\App\CPU\translate('quantity')}}" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row pl-2">
                                <div class="col-12 col-sm-6">
                                    <div class="form-group">
                                        <label class="input-label"
                                               for="exampleFormControlInput1">{{\App\CPU\translate('unit_type')}}
                                            <span class="input-label-secondary">*</span>
                                        </label>
                                        <select name="unit_type" class="form-control js-select2-custom" required>
                                            <option value="">---{{\App\CPU\translate('select')}}---</option>
                                            @foreach($units as $unit)
                                                <option
                                                    value="{{$unit['id']}}" {{ old('unit_type')==$unit['id']?'selected':''}}>{{$unit['unit_type']}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6">
                                    <div class="form-group">
                                        <label class="input-label"
                                               for="exampleFormControlInput1">{{\App\CPU\translate('unit_value')}}
                                            <span class="input-label-secondary">*</span>
                                        </label>
                                        <input type="number" min="0" step="0.01" name="unit_value" class="form-control"
                                               value="{{ old('unit_value') }}"
                                               placeholder="{{\App\CPU\translate('unit_value')}}" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row pl-2">
                                <div class="col-12 col-sm-6">
                                    <div class="form-group">
                                        <label class="input-label"
                                               for="exampleFormControlSelect1">{{\App\CPU\translate('category')}}<span
                                                class="input-label-secondary">*</span></label>
                                        <select name="category_id" class="form-control js-select2-custom"
                                                onchange="getRequest('{{url('/')}}/admin/product/get-categories?parent_id='+this.value,'sub-categories')"
                                                required>
                                            <option value="">---{{\App\CPU\translate('select')}}---</option>
                                            @foreach($categories as $category)
                                                <option
                                                    value="{{$category['id']}}" {{ old('category_id')==$category['id']?'selected':''}}>{{$category['name']}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6">
                                    <div class="form-group">
                                        <label class="input-label"
                                               for="exampleFormControlSelect1">{{\App\CPU\translate('sub_category')}}
                                            <span
                                                class="input-label-secondary"></span></label>
                                        <select name="sub_category_id" id="sub-categories"
                                                class="form-control js-select2-custom"
                                                onchange="getRequest('{{url('/')}}/admin/product/get-categories?parent_id='+this.value,'sub-sub-categories')">

                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row pl-2">
                                <div class="col-12 col-sm-6">
                                    <div class="form-group">
                                        <label class="input-label"
                                               for="exampleFormControlInput1">{{\App\CPU\translate('selling_price')}}
                                            <span class="input-label-secondary">*</span>
                                        </label>
                                        <input type="number" step="0.01" name="selling_price" class="form-control"
                                               value="{{ old('selling_price') }}"
                                               placeholder="{{\App\CPU\translate('selling_price')}}" required>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6">
                                    <div class="form-group">
                                        <label class="input-label"
                                               for="exampleFormControlInput1">{{\App\CPU\translate('purchase_price')}}
                                            <span class="input-label-secondary">*</span>
                                        </label>
                                        <input type="number" step="0.01" name="purchase_price" class="form-control"
                                               value="{{ old('purchase_price') }}"
                                               placeholder="{{\App\CPU\translate('purchase_price')}}" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row pl-2">
                                <div class="col-12 col-sm-6">
                                    <div class="form-group">
                                        <label class="input-label"
                                               for="exampleFormControlInput1">{{\App\CPU\translate('discount_type')}}</label>
                                        <select onchange="discount_option(this);" name="discount_type"
                                                class="form-control js-select2-custom">
                                            <option
                                                value="percent" {{ old('discount_type')=='percent'?'selected':''}}>{{\App\CPU\translate('percent')}}</option>
                                            <option
                                                value="amount" {{ old('discount_type')=='amount'?'selected':''}}>{{\App\CPU\translate('amount')}}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6">
                                    <div class="form-group">
                                        <label id="percent"
                                               class="input-label">{{\App\CPU\translate('discount_percent')}}
                                            (%)</label>
                                        <label id="amount"
                                               class="input-label d-none">{{\App\CPU\translate('discount_amount')}}</label>
                                        <input type="number" min="0" name="discount" class="form-control"
                                               value="{{ old('discount') }}"
                                               placeholder="{{\App\CPU\translate('discount')}}">
                                    </div>
                                </div>

                            </div>
                            <div class="row pl-2">
                                <div class="col-12 col-sm-6">
                                    <div class="form-group">
                                        <label class="input-label"
                                               for="exampleFormControlInput1">{{\App\CPU\translate('tax_in_percent')}}
                                            (%)</label>
                                        <input type="number" min="0" name="tax" class="form-control"
                                               value="{{ old('tax') }}" placeholder="{{\App\CPU\translate('tax')}}">
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6">
                                    <div class="form-group">
                                        <label class="input-label"
                                               for="exampleFormControlInput1">{{\App\CPU\translate('select_supplier')}}</label>
                                        <select class="form-control js-select2-custom" name="supplier_id"
                                                id="supplier_id">
                                            <option value="">---{{\App\CPU\translate('select')}}---</option>
                                            @foreach ($suppliers as $supplier)
                                                <option
                                                    value="{{$supplier['id']}}" {{ old('supplier_id')==$supplier['id']?'selected':''}}>{{$supplier['name']}}
                                                    ({{ $supplier['mobile'] }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row pl-2">
                                <div class="col-12 col-sm-12">
                                    <label>{{\App\CPU\translate('image')}}</label>
                                    <div class="custom-file">
                                        <input type="file" name="image" id="customFileEg1" class="custom-file-input"
                                               accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                        <label class="custom-file-label"
                                               for="customFileEg1">{{\App\CPU\translate('choose')}} {{\App\CPU\translate('file')}}</label>
                                    </div>
                                    <div class="form-group my-4">
                                        <center>
                                            <img class="style-two-pro" id="viewer"
                                                 src="{{asset('public/assets/admin/img/400x400/img2.jpg')}}"
                                                 alt="image"/>
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
