@extends('layouts.admin.app')

@section('title',\App\CPU\translate('supplier_details'))

@push('css_or_js')

@endpush

@section('content')

<div class="content container-fluid">
    <div class="page-header">
        <div>
            <h1 class="page-header-title">{{ $supplier->name }}</h1>
        </div>
        <div class="js-nav-scroller hs-nav-scroller-horizontal">
            <ul class="nav nav-tabs page-header-tabs">
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('admin.supplier.view',[$supplier['id']]) }}">{{\App\CPU\translate('details')}}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.supplier.products',[$supplier['id']]) }}">{{\App\CPU\translate('product_list')}}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.supplier.transaction-list',[$supplier['id']]) }}">{{\App\CPU\translate('transaction')}}</a>
                </li>
            </ul>

        </div>
    </div>

    <div class="row m-1">
        <div class="card col-12">
            <div class="card-header">
                <h3>
                    {{\App\CPU\translate('supplier_details')}}
                </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-md-2 mt-2">
                        <img class="w-100"
                            src="{{asset('storage/app/public/supplier')}}/{{ $supplier->image }}"
                            onerror="this.src='{{asset('public/assets/admin/img/160x160/img1.jpg')}}'">
                    </div>
                    <div class="col-12 col-md-5 mt-2">

                        <div>
                            <span>{{\App\CPU\translate('name')}}: {{ $supplier->name }}</span>
                        </div>
                        <div>
                            <span>{{\App\CPU\translate('Phone')}}: {{ $supplier->mobile }}</span>
                        </div>
                        <div>
                            <span>{{\App\CPU\translate('email')}}: {{ $supplier->email }}</span>
                        </div>
                    </div>
                    <div class="col-12 col-md-5 mt-2">
                        <div>
                            <span>{{\App\CPU\translate('state')}}: {{ $supplier->state }}</span>
                        </div>
                        <div>
                            <span>{{\App\CPU\translate('city')}}: {{ $supplier->city }}</span>
                        </div>
                        <div>
                            <span>{{\App\CPU\translate('zip_code')}}: {{ $supplier->zip_code }}</span>
                        </div>
                        <div>
                            <span>{{\App\CPU\translate('address')}}: {{ $supplier->address }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
