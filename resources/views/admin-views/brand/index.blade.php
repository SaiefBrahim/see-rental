@extends('layouts.admin.app')

@section('title',\App\CPU\translate('add_new_brand'))

@push('css_or_js')
    <link rel="stylesheet" href="{{asset('public/assets/admin')}}/css/custom.css"/>
@endpush

@section('content')
<div class="content container-fluid">
        <!-- Page Header -->
        <div class="">
            <div class="row align-items-center mb-3 ">
                <div class="col-sm mb-2 mb-sm-0">
                    <h1 class="page-header-title d-flex align-items-center g-2px text-capitalize">
                        <i class="tio-add-circle-outlined"></i>
                        <span>{{\App\CPU\translate('add_new_brand')}}</span>
                    </h1>
                </div>
            </div>
        </div>
        <!-- End Page Header -->
        <div class="row gx-2 gx-lg-3">
            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <div class="card">
                    <div class="card-body">
                        <form action="{{route('admin.brand.store')}}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-12 col-sm-6">
                                    <div class="form-group">
                                        <label for="">{{\App\CPU\translate('brand_name')}}</label>
                                        <input type="text" name="name" class="form-control" placeholder="{{\App\CPU\translate('brand_name')}}">
                                        <input name="position" value="0" class="d-none">
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6">
                                    <label>{{\App\CPU\translate('image')}}</label><small class="text-danger">* ( {{\App\CPU\translate('ratio_1:1')}}  )</small>
                                    <div class="custom-file">
                                        <input type="file" name="image" id="customFileEg1" class="custom-file-input"
                                            accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*" required>
                                        <label class="custom-file-label" for="customFileEg1">{{\App\CPU\translate('choose_file')}}</label>
                                    </div>
                                </div>
                                <div class="col-12 from_part_2">
                                    <div class="form-group">
                                        <center>
                                            <img class="img-one-bri" id="viewer"
                                                src="{{asset('public/assets/admin/img/400x400/img2.jpg')}}" alt="image"/>
                                        </center>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">{{\App\CPU\translate('submit')}}</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <div class="card">
                    <div class="card-header">
                        <div>
                            <h5>{{ \App\CPU\translate('brand_table')}}<span class="badge badge-soft-dark ml-2">{{$brands->total()}}</span></h5>
                        </div>
                    </div>
                    <!-- Table -->
                    <div class="table-responsive ">
                        <table class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                            <thead class="thead-light">
                            <tr>
                                <th >{{\App\CPU\translate('#')}}</th>
                                <th >{{\App\CPU\translate('image')}}</th>
                                <th >{{\App\CPU\translate('name')}}</th>
                                <th class="w-one-bri">{{\App\CPU\translate('action')}}</th>
                            </tr>

                            </thead>
                            <tbody>
                            @foreach($brands as $key=>$brand)
                                <tr>
                                    <td>{{ $brands->firstItem()+$key }}</td>
                                    <td>
                                        <img class="img-two-bri"
                                        onerror="this.src='{{asset('public/assets/admin/img/160x160/img2.jpg')}}'"
                                        src="{{asset('storage/app/public/brand')}}/{{ $brand->image }}" alt="">
                                    </td>
                                    <td>
                                    <span class="d-block font-size-sm text-body">
                                        {{$brand['name']}}
                                    </span>
                                    </td>
                                    <td>
                                        <a class="btn btn-white mr-1"
                                                   href="{{route('admin.brand.edit',[$brand['id']])}}"><span class="tio-edit"></span></a>
                                        <a class="btn btn-white mr-1" href="javascript:"
                                                   onclick="form_alert('brand-{{$brand['id']}}','Want to delete this Brand?')"><span class="tio-delete"></span></a>
                                                <form action="{{route('admin.brand.delete',[$brand['id']])}}"
                                                      method="post" id="brand-{{$brand['id']}}">
                                                    @csrf @method('delete')
                                                </form>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                        <hr>
                        <table>
                            <tfoot>
                            {!! $brands->links() !!}
                            </tfoot>
                        </table>
                        @if(count($brands)==0)
                            <div class="text-center p-4">
                                <img class="mb-3 w-two-bri" src="{{asset('public/assets/admin')}}/svg/illustrations/sorry.svg" alt="{{\App\CPU\translate('image_description')}}">
                                <p class="mb-0">{{ \App\CPU\translate('No_data_to_show')}}</p>
                            </div>
                        @endif
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
