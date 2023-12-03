<div class="width-inone">
    <div class="text-center mb-3">
        <h2 class="line-inone">{{ \App\Models\BusinessSetting::where(['key' => 'shop_name'])->first()->value }}</h2>
        <h5 class="style-inone">
            {{ \App\Models\BusinessSetting::where(['key' => 'shop_address'])->first()->value }}
        </h5>
        <h5 class="style-intwo">
            {{ \App\CPU\translate('Phone') }}
            : {{ \App\Models\BusinessSetting::where(['key' => 'shop_phone'])->first()->value }}
        </h5>
        <h5 class="style-intwo">
            {{ \App\CPU\translate('Email') }}
            : {{ \App\Models\BusinessSetting::where(['key' => 'shop_email'])->first()->value }}
        </h5>
        <h5 class="style-intwo">
            {{ \App\CPU\translate('Vat_registration_number') }}
            : {{ \App\Models\BusinessSetting::where(['key' => 'vat_reg_no'])->first()->value }}
        </h5>
    </div>

    <hr class="line-dot">

    <center class="mt-3">
        <h5>{{ \App\CPU\translate('order_ID') }} : {{ $order['id'] }}</h5>

        <h5 class="font-inone fz-10">
            {{ date('d/M/Y h:i a', strtotime($order['created_at'])) }}
        </h5>
    </center>
    <h5 class="text-uppercase"></h5>
    <hr class="line-dot">

    <table class="table mt-3">
        <thead>
            <tr>
                <th>{{ \App\CPU\translate('SL') }}</th>
                <th>{{ \App\CPU\translate('DESC') }}</th>
                <th>{{ \App\CPU\translate('QTY') }}</th>
                <th>{{ \App\CPU\translate('Price') }}</th>
            </tr>
        </thead>

        <tbody>
            @php($sub_total = 0)
            @php($total_tax = 0)
            @php($total_dis_on_pro = 0)
            @foreach ($order->details as $key => $detail)
                @if ($detail->product_details)
                    @php($product = json_decode($detail->product_details, true))
                    <tr>
                        <td>
                            {{ $key + 1 }}
                        </td>
                        <td>
                            <span class="style-inthree">{{ $product['name'] }}</span><br />
                            {{ \App\CPU\translate('price') }} :
                            {{ $detail['price'] . ' ' . \App\CPU\Helpers::currency_symbol() }} <br>
                            {{ \App\CPU\translate('discount') }} :
                            {{ $detail['discount_on_product'] * $detail['quantity'] . ' ' . \App\CPU\Helpers::currency_symbol() }}
                        </td>
                        <td class="">
                            {{ $detail['quantity'] }}
                        </td>
                        <td>
                            @php($amount = ($detail['price'] - $detail['discount_on_product']) * $detail['quantity'])
                            {{ $amount . ' ' . \App\CPU\Helpers::currency_symbol() }}
                        </td>
                    </tr>
                    @php($sub_total += $amount)
                    @php($total_tax += $detail['tax_amount'] * $detail['quantity'])
                @endif
            @endforeach
        </tbody>
    </table>
    <hr class="line-dot">
    <dl class="row text-black-50">
        <dt class="col-7">{{ \App\CPU\translate('items_price') }}:</dt>
        <dd class="col-5 text-right">{{ $sub_total . ' ' . \App\CPU\Helpers::currency_symbol() }}</dd>
        <dt class="col-7">{{ \App\CPU\translate('Tax_/_VAT') }}:</dt>
        <dd class="col-5  text-right">{{ $total_tax . ' ' . \App\CPU\Helpers::currency_symbol() }}</dd>
        <dt class="col-7">{{ \App\CPU\translate('subtotal') }}:</dt>
        <dd class="col-5  text-right">{{ $sub_total + $total_tax . ' ' . \App\CPU\Helpers::currency_symbol() }}</dd>
        <dt class="col-7">{{ \App\CPU\translate('extra_discount') }}:</dt>
        <dd class="col-5  text-right">
            {{ $order['extra_discount'] ? number_format($order['extra_discount'], 2) . ' ' . \App\CPU\Helpers::currency_symbol() : 0 . ' ' . \App\CPU\Helpers::currency_symbol() }}</dd>
        <dt class="col-7">{{ \App\CPU\translate('coupon_discount') }}:</dt>
        <dd class="col-5  text-right">
            {{ $order['coupon_discount_amount'] . ' ' . \App\CPU\Helpers::currency_symbol() }}</dd>
        <dt class="col-7 total">{{ \App\CPU\translate('total') }}:</dt>
        <dd class="col-5  text-right total">
            {{ $sub_total + $total_tax  - ($order['coupon_discount_amount'] + $order['extra_discount']) }} {{  \App\CPU\Helpers::currency_symbol()  }}
        </dd>
    </dl>

    <div class="d-flex flex-wrap justify-content-between border-top pt-3">
        <div class="mr-1">
            {{ \App\CPU\translate('Paid_by') }}: {{ ($order->payment_id != 0) ? ($order->account ? $order->account->account : \App\CPU\translate('account_deleted')): 'Customer balance' }}
        </div>
        @if ($order->payment_id == 1)
            <div class="mr-1">{{ \App\CPU\translate('amount') }}:
                {{ $order->collected_cash ? $order->collected_cash . ' ' . \App\CPU\Helpers::currency_symbol() : 0 . ' ' . \App\CPU\Helpers::currency_symbol() }}
            </div>
            <div class="mr-1">{{ \App\CPU\translate('change') }}: {{ number_format($order->collected_cash - $order->order_amount - $order->total_tax + $order->extra_discount + $order->coupon_discount_amount, 2) }}
                {{ \App\CPU\Helpers::currency_symbol() }}
            </div>
        @endif
    </div>
    <hr class="line-dot">
    <h5 class="text-center">
        """{{ \App\CPU\translate('THANK YOU') }}"""
    </h5>
    <hr class="line-dot">
</div>
