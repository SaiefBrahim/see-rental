@extends('layouts.admin.app')

@section('title', $product->name .' barcode '. date("Y/m/d"))

@push('css_or_js')
    <link rel="stylesheet" href="{{asset('public/assets/admin')}}/css/barcode.css"/>
@endpush

@section('content')
    <div class="row m-2 show-div">
        <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive-lg">
                        <table
                            class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                            <thead class="thead-light">
                            <tr>
                                <th>{{\App\CPU\translate('code')}}</th>
                                <th>{{\App\CPU\translate('name')}}</th>
                                <th>{{\App\CPU\translate('quantity')}}</th>
                                <th>{{\App\CPU\translate('action')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <form action="{{ url()->current() }}" method="GET">

                                    <th>{{ $product->product_code }}</th>
                                    <th>{{ Str::limit($product->name,30) }}</th>
                                    <th>
                                        <input type="number" name="limit" value="{{ $limit }}"><br>
                                        <span class="text-danger">{{\App\CPU\translate('maximum_quantity_270')}}</span>
                                    </th>

                                    <th>
                                        <button class="btn btn-info"
                                                type="submit">{{\App\CPU\translate('generate_bercode')}}</button>

                                        <a class="btn btn-danger"
                                           href="{{ route('admin.product.barcode-generate',[$product['id']]) }}">{{\App\CPU\translate('reset')}}</a>
                                        <button type="button" id="print_bar" onclick="printDiv('printarea')"
                                                class="btn btn-primary ">{{\App\CPU\translate('print')}}</button>
                                    </th>
                                </form>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 mt-5 p-4">
            <h1 class="style-one-br show-div2">
                {{\App\CPU\translate("This page is for A4 size page printer, so it won't be visible in smaller devices")}}.
            </h1>
        </div>
    </div>

    <div id="printarea" class="show-div">
        @if ($limit)
            <div class="barcodea4">
                @for ($i = 0; $i <$limit; $i++)
                    @if($i%27==0 && $i!=0)
            </div>
            <div class="barcodea4">
                @endif
                <div class="item style24">
                    <span
                        class="barcode_site text-capitalize">{{ \App\Models\BusinessSetting::where('key','shop_name')->first()->value }}</span>
                    <span class="barcode_name text-capitalize">{{Str::limit($product->name,30)}}</span>
                    <span class="barcode_price text-capitalize">
                            {{ $product['selling_price'] . ' ' . \App\CPU\Helpers::currency_symbol() }}
                        </span>
                    <span class="barcode_image">{!! DNS1D::getBarcodeHTML($product->product_code, "C128") !!}</span>
                    <span
                        class="barcode_code text-capitalize">{{\App\CPU\translate('code')}} : {{$product->product_code}}</span>
                </div>

                @endfor
            </div>
        @endif
    </div>

@endsection

@push('script_2')
    <script src={{asset("public/assets/admin/js/global.js")}}></script>
@endpush
