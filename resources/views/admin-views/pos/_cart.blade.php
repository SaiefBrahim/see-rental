@push('css_or_js')
<link rel="stylesheet" href="{{ asset('public/assets/admin') }}/css/custom.css" />
@endpush

    <div class="card-body pt-0">
        <div class="table-responsive pos-cart-table border">
            <table class="table table-align-middle mb-0">
                <thead class="text-muted">
                    <tr>
                        <th>{{ \App\CPU\translate('item') }}</th>
                        <th>{{ \App\CPU\translate('qty') }}</th>
                        <th>{{ \App\CPU\translate('price') }}</th>
                        <th>{{ \App\CPU\translate('delete') }}</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $subtotal = 0;
                    $tax = 0;
                    $ext_discount = 0;
                    $ext_discount_type = 'amount';
                    $discount_on_product = 0;
                    $product_tax = 0;
                    $coupon_discount = 0;
                    ?>

                    @if (session()->has($cart_id) && count(session($cart_id)) > 0)
                        <?php
                        $cart = session()->get($cart_id);
                        if (isset($cart['tax'])) {
                            $tax = $cart['tax'];
                        }
                        if (isset($cart['ext_discount'])) {
                            $ext_discount = $cart['ext_discount'];
                            $ext_discount_type = $cart['ext_discount_type'];
                        }
                        if (isset($cart['coupon_discount'])) {
                            $coupon_discount = $cart['coupon_discount'];
                        }
                        ?>
                        @foreach (session($cart_id) as $key => $cartItem)
                            @if (is_array($cartItem))
                                <?php
                                $product_subtotal = $cartItem['price'] * $cartItem['quantity'];
                                $discount_on_product += $cartItem['discount'] * $cartItem['quantity'];
                                $subtotal += $product_subtotal;
                                $product_tax += $cartItem['tax'] * $cartItem['quantity'];

                                ?>
                                <tr>
                                    <td class="media gap-2 align-items-center">
                                        <img class="avatar avatar-sm"
                                            src="{{ asset('storage/app/public/product') }}/{{ $cartItem['image'] }}"
                                            onerror="this.src='{{ asset('public/assets/admin/img/160x160/img2.jpg') }}'"
                                            alt="{{ $cartItem['name'] }} image">
                                        <div class="media-body">
                                            <h5 class="text-hover-primary mb-0">{{ Str::limit($cartItem['name'], 10) }}</h5>
                                        </div>
                                    </td>
                                    <td>
                                        <input type="number" data-key="{{ $key }}" class="form-control text-center qty-width"
                                            value="{{ $cartItem['quantity'] }}" min="1"
                                            onkeyup="updateQuantity('{{ $cartItem['id'] }}',this.value)">
                                    </td>
                                    <td>
                                        <div>
                                            {{ $product_subtotal . ' ' . \App\CPU\Helpers::currency_symbol() }}
                                        </div>
                                    </td>
                                    <td>
                                        <a href="javascript:removeFromCart({{ $cartItem['id'] }})"
                                            class="btn btn-sm btn-outline-danger square-btn"> <i class="tio-delete"></i></a>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
@php
$total = $subtotal - $discount_on_product;
$discount_amount = $ext_discount_type == 'percent' && $ext_discount > 0 ? ($subtotal * $ext_discount) / 100 : $ext_discount;
//$discount_amount += $discount_on_product;
$total -= $discount_amount;
//$total_tax_amount= ($tax > 0)?(($total * $tax)/100):0;
$total_tax_amount = $product_tax;
@endphp
<div class="box p-3">
    <dl class="row">
        <dt class="col-6">{{ \App\CPU\translate('sub_total') }} :</dt>
        <dd class="col-6 text-right">{{ $subtotal . ' ' . \App\CPU\Helpers::currency_symbol() }}</dd>


        <dt class="col-6">{{ \App\CPU\translate('product_discount') }} :</dt>
        <dd class="col-6 text-right">{{ round($discount_on_product, 2) . ' ' . \App\CPU\Helpers::currency_symbol() }}
        </dd>

        <dt class="col-6">{{ \App\CPU\translate('extra_discount') }} :</dt>
        <dd class="col-6 text-right">
            <button id="extra_discount" class="btn btn-sm" type="button" data-toggle="modal"
                data-target="#add-discount"><i
                    class="tio-edit"></i></button>{{ number_format($discount_amount, 2)}} {{ \App\CPU\Helpers::currency_symbol()  }}
        </dd>
        <dt class="col-6">{{ \App\CPU\translate('coupon_discount') }} :</dt>
        <dd class="col-6 text-right">
            <button id="coupon_discount" class="btn btn-sm" type="button" data-toggle="modal"
                data-target="#add-coupon-discount"><i
                    class="tio-edit"></i></button>{{ $coupon_discount . ' ' . \App\CPU\Helpers::currency_symbol() }}
        </dd>

        <dt class="col-6">{{ \App\CPU\translate('tax') }} :</dt>
        <dd class="col-6 text-right">{{ round($total_tax_amount, 2) . ' ' . \App\CPU\Helpers::currency_symbol() }}</dd>
        {{-- <button class="btn btn-sm" type="button" data-toggle="modal" data-target="#add-tax"><i class="tio-edit"></i></button> --}}
        <dt class="col-6">{{ \App\CPU\translate('total') }} :</dt>
        <dd class="col-6 text-right h4 b">
            <span id="total_price">{{ round($total + $total_tax_amount - $coupon_discount, 2) }}</span>
            {{ \App\CPU\Helpers::currency_symbol() }}
        </dd>
    </dl>
    <div class="row g-2">
        <div class="col-6 mt-2">
            <a href="#" class="btn btn-danger btn-block" onclick="emptyCart()">
                <i class="fa fa-times-circle "></i>
                {{ \App\CPU\translate('Cancel_Order') }}
            </a>
        </div>
        <div class="col-6 mt-2">
            <button onclick="submit_order();" type="button" class="btn btn-success btn-block">
                <i class="fa fa-shopping-bag"></i>
                {{ \App\CPU\translate('Place_Order') }}
            </button>
        </div>
    </div>
</div>

{{-- data-toggle="modal" data-target="#paymentModal" --}}
<div class="modal fade" id="add-customer" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ \App\CPU\translate('add_new_customer') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.customer.store') }}" method="post" id="product_form">
                    @csrf
                    <input type="hidden" class="form-control" name="balance" value=0>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="input-label">{{ \App\CPU\translate('customer_name') }} <span
                                        class="input-label-secondary text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" value="{{ old('name') }}"
                                    placeholder="{{ \App\CPU\translate('customer_name') }}" required>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="input-label">{{ \App\CPU\translate('mobile_no') }} <span
                                        class="input-label-secondary text-danger">*</span></label>
                                <input type="text" id="mobile" name="mobile" class="form-control"
                                    value="{{ old('mobile') }}"
                                    placeholder="{{ \App\CPU\translate('mobile_no') }}" required>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="input-label">{{ \App\CPU\translate('email') }}</label>
                                <input type="email" name="email" class="form-control"
                                    value="{{ old('email') }}"
                                    placeholder="{{ \App\CPU\translate('Ex_:_ex@example.com') }}">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="input-label">{{ \App\CPU\translate('state') }}</label>
                                <input type="text" name="state" class="form-control"
                                    value="{{ old('state') }}" placeholder="{{ \App\CPU\translate('state') }}">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="input-label">{{ \App\CPU\translate('city') }} </label>
                                <input type="text" name="city" class="form-control"
                                    value="{{ old('city') }}" placeholder="{{ \App\CPU\translate('city') }}">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="input-label">{{ \App\CPU\translate('zip_code') }} </label>
                                <input type="text" name="zip_code" class="form-control"
                                    value="{{ old('zip_code') }}"
                                    placeholder="{{ \App\CPU\translate('zip_code') }}">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="input-label">{{ \App\CPU\translate('address') }} </label>
                                <input type="text" name="address" class="form-control"
                                    value="{{ old('address') }}"
                                    placeholder="{{ \App\CPU\translate('address') }}">
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" id="submit_new_customer"
                        class="btn btn-primary">{{ \App\CPU\translate('submit') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="add-discount" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ \App\CPU\translate('extra_discount') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="form-group col-sm-6">
                        <label for="">{{ \App\CPU\translate('discount') }}</label>
                        <input type="number" id="dis_amount" class="form-control" name="discount" step="0.01"
                            min="0">

                    </div>
                    <div class="form-group col-sm-6">
                        <label for="">{{ \App\CPU\translate('type') }}</label>
                        <select name="type" id="type_ext_dis" class="form-control" onchange="limit(this);">
                            <option value="amount" {{ $ext_discount_type == 'amount' ? 'selected' : '' }}>
                                {{ \App\CPU\translate('amount') }}
                                ({{ \App\CPU\Helpers::currency_symbol() }})
                            </option>
                            <option value="percent" {{ $ext_discount_type == 'percent' ? 'selected' : '' }}>
                                {{ \App\CPU\translate('percent') }}
                                (%)
                            </option>
                        </select>
                    </div>
                </div>
                <div class="d-flex justify-content-end">
                    <button class="btn btn-sm btn-primary" onclick="extra_discount();"
                        type="submit">{{ \App\CPU\translate('submit') }}</button>
                </div>

            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="add-coupon-discount" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ \App\CPU\translate('coupon_discount') }}</h5>
                <button id="coupon_close" type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <div class="form-group">
                    <label for="">{{ \App\CPU\translate('coupon_code') }}</label>
                    <input type="text" id="coupon_code" class="form-control" name="coupon_code">

                </div>

                <div class="d-flex justify-content-end">
                    <button class="btn btn-sm btn-primary" type="submit"
                        onclick="coupon_discount();">{{ \App\CPU\translate('submit') }}</button>
                </div>

            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="add-tax" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ \App\CPU\translate('update_tax') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.pos.tax') }}" method="POST" class="row">
                    @csrf
                    <div class="form-group col-12">
                        <label for="">{{ \App\CPU\translate('tax') }} (%)</label>
                        <input type="number" class="form-control" name="tax" min="0">
                    </div>

                    <div class="form-group col-sm-12">
                        <button class="btn btn-sm btn-primary"
                            type="submit">{{ \App\CPU\translate('submit') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="paymentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ \App\CPU\translate('payment') }} </h5>
                <button id="payment_close" type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <span class="style-three-cart">{{ \App\CPU\translate('total') }}</span>
                <h4 class="mb-0" id="total_balance"><span class="style-four-cart"> = </span>
                    {{ round($total + $total_tax_amount - $coupon_discount, 2) }}
                    {{ \App\CPU\Helpers::currency_symbol() }}</h4>
            </div>

            @php
                $accounts = \App\Models\Account::orderBy('id')->get();
            @endphp
            <div class="modal-body">
                <form action="{{ route('admin.pos.order') }}" id='order_place' method="post">
                    @csrf

                    <div class="form-group">
                        <label class="input-label" for="">{{ \App\CPU\translate('type') }}</label>
                        <select onchange="payment_option(this);" name="type" id="payment_opp"
                            class="form-control select2" required>
                            @foreach ($accounts as $account)
                                @if ($account['id'] != 2 && $account['id'] != 3)
                                    <option value="{{ $account['id'] }}">{{ $account['account'] }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group d-none" id="balance">
                        <label class="input-label" for="">{{ \App\CPU\translate('customer_balance') }}
                            ({{ \App\CPU\Helpers::currency_symbol() }})</label>
                        <input type="number" id="balance_customer" class="form-control" name="customer_balance"
                            disabled>
                    </div>
                    <div class="form-group d-none" id="remaining_balance">
                        <label class="input-label" for="">{{ \App\CPU\translate('remaining_balance') }}
                            ({{ \App\CPU\Helpers::currency_symbol() }})</label>
                        <input type="number" id="balance_remain" class="form-control" name="remaining_balance"
                            value="" readonly>
                    </div>
                    <div class="form-group d-none" id="transaction_ref">
                        <label class="input-label" for="">{{ \App\CPU\translate('transaction_reference') }}
                            ({{ \App\CPU\Helpers::currency_symbol() }})
                            -({{ \App\CPU\translate('optional') }})</label>
                        <input type="text" id="tran_ref" class="form-control" name="transaction_reference">
                    </div>
                    <div class="form-group" id="collected_cash">
                        <label class="input-label" for="">{{ \App\CPU\translate('collected_cash') }}
                            ({{ \App\CPU\Helpers::currency_symbol() }})</label>
                        <input type="number" id="cash_amount" onkeyup="price_calculation();" class="form-control"
                            name="collected_cash" step="0.01">
                    </div>
                    <div class="form-group" id="returned_amount">
                        <label class="input-label" for="">{{ \App\CPU\translate('returned_amount') }}
                            ({{ \App\CPU\Helpers::currency_symbol() }})</label>
                        <input type="number" id="returned" class="form-control" name="returned_amount"
                            value="" readonly>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button class="btn btn-sm btn-primary" id="order_complete"
                            type="submit">{{ \App\CPU\translate('submit') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="short-cut-keys" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ \App\CPU\translate('short_cut_keys') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <span>{{ \App\CPU\translate('to_click_order') }} : alt + O</span><br>
                <span>{{ \App\CPU\translate('to_click_payment_submit') }} : alt + S</span><br>
                <span>{{ \App\CPU\translate('to_close_payment_submit') }} : alt + Z</span><br>
                <span>{{ \App\CPU\translate('to_click_cancel_cart_item_all') }} : alt + C</span><br>
                <span>{{ \App\CPU\translate('to_click_add_new_customer') }} : alt + A</span> <br>
                <span>{{ \App\CPU\translate('to_submit_add_new_customer_form') }} : alt + N</span><br>
                <span>{{ \App\CPU\translate('to_click_short_cut_keys') }} : alt + K</span><br>
                <span>{{ \App\CPU\translate('to_print_invoice') }} : alt + P</span> <br>
                <span>{{ \App\CPU\translate('to_cancel_invoice') }} : alt + B</span> <br>
                <span>{{ \App\CPU\translate('to_focus_search_input') }} : alt + Q</span> <br>
                <span>{{ \App\CPU\translate('to_click_extra_discount') }} : alt + E</span> <br>
                <span>{{ \App\CPU\translate('to_click_coupon_discount') }} : alt + D</span> <br>
            </div>
        </div>
    </div>
</div>
