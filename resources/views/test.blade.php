<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Product Barcode</title>
    <link rel="stylesheet" href="{{ asset('public/assets/admin') }}/css/bootstrap.css" />
    <style>
        @font-face {
            font-family: 'DejaVuSans';
            font-style: normal;
            font-weight: 900;
            src: url('{{ asset('public/assets/admin/font/ttf/') }}/DejaVuSans.ttf');

        }
        .text-capitalize {
            text-transform: uppercase;
        }

        .text-bold {
            font-weight: bold;
        }
        .currency {
            font-family: DejaVuSans;
        }
    </style>
</head>

<body>
    @if ($quantity)
        <div class="container">
            <div class="row">
                @for ($i = 0; $i < $quantity; $i++)
                    @if ($i % 3 == 0 && $i != 0)
            </div>
            <div class="row">
    @endif
    <div class="col-xs-4">
        <span
            class="text-capitalize text-bold">{{ \App\Models\BusinessSetting::where('key', 'shop_name')->first()->value }}</span>
        <br>
        <span class="product-name">{{ Str::limit($product->name, 30) }}</span> <br>
        <span class="currency">
            {{ $product['selling_price'] . ' ' . \App\CPU\Helpers::currency_symbol() }}
        </span> <br>
        <span class="bar-code">{!! DNS1D::getBarcodeHTML($product->product_code, 'C128') !!}</span>
        <p class="">{{ \App\CPU\translate('code') }} :
            {{ $product->product_code }}</p>
    </div>
    @endfor
    </div>
    </div>
    @endif
</body>

</html>
