@extends('layouts.admin.app')

@section('title',\App\CPU\translate('supplier_product_list'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
                    <a class="nav-link" href="{{ route('admin.supplier.view',[$supplier['id']]) }}">{{\App\CPU\translate('details')}}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('admin.supplier.products',[$supplier['id']]) }}">{{\App\CPU\translate('product_list')}}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.supplier.transaction-list',[$supplier['id']]) }}">{{\App\CPU\translate('transaction')}}</a>
                </li>
            </ul>

        </div>
    </div>
    <!-- Page Header -->

        <div class="row align-items-center mt-3 mb-3">
            <div class="col-sm  mb-sm-0">
                <h1 class="page-header-title"><i
                        class="tio-filter-list"></i> {{\App\CPU\translate('products_list')}}
                    <span class="badge badge-soft-dark ml-2">{{$products->total()}}</span>
                </h1>
            </div>
            <div class="col-md-4">
                <a href="{{route('admin.product.add')}}" class="btn btn-primary float-right">
                    <i class="tio-add-circle-outlined"></i>  {{\App\CPU\translate('product')}}
                </a>
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
                        <div class="col-md-5  mb-lg-0 mt-2">
                            <form action="{{url()->current()}}" method="GET">
                                <!-- Search -->
                                <div class="input-group input-group-merge input-group-flush">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="tio-search"></i>
                                        </div>
                                    </div>
                                    <input id="datatableSearch_" type="search" name="search" class="form-control"
                                            placeholder="{{\App\CPU\translate('search_by_product_code_or_name')}}" aria-label="{{\App\CPU\translate('Search')}}" value="{{ $search }}"  required>
                                    <button type="submit" class="btn btn-primary">{{\App\CPU\translate('search')}}</button>

                                </div>
                                <!-- End Search -->
                            </form>
                        </div>
                        <div class="col-md-4 mt-2">
                            <select name="qty_ordr_sort" class="form-control" onchange="location.href='{{ url('/') }}/admin/supplier/products/{{ $supplier->id }}?sort_oqrderQty='+this.value">
                                <option value="default" {{ $sort_oqrderQty== "default"?'selected':''}}>{{\App\CPU\translate('default_sort')}}</option>
                                <option value="quantity_asc" {{ $sort_oqrderQty== "quantity_asc"?'selected':''}}>{{\App\CPU\translate('quantity_sort_by_(low_to_high)')}}</option>
                                <option value="quantity_desc" {{ $sort_oqrderQty== "quantity_desc"?'selected':''}}>{{\App\CPU\translate('quantity_sort_by_(high_to_low)')}}</option>
                                <option value="order_asc" {{ $sort_oqrderQty== "order_asc"?'selected':''}}>{{\App\CPU\translate('order_sort_by_(low_to_high)')}}</option>
                                <option value="order_desc" {{ $sort_oqrderQty== "order_desc"?'selected':''}}>{{\App\CPU\translate('order_sort_by_(high_to_low)')}}</option>
                            </select>
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
                            <th>{{\App\CPU\translate('image')}}</th>
                            <th >{{\App\CPU\translate('name')}}</th>
                            <th>{{\App\CPU\translate('product_code')}}</th>
                            <th>{{\App\CPU\translate('quantity')}}</th>
                            <th>{{\App\CPU\translate('purchase_price')}}</th>
                            <th>{{\App\CPU\translate('selling_price')}}</th>
                            <th>{{ \App\CPU\translate('orders') }}</th>
                            <th>{{\App\CPU\translate('action')}}</th>
                        </tr>
                        </thead>

                        <tbody id="set-rows">
                        @foreach($products as $key=>$product)
                            <tr>
                                <td>{{$products->firstitem()+$key}}</td>
                                <td>
                                        <img
                                            src="{{asset('storage/app/public/product')}}/{{$product['image']}}"
                                            class="img-one-spl"
                                            onerror="this.src='{{asset('public/assets/admin/img/160x160/img2.jpg')}}'">
                                </td>
                                <td>
                                    <span class="d-block font-size-sm text-body">
                                            <a href="#">
                                            {{substr($product['name'],0,20)}}{{strlen($product['name'])>20?'...':''}}
                                            </a>
                                    </span>
                                </td>

                                <td>{{ $product['product_code'] }}</td>
                                <td>
                                    {{ $product['quantity'] }}
                                    <button class="btn btn-sm" id="{{ $product->id }}" onclick="update_quantity({{ $product->id }})" type="button" data-toggle="modal" data-target="#update-quantity">
                                        <i class="tio-add-circle"></i>

                                    </button>
                                </td>
                                <td>{{$product['purchase_price'] ." ".\App\CPU\Helpers::currency_symbol()}}</td>
                                <td>{{$product['selling_price'] ." ".\App\CPU\Helpers::currency_symbol()}}</td>
                                <td>{{ $product->order_count??0 }}</td>
                                <td>
                                    <a class="tio-edit pr-2"
                                                href="{{route('admin.product.edit',[$product['id']])}}"></a>
                                            <a class="tio-delete" href="javascript:"
                                                onclick="form_alert('product-{{$product['id']}}','Want to delete this Product ?')"></a>
                                            <form action="{{route('admin.product.delete',[$product['id']])}}"
                                                    method="post" id="product-{{$product['id']}}">
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
                            {!! $products->links() !!}
                            </tfoot>
                        </table>
                    </div>
                    @if(count($products)==0)
                        <div class="text-center p-4">
                            <img class="mb-3 img-two-spl" src="{{asset('public/assets/admin')}}/svg/illustrations/sorry.svg" alt="Image Description">
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

<div class="modal fade" id="update-quantity" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{\App\CPU\translate('update_product_quantity')}} <br>
                    <span class="text-danger">({{\App\CPU\translate('to_decrease_product_quantity_use_minus_before_number._Ex: -10')}} )</span>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{route('admin.stock.update-quantity')}}" method="post" class="row">
                    @csrf
                    <div class="form-group col-sm-12">
                        <label for="">{{\App\CPU\translate('quantity')}}</label>
                        <input type="number" class="form-control" name="quantity" required>
                        <input type="hidden" id="product_id" name="id" value="{{ $product['id']??0 }}">
                    </div>

                    <div class="form-group col-sm-12">
                        <button class="btn btn-sm btn-primary" type="submit">{{\App\CPU\translate('submit')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script_2')
    <script src={{asset("public/assets/admin/js/global.js")}}></script>
@endpush
