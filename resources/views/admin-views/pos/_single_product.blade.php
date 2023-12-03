<div id="{{ $product->id }}" class="">
    <input type="hidden" id="product_id" name="id" value="{{ $product->id }}">
    <input type="hidden" id="product_qty" name="quantity" value=1>
    <a onclick="addToCart({{ $product->id }})" class="pos-product-item card">
        <div class="pos-product-item_thumb">
            <img src="{{asset('storage/app/public/product')}}/{{$product['image']}}"
                onerror="this.src='{{asset('public/assets/admin/img/160x160/img2.jpg')}}'"
            class="img-fit">
        </div>
        <div class="pos-product-item_content">
            <div class="pos-product-item_title">{{ $product['name'] }}</div>
            {{-- <div class="fz-12 mb-1">{{\App\CPU\translate('code')}}: {{ $product['product_code'] }}</div> --}}
            <div class="pos-product-item_price">
                {{ ($product['selling_price']- \App\CPU\Helpers::discount_calculate($product, $product['selling_price'])) . ' ' . \App\CPU\Helpers::currency_symbol() }}

                @if($product->discount > 0)
                    <strike class="fz-10 text-muted">
                        {{ $product['selling_price'] . ' ' . \App\CPU\Helpers::currency_symbol() }}
                    </strike><br>
                @endif
            </div>
        </div>
    </a>
</div>



