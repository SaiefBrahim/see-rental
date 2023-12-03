@extends('layouts.admin.app')

@section('title',\App\CPU\translate('supplier_list'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="row align-items-center mb-3">
            <div class="col-sm mb-2 mb-sm-0">
                <h1 class="page-header-title d-flex align-items-center g-2px text-capitalize"><i
                        class="tio-filter-list"></i> {{\App\CPU\translate('supplier_list')}}
                    <span class="badge badge-soft-dark ml-2">{{$suppliers->total()}}</span>
                </h1>
            </div>
        </div>
        <!-- End Page Header -->
        <div class="row gx-2 gx-lg-3">
            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <!-- Card -->
                <div class="card">
                    <!-- Header -->
                    <div class="card-header">
                        <div class="row justify-content-between align-items-center flex-grow-1">
                            <div class="col-12 col-md-6 mb-3">
                                <form action="{{url()->current()}}" method="GET">
                                    <!-- Search -->
                                    <div class="input-group input-group-merge input-group-flush">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="tio-search"></i>
                                            </div>
                                        </div>
                                        <input id="datatableSearch_" type="search" name="search" class="form-control"
                                               placeholder="{{\App\CPU\translate('search_by_name_or_phone')}}" aria-label="Search" value="{{ $search }}"  required>
                                        <button type="submit" class="btn btn-primary">{{\App\CPU\translate('search')}} </button>

                                    </div>
                                    <!-- End Search -->
                                </form>
                            </div>
                            <div class="col-12 col-md-6">
                                <a href="{{route('admin.supplier.add')}}" class="btn btn-primary float-right"><i
                                        class="tio-add-circle"></i> {{\App\CPU\translate('add_new_supplier')}}
                                </a>
                            </div>
                        </div>
                    </div>
                    <!-- End Header -->

                    <!-- Table -->
                    <div class="table-responsive datatable-custom">
                        <table class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                            <thead class="thead-light">
                            <tr>
                                <th>{{\App\CPU\translate('#')}}</th>
                                <th>{{\App\CPU\translate('name')}}</th>
                                <th class="hide-div-sl">{{\App\CPU\translate('email')}}</th>
                                <th class="hide-div-sl"> {{ \App\CPU\translate('phone') }}</th>
                                <th>{{ \App\CPU\translate('products') }}</th>
                                <th>{{\App\CPU\translate('action')}}</th>
                            </tr>
                            </thead>

                            <tbody id="set-rows">
                            @foreach($suppliers as $key=>$supplier)
                                <tr>
                                    <td>{{ $suppliers->firstItem()+$key }}</td>
                                    <td>
                                        <a class="text-primary" href="{{ route('admin.supplier.view',[$supplier['id']]) }}">
                                            {{ $supplier->name }}
                                        </a>
                                    </td>
                                    <td class="hide-div-sl">
                                        <a class="text-dark" href="mailto:{{ $supplier['email'] }}" class="text-primary">{{ $supplier['email'] }}</a>
                                    </td>
                                    <td class="hide-div-sl">
                                        <a href="tel:{{$supplier->mobile}}">{{$supplier->mobile}}</a>
                                    </td>
                                    <td>
                                        <a data-toggle="tooltip" class="badge badge-soft-info" href="{{ route('admin.supplier.products',[$supplier['id']]) }}"
                                            title="{{ \App\CPU\translate('product_view') }}">
                                            {{ $supplier->products->count() }}
                                        </a>
                                        <div class="tooltip bs-tooltip-top" role="tooltip">
                                            <div class="arrow"></div>
                                            <div class="tooltip-inner"></div>
                                        </div>
                                    </td>

                                    <td>
                                        <a class="btn btn-white mr-1" href="{{route('admin.supplier.view',[$supplier['id']])}}"><span class="tio-visible"></span></a>
                                        <a class="btn btn-white mr-1"
                                            href="{{route('admin.supplier.edit',[$supplier['id']])}}">
                                            <span class="tio-edit"></span>
                                        </a>
                                        <a class="btn btn-white mr-1" href="javascript:"
                                            onclick="form_alert('supplier-{{$supplier['id']}}','Want to delete this supplier?')"><span class="tio-delete"></span></a>
                                            <form action="{{route('admin.supplier.delete',[$supplier['id']])}}"
                                                    method="post" id="supplier-{{$supplier['id']}}">
                                                @csrf @method('delete')
                                            </form>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                        <div class="page-area">
                            <table>
                                <tfoot class="border-top">
                                {!! $suppliers->links() !!}
                                </tfoot>
                            </table>
                        </div>
                        @if(count($suppliers)==0)
                            <div class="text-center p-4">
                                <img class="mb-3 img-one-sl" src="{{asset('public/assets/admin')}}/svg/illustrations/sorry.svg" alt="{{\App\CPU\translate('Image Description')}}">
                                <p class="mb-0">{{ \App\CPU\translate('No_data_to_show')}}</p>
                            </div>
                        @endif
                    </div>
                    <!-- End Table -->
                </div>
                <!-- End Card -->
            </div>
        </div>
    </div>

@endsection

@push('script_2')

@endpush
