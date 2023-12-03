@extends('layouts.admin.app')

@section('title',\App\CPU\translate('add_new_sub_category'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{asset('public/assets/admin')}}/css/custom.css"/>
@endpush

@section('content')
<div class="content container-fluid">
    <!-- Page Header -->
    <div class="">
        <div class="row align-items-center mb-3">
            <div class="col-sm mb-2 mb-sm-0">
                <h1 class="page-header-title d-flex align-items-center g-2px text-capitalize">
                    <i class="tio-add-circle-outlined text-capitalize"></i>
                    <span>{{\App\CPU\translate('add_new_sub_category')}}</span>
                </h1>
            </div>
        </div>
    </div>
    <!-- End Page Header -->
        <div class="row gx-2 gx-lg-3">
            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <div class="card">
                    <div class="card-body">
                        <form action="{{route('admin.category.store')}}" method="post">
                            @csrf
                            <div class="row">
                                <div class="col-12 col-sm-6">
                                    <div class="form-group lang_form" >
                                        <label class="input-label" for="exampleFormControlInput1">{{\App\CPU\translate('sub_category')}} {{\App\CPU\translate('name')}}</label>
                                        <input type="text" name="name" class="form-control" placeholder="{{\App\CPU\translate('add_new_sub_category')}}" required>
                                    </div>

                                    <input name="position" value="1" class="d-none">
                                </div>
                                <div class="col-12 col-sm-6">
                                    <div class="form-group">
                                        <label class="input-label"
                                            for="exampleFormControlSelect1">{{\App\CPU\translate('main')}} {{\App\CPU\translate('category')}}
                                            <span class="input-label-secondary">*</span></label>
                                        <select id="exampleFormControlSelect1" name="parent_id" class="form-control" required>
                                            <option value="">---{{\App\CPU\translate('select')}}---</option>
                                            @foreach(\App\Models\Category::where(['position'=>0])->get() as $category)
                                                <option value="{{$category['id']}}">{{$category['name']}}</option>
                                            @endforeach
                                        </select>
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
                        <div class="w-100">
                            <div class="row">
                                <div class="col-12 col-sm-4 col-md-6 col-lg-7 col-xl-7">
                                    <h5>{{ \App\CPU\translate('sub_category_table')}}
                                        <span class="badge badge-soft-dark">{{$categories->total()}}</span>
                                    </h5>
                                </div>
                                <div class=" col-12 col-sm-8 col-md-6 col-lg-5 col-xl-5">
                                    <!-- Search -->
                                    <form action="{{ url()->current() }}" method="GET">
                                        <div class="input-group input-group-merge input-group-flush">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <i class="tio-search"></i>
                                                </div>
                                            </div>
                                            <input id="datatableSearch_" type="search" name="search" class="form-control"
                                                   placeholder="Search  Sub Category" aria-label="Search orders" value="{{ $search }}" required>
                                            <button type="submit" class="btn btn-primary">{{\App\CPU\translate('search')}}</button>
                                        </div>
                                    </form>
                                    <!-- End Search -->
                                </div>
                            </div>
                        </div>

                    </div>
                    <!-- Table -->
                    <div class="table-responsive">
                        <table class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                            <thead class="thead-light">
                            <tr>
                                <th>{{\App\CPU\translate('#')}}</th>
                                <th>{{\App\CPU\translate('main')}} {{\App\CPU\translate('category')}}</th>
                                <th>{{\App\CPU\translate('sub_category')}}</th>
                                <th>{{\App\CPU\translate('action')}}</th>
                            </tr>

                            </thead>

                            <tbody id="set-rows">
                            @foreach($categories as $key=>$category)
                                <tr>
                                    <td>{{$categories->firstitem()+$key}}</td>
                                    <td>
                                        <span class="d-block font-size-sm text-body">
                                            {{$category->parent['name']}}
                                        </span>
                                    </td>

                                    <td>
                                        <span class="d-block font-size-sm text-body">
                                            {{$category['name']}}
                                        </span>
                                    </td>
                                    <td>
                                        <a class="btn btn-white mr-1"
                                                   href="{{route('admin.category.sub-edit',[$category['id']])}}"><span class="tio-edit"></span></a>
                                                <a class="btn btn-white mr-1" href="javascript:"
                                                   onclick="form_alert('category-{{$category['id']}}','Want to delete this Sub Category ?')">
                                                    <span class="tio-delete"></span>
                                                </a>
                                                <form action="{{route('admin.category.delete',[$category['id']])}}"
                                                      method="post" id="category-{{$category['id']}}">
                                                    @csrf @method('delete')
                                                </form>

                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <hr>
                        <div class="page-area">
                            <table>
                                <tfoot>
                                {!! $categories->links() !!}
                                </tfoot>
                            </table>
                        </div>
                        @if(count($categories)==0)
                            <div class="text-center p-4">
                                <img class="mb-3 w-one-carsi" src="{{asset('public/assets/admin')}}/svg/illustrations/sorry.svg" alt="Image Description">
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
