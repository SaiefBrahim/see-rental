<!DOCTYPE html>
<html lang="en">

<head>
    {{-- <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge"> --}}
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <title>Product Barcode</title>
    {{-- <link rel="stylesheet" href="{{ asset('public/assets/admin') }}/css/barcode.css" /> --}}
    <style>
        /* .barcodea4 {
            page-break-after: always;
        } */

        p.serif {
            font-family: 'IDAHC39M Code 39 Barcode', Times, serif;
        }

        p.sansserif {
            font-family: Arial, Helvetica, sans-serif;
        }

        .style-one {
            width: 200px;
            border-style: dotted;
            border-width: thin;
            margin: 10px;
            padding: 10px;
        }

        * {
            -webkit-print-color-adjust: exact !important;
            /* Chrome, Safari, Edge */
            color-adjust: exact !important;
            /*Firefox*/
        }

        .barcodea4 {

            /* display: block; */
            border: 1px solid #CCC;
            margin: 10px auto;
            padding:10px 0 0 10px;
            page-break-after: always;
        }

        .barcodea4 .style24 {
            width: 100px;
            height: auto;
            /* margin-inline-start: 0.1rem; */
            /* padding-top: 0.3rem; */
            padding: 10px;
        }

        .barcodea4 .item {
            /* display: block; */
            overflow: hidden;
            text-align: center;
            border: 1px dotted #CCC;
            font-size: 12px;
            line-height: 14px;
            text-transform: uppercase;
            float: left;
            margin-inline-end: 5px;
            margin-bottom: 8px;
        }

        .barcode_site {
            font-size: 14px;
            display: block;
            font-weight: bold;
        }

        .barcode_name {

            display: block;
        }

        .barcode_price {
            display: block;
        }

        .barcode_image {
            display: block;
            margin-inline-start: 35px;
        }

        .barcode_code {
            display: block;
            font-weight: bold;
        }

        @page {
            size: A4 !important;
            margin: 0 !important;
        }

        /* @media screen and (max-width: 800px) {
            .show-div {
                visibility: hidden;
                display: none;
            }

            .show-div2 {
                visibility: visible;
                display: block;
            }
        }

        @media screen and (min-width: 800px) {
            .show-div {
                display: block;
            }

            .show-div2 {
                display: none;
            }
        }
 */
        .style-one-br {
            font-size: 35px;
            color: darkred
        }
    </style>
</head>
<body>
    @if ($limit)
        <div class="barcodea4">
            {{-- @for ($i = 0; $i < $limit; $i++) --}}
            {{-- @if ($i % 27 == 0 && $i != 0)
                        </div>
                        <div class="barcodea4">
                    @endif --}}
            <div class="item style24">
                <span
                    class="barcode_site text-capitalize">{{ \App\Models\BusinessSetting::where('key', 'shop_name')->first()->value }}</span>
                <span class="barcode_name text-capitalize">{{ Str::limit($product->name, 30) }}</span>
                <span class="barcode_price text-capitalize">
                    {{ $product['selling_price'] . ' ' . \App\CPU\Helpers::currency_symbol() }}
                </span>
                <span class="barcode_image">{!! DNS1D::getBarcodeHTML($product->product_code, 'C128') !!}</span>
                <span class="barcode_code text-capitalize">{{ \App\CPU\translate('code') }} :
                    {{ $product->product_code }}</span>
            </div>
            <div class="item style24">
                <span
                    class="barcode_site text-capitalize">{{ \App\Models\BusinessSetting::where('key', 'shop_name')->first()->value }}</span>
                <span class="barcode_name text-capitalize">{{ Str::limit($product->name, 30) }}</span>
                <span class="barcode_price text-capitalize">
                    {{ $product['selling_price'] . ' ' . \App\CPU\Helpers::currency_symbol() }}
                </span>
                <span class="barcode_image">{!! DNS1D::getBarcodeHTML($product->product_code, 'C128') !!}</span>
                <span class="barcode_code text-capitalize">{{ \App\CPU\translate('code') }} :
                    {{ $product->product_code }}</span>
            </div>
            <div class="item style24">
                <span
                    class="barcode_site text-capitalize">{{ \App\Models\BusinessSetting::where('key', 'shop_name')->first()->value }}</span>
                <span class="barcode_name text-capitalize">{{ Str::limit($product->name, 30) }}</span>
                <span class="barcode_price text-capitalize">
                    {{ $product['selling_price'] . ' ' . \App\CPU\Helpers::currency_symbol() }}
                </span>
                <span class="barcode_image">{!! DNS1D::getBarcodeHTML($product->product_code, 'C128') !!}</span>
                <span class="barcode_code text-capitalize">{{ \App\CPU\translate('code') }} :
                    {{ $product->product_code }}</span>
            </div><br/>
            <div class="item style24">
                <span
                    class="barcode_site text-capitalize">{{ \App\Models\BusinessSetting::where('key', 'shop_name')->first()->value }}</span>
                <span class="barcode_name text-capitalize">{{ Str::limit($product->name, 30) }}</span>
                <span class="barcode_price text-capitalize">
                    {{ $product['selling_price'] . ' ' . \App\CPU\Helpers::currency_symbol() }}
                </span>
                <span class="barcode_image">{!! DNS1D::getBarcodeHTML($product->product_code, 'C128') !!}</span>
                <span class="barcode_code text-capitalize">{{ \App\CPU\translate('code') }} :
                    {{ $product->product_code }}</span>
            </div>
            <div class="item style24">
                <span
                    class="barcode_site text-capitalize">{{ \App\Models\BusinessSetting::where('key', 'shop_name')->first()->value }}</span>
                <span class="barcode_name text-capitalize">{{ Str::limit($product->name, 30) }}</span>
                <span class="barcode_price text-capitalize">
                    {{ $product['selling_price'] . ' ' . \App\CPU\Helpers::currency_symbol() }}
                </span>
                <span class="barcode_image">{!! DNS1D::getBarcodeHTML($product->product_code, 'C128') !!}</span>
                <span class="barcode_code text-capitalize">{{ \App\CPU\translate('code') }} :
                    {{ $product->product_code }}</span>
            </div>
            <div class="item style24">
                <span
                    class="barcode_site text-capitalize">{{ \App\Models\BusinessSetting::where('key', 'shop_name')->first()->value }}</span>
                <span class="barcode_name text-capitalize">{{ Str::limit($product->name, 30) }}</span>
                <span class="barcode_price text-capitalize">
                    {{ $product['selling_price'] . ' ' . \App\CPU\Helpers::currency_symbol() }}
                </span>
                <span class="barcode_image">{!! DNS1D::getBarcodeHTML($product->product_code, 'C128') !!}</span>
                <span class="barcode_code text-capitalize">{{ \App\CPU\translate('code') }} :
                    {{ $product->product_code }}</span>
            </div>

        </div>

    @endif
</body>
</html>
