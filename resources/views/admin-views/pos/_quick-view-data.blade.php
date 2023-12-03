<link rel="stylesheet" href="{{asset('public/assets/admin')}}/css/custom.css"/>
<div class="modal-header p-2">
    <h4 class="modal-title product-title">
    </h4>
    <button class="close call-when-done" type="button" data-dismiss="modal" aria-label="{{\App\CPU\translate('Close')}}">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <div class="d-flex flex-row">
        <!-- Product gallery-->
        <div class="d-flex align-items-center justify-content-center active h-one-qv">
            <img class="img-responsive style-one-qv"
                 src="{{asset('storage/app/public/product')}}/{{$product['image']}}"
                 onerror="this.src='{{asset('public/assets/admin/img/160x160/img2.jpg')}}'"
                 data-zoom="{{asset('storage/app/public/product')}}/{{$product['image']}}"
                 alt="{{\App\CPU\translate('Product image')}}" width="">
            <div class="cz-image-zoom-pane"></div>
        </div>
        <!-- Product details-->
        <div class="details pl-2">
            <a href="#" class="h3 mb-2 product-title">{{ Str::limit($product->name, 26) }}</a>

            <div class="mb-3 text-dark">
                <span class="h3 font-weight-normal text-accent mr-1">
                    {{ ($product['selling_price']- \App\CPU\Helpers::discount_calculate($product, $product['selling_price'])) . ' ' . \App\CPU\Helpers::currency_symbol() }}
                </span>
                @if($product->discount > 0)
                    <strike class="font-one-qv">
                        {{ $product['selling_price'] . ' ' . \App\CPU\Helpers::currency_symbol() }}
                    </strike>
                @endif
            </div>

            @if($product->discount > 0)
                <div class="mb-3 text-dark">
                    <strong>{{\App\CPU\translate('Discount')}} : </strong>
                    <strong
                        id="set-discount-amount">{{\App\CPU\Helpers::discount_calculate($product, $product->selling_price)}}</strong>
                </div>
            @endif

        </div>
    </div>
    <div class="row pt-2">
        <div class="col-12">
            <?php
            $cart = false;
            if (session()->has('cart')) {
                foreach (session()->get('cart') as $key => $cartItem) {
                    if (is_array($cartItem) && $cartItem['id'] == $product['id']) {
                        $cart = $cartItem;
                    }
                }
            }

            ?>

            <form id="add-to-cart-form" class="mb-2">
                @csrf
                <input type="hidden" name="id" value="{{ $product->id }}">


                <!-- Quantity + Add to cart -->
                <div class="d-flex justify-content-between">
                    <div class="product-description-label mt-2 text-dark h3">{{\App\CPU\translate('Quantity')}}:</div>
                    <div class="product-quantity d-flex align-items-center">
                        <div class="input-group input-group--style-2 pr-3 w-one-qv">
                            <span class="input-group-btn">
                                <button class="btn btn-number text-dark p-1" type="button"
                                        data-type="minus" data-field="quantity"
                                        disabled="disabled">
                                        <i class="tio-remove  font-weight-bold"></i>
                                </button>
                            </span>
                            <input type="text" name="quantity"
                                   class="form-control input-number text-center cart-qty-field"
                                   placeholder="{{\App\CPU\translate('1')}}" value="1" min="1" max="100">
                            <span class="input-group-btn">
                                <button class="btn btn-number text-dark p-1" type="button" data-type="plus"
                                        data-field="quantity">
                                        <i class="tio-add  font-weight-bold"></i>
                                </button>
                            </span>
                        </div>
                    </div>
                </div>


                <div class="d-flex justify-content-center mt-2">
                    <button class="btn btn-primary style-two-qv"
                            onclick="addToCart()"
                            type="button">
                        <i class="tio-shopping-cart"></i>
                        {{\App\CPU\translate('add')}}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{--<script type="text/javascript">
    "use strict";
    cartQuantityInitialize();
    getVariantPrice();
    $('#add-to-cart-form input').on('change', function () {
        getVariantPrice();
    });
</script>--}}

