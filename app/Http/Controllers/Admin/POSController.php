<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\Order;
use App\Models\Coupon;
use App\Models\Transection;
use App\Models\Account;
use App\Models\OrderDetail;
use App\Models\Customer;
use App\CPU\Helpers;
use Brian2694\Toastr\Facades\Toastr;
use function App\CPU\translate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class POSController extends Controller
{
    public function __construct(
        private Category $category,
        private Product $product,
        private Order $order,
        private Coupon $coupon,
        private Transection $transection,
        private Account $account,
        private OrderDetail $order_details,
        private Customer $customer
    ){}

    /**
     * @param Request $request
     * @return Application|Factory|View
     */
    public function index(Request $request): Factory|View|Application
    {
        $category = $request->query('category_id', 0);
        $keyword = $request->query('search', false);
        $key = explode(' ', $keyword);
        $categories = $this->category->where('status', 1)->where('position', 0)->latest()->get();

        $products = $this->product->where('quantity', '>', 0)->active()
            ->when($request->has('category_id') && $request['category_id'] != 0, function ($query) use ($request) {
                $query->whereJsonContains('category_ids', [['id' => (string)$request['category_id']]]);
            })->latest()->paginate(Helpers::pagination_limit());

        $cart_id = 'wc-' . rand(10, 1000);

        if (!session()->has('current_user')) {
            session()->put('current_user', $cart_id);
        }
        if (strpos(session('current_user'), 'wc')) {
            $user_id = 0;
        } else {
            $user_id = explode('-', session('current_user'))[1];
        }

        if (!session()->has('cart_name')) {
            if (!in_array($cart_id, session('cart_name') ?? [])) {
                session()->push('cart_name', $cart_id);
            }
        }

        return view('admin-views.pos.index', compact('categories', 'products', 'cart_id', 'category', 'user_id'));
    }

    /**
     * @return RedirectResponse
     */
    public function clear_cart_ids(): RedirectResponse
    {
        session()->forget('cart_name');
        session()->forget(session('current_user'));
        session()->forget('current_user');

        return redirect()->route('admin.pos.index');
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function quick_view(Request $request): JsonResponse
    {
        $product = $this->product->findOrFail($request->product_id);

        return response()->json([
            'success' => 1,
            'view' => view('admin-views.pos._quick-view-data', compact('product'))->render(),
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function addToCart(Request $request): JsonResponse
    {
        $cart_id = session('current_user');
        $user_id = 0;
        $user_type = 'wc';
        if (Str::contains(session('current_user'), 'sc')) {
            $user_id = explode('-', session('current_user'))[1];
            $user_type = 'sc';
        }
        $product = $this->product->find($request->id);
        $cart = session($cart_id);
        if (session()->has($cart_id) && count($cart) > 0) {
            foreach ($cart as $key => $cartItem) {
                if (is_array($cartItem) && $cartItem['id'] == $request['id']) {
                    $qty = $product->quantity - $cartItem['quantity'];
                    if ($qty == 0) {
                        return response()->json([
                            'qty' => $qty,
                            'user_type' => $user_type,
                            'user_id' => $user_id,
                            'view' => view('admin-views.pos._cart', compact('cart_id'))->render()
                        ]);
                    }
                }
            }
        }

        $data = array();
        $data['id'] = $product->id;
        $cart_keeper = [];
        $item_exist = 0;
        if (session()->has($cart_id) && count($cart) > 0) {
            foreach ($cart as $key => $cartItem) {
                if (is_array($cartItem) && $cartItem['id'] == $request['id']) {
                    $cartItem['quantity'] += 1;
                    $item_exist = 1;
                }
                array_push($cart_keeper, $cartItem);
            }
        }
        session()->put($cart_id, $cart_keeper);

        if ($item_exist == 0) {
            $data['quantity'] = $request['quantity'];
            $data['price'] = $product->selling_price;
            $data['name'] = $product->name;
            $data['discount'] = Helpers::discount_calculate($product, $product->selling_price);
            $data['image'] = $product->image;
            $data['tax'] = Helpers::tax_calculate($product, $product->selling_price);
            if ($request->session()->has($cart_id)) {
                $keeper = [];
                foreach (session($cart_id) as $item) {
                    array_push($keeper, $item);
                }
                $keeper[] = $data;
                $request->session()->put($cart_id, $keeper);
            } else {
                $request->session()->put($cart_id, [$data]);
            }
        }

        return response()->json([
            'user_type' => $user_type,
            'user_id' => $user_id,
            'view' => view('admin-views.pos._cart', compact('cart_id'))->render()
        ]);
    }

    /**
     * @return Application|Factory|View
     */
    public function cart_items(): Factory|View|Application
    {
        return view('admin-views.pos._cart');
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function emptyCart(Request $request): JsonResponse
    {
        $cart_id = session('current_user');
        $user_id = 0;
        $user_type = 'wc';
        if (Str::contains(session('current_user'), 'sc')) {
            $user_id = explode('-', session('current_user'))[1];
            $user_type = 'sc';
        }
        session()->forget($cart_id);
        return response()->json([
            'user_type' => $user_type,
            'user_id' => $user_id,
            'view' => view('admin-views.pos._cart', compact('cart_id'))->render()
        ], 200);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function updateQuantity(Request $request): JsonResponse
    {
        $cart_id = session('current_user');
        $user_id = 0;
        $user_type = 'wc';
        if (Str::contains(session('current_user'), 'sc')) {
            $user_id = explode('-', session('current_user'))[1];
            $user_type = 'sc';
        }
        if ($request->quantity > 0) {

            $product = $this->product->find($request->key);
            $cart = session($cart_id);
            $keeper = [];
            foreach ($cart as $item) {
                if (is_array($item)) {
                    if ($item['id'] == $request->key) {
                        $qty = $product->quantity - $request->quantity;
                        if ($qty < 0) {
                            return response()->json([
                                'qty' => $qty,
                                'user_type' => $user_type,
                                'user_id' => $user_id,
                                'view' => view('admin-views.pos._cart', compact('cart_id'))->render()
                            ]);
                        }
                        $item['quantity'] = $request->quantity;
                    }
                    $keeper[] = $item;
                }
            }
            session()->put($cart_id, $keeper);

            return response()->json([
                'user_type' => $user_type,
                'user_id' => $user_id,
                'view' => view('admin-views.pos._cart', compact('cart_id'))->render()
            ], 200);
        } else {
            return response()->json([
                'upQty' => 'zeroNegative',
                'user_type' => $user_type,
                'user_id' => $user_id,
                'view' => view('admin-views.pos._cart', compact('cart_id'))->render()
            ]);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function removeFromCart(Request $request): JsonResponse
    {
        $cart_id = session('current_user');
        $user_id = 0;
        $user_type = 'wc';
        if (Str::contains(session('current_user'), 'sc')) {
            $user_id = explode('-', session('current_user'))[1];
            $user_type = 'sc';
        }
        $cart = session($cart_id);
        $cart_keeper = [];
        if (session()->has($cart_id) && count($cart) > 0) {
            foreach ($cart as $cartItem) {
                if (is_array($cartItem) && $cartItem['id'] != $request['key']) {
                    array_push($cart_keeper, $cartItem);
                }
            }
        }
        session()->put($cart_id, $cart_keeper);

        return response()->json([
            'user_type' => $user_type,
            'user_id' => $user_id,
            'view' => view('admin-views.pos._cart', compact('cart_id'))->render()
        ], 200);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function update_discount(Request $request): JsonResponse
    {
        $cart_id = session('current_user');
        $user_id = 0;
        $user_type = 'wc';
        if (Str::contains(session('current_user'), 'sc')) {
            $user_id = explode('-', session('current_user'))[1];
            $user_type = 'sc';
        }
        $cart = session($cart_id, collect([]));
        if ($cart != null) {
            $total_product_price = 0;
            $product_discount = 0;
            $product_tax = 0;
            $ext_discount = 0;
            $coupon_discount = $cart['coupon_discount'] ?? 0;
            foreach ($cart as $ct) {
                if (is_array($ct)) {
                    $total_product_price += $ct['price'] * $ct['quantity'];
                    $product_discount += $ct['discount'] * $ct['quantity'];
                    $product_tax += $ct['tax'] * $ct['quantity'];
                }
            }
            $price_discount = 0;
            if ($request->type == 'percent') {
                $price_discount = ($total_product_price / 100) * $request->discount;
            } else {
                $price_discount = $request->discount;
            }
            $ext_discount = $price_discount;
            $total = $total_product_price - $product_discount + $product_tax - $coupon_discount - $ext_discount;

            if ($total < 0) {
                return response()->json([
                    'extra_discount' => "amount_low",
                    'user_type' => $user_type,
                    'user_id' => $user_id,
                    'view' => view('admin-views.pos._cart', compact('cart_id'))->render()
                ]);
            } else {
                $cart['ext_discount'] = $request->discount;
                $cart['ext_discount_type'] = $request->type;
                session()->put($cart_id, $cart);

                return response()->json([
                    'extra_discount' => "success",
                    'user_type' => $user_type,
                    'user_id' => $user_id,
                    'view' => view('admin-views.pos._cart', compact('cart_id'))->render()
                ]);
            }
        } else {
            return response()->json([
                'extra_discount' => "empty",
                'user_type' => $user_type,
                'user_id' => $user_id,
                'view' => view('admin-views.pos._cart', compact('cart_id'))->render()
            ]);
        }
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function update_tax(Request $request): RedirectResponse
    {
        $cart = $request->session()->get('cart', collect([]));
        $cart['tax'] = $request->tax;
        $request->session()->put('cart', $cart);
        return back();
    }

    /**
     * @param $cart
     * @param $price
     * @return float|int
     */
    public function extra_dis_calculate($cart, $price): float|int
    {

        if ($cart['ext_discount_type'] == 'percent') {
            $price_discount = ($price / 100) * $cart['ext_discount'];
        } else {
            $price_discount = $cart['ext_discount'];
        }
        return $price_discount;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function coupon_discount(Request $request): JsonResponse
    {
        $cart_id = session('current_user');
        $user_id = 0;
        $user_type = 'wc';
        if (Str::contains(session('current_user'), 'sc')) {
            $user_id = explode('-', session('current_user'))[1];
            $user_type = 'sc';
        }
        if ($user_id != 0) {
            $couponLimit = $this->order->where('user_id', $user_id)
                ->where('coupon_code', $request['coupon_code'])->count();

            $coupon = $this->coupon->where(['code' => $request['coupon_code']])
                ->where('user_limit', '>', $couponLimit)
                ->where('status', '=', 1)
                ->whereDate('start_date', '<=', now())
                ->whereDate('expire_date', '>=', now())->first();
        } else {
            $coupon = $this->coupon->where(['code' => $request['coupon_code']])
                ->where('status', '=', 1)
                ->whereDate('start_date', '<=', now())
                ->whereDate('expire_date', '>=', now())->first();
        }

        $carts = session($cart_id);
        $total_product_price = 0;
        $product_discount = 0;
        $product_tax = 0;
        $ext_discount = 0;

        if ($coupon != null) {
            if ($carts != null) {
                foreach ($carts as $cart) {
                    if (is_array($cart)) {
                        $total_product_price += $cart['price'] * $cart['quantity'];
                        $product_discount += $cart['discount'] * $cart['quantity'];
                        $product_tax += $cart['tax'] * $cart['quantity'];
                    }
                }
                if ($total_product_price >= $coupon['min_purchase']) {
                    if ($coupon['discount_type'] == 'percent') {

                        $discount = (($total_product_price / 100) * $coupon['discount']) > $coupon['max_discount'] ? $coupon['max_discount'] : (($total_product_price / 100) * $coupon['discount']);
                    } else {
                        $discount = $coupon['discount'];
                    }
                    if (isset($carts['ext_discount_type'])) {
                        $ext_discount = $this->extra_dis_calculate($carts, $total_product_price);
                    }
                    $total = $total_product_price - $product_discount + $product_tax - $discount - $ext_discount;
                    if ($total < 0) {
                        return response()->json([
                            'coupon' => "amount_low",
                            'user_type' => $user_type,
                            'user_id' => $user_id,
                            'view' => view('admin-views.pos._cart', compact('cart_id'))->render()
                        ]);
                    }

                    $cart = session($cart_id, collect([]));
                    $cart['coupon_code'] = $request['coupon_code'];
                    $cart['coupon_discount'] = $discount;
                    $cart['coupon_title'] = $coupon->title;
                    $request->session()->put($cart_id, $cart);

                    return response()->json([
                        'coupon' => 'success',
                        'user_type' => $user_type,
                        'user_id' => $user_id,
                        'view' => view('admin-views.pos._cart', compact('cart_id'))->render()
                    ]);
                }
            } else {
                return response()->json([
                    'coupon' => 'cart_empty',
                    'user_type' => $user_type,
                    'user_id' => $user_id,
                    'view' => view('admin-views.pos._cart', compact('cart_id'))->render()
                ]);
            }

            return response()->json([
                'coupon' => 'coupon_invalid',
                'user_type' => $user_type,
                'user_id' => $user_id,
                'view' => view('admin-views.pos._cart', compact('cart_id'))->render()
            ]);
        }

        return response()->json([
            'coupon' => 'coupon_invalid',
            'user_type' => $user_type,
            'user_id' => $user_id,
            'view' => view('admin-views.pos._cart', compact('cart_id'))->render()
        ]);
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function place_order(Request $request): RedirectResponse
    {
        $cart_id = session('current_user');
        $user_id = 0;
        $user_type = 'wc';
        if (Str::contains(session('current_user'), 'sc')) {
            $user_id = explode('-', session('current_user'))[1];
            $user_type = 'sc';
        }
        if (session($cart_id)) {
            if (count(session($cart_id)) < 1) {
                Toastr::error(translate('cart_empty_warning'));
                return back();
            }
        } else {
            Toastr::error(translate('cart_empty_warning'));
            return back();
        }
        $cart = session($cart_id);
        $coupon_code = 0;
        $product_price = 0;
        $order_details = [];
        $product_discount = 0;
        $product_tax = 0;
        $ext_discount = 0;
        $coupon_discount = $cart['coupon_discount'] ?? 0;

        $order_id = 100000 + $this->order->all()->count() + 1;
        if ($this->order->find($order_id)) {
            $order_id = $this->order->orderBy('id', 'DESC')->first()->id + 1;
        }

        $order = $this->order;
        $order->id = $order_id;

        $order->user_id = $user_id;
        $order->coupon_code = $cart['coupon_code'] ?? null;
        $order->coupon_discount_title = $cart['coupon_title'] ?? null;
        $order->payment_id = $request->type;
        $order->transaction_reference = $request->transaction_reference ?? null;

        $order->created_at = now();
        $order->updated_at = now();

        foreach ($cart as $c) {
            if (is_array($c)) {
                $product = $this->product->find($c['id']);
                if ($product) {
                    $price = $c['price'];
                    $or_d = [
                        'product_id' => $c['id'],
                        'product_details' => $product,
                        'quantity' => $c['quantity'],
                        'price' => $product->selling_price,
                        'tax_amount' => Helpers::tax_calculate($product, $product->selling_price),
                        'discount_on_product' => Helpers::discount_calculate($product, $product->selling_price),
                        'discount_type' => 'discount_on_product',
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                    $product_price += $price * $c['quantity'];
                    $product_discount += $c['discount'] * $c['quantity'];
                    $product_tax += $c['tax'] * $c['quantity'];
                    $order_details[] = $or_d;

                    $product->quantity = $product->quantity - $c['quantity'];
                    $product->order_count++;
                    $product->save();
                }
            }
        }
        $total_price = $product_price - $product_discount;

        if (isset($cart['ext_discount_type'])) {
            $ext_discount = $this->extra_dis_calculate($cart, $product_price);
            $order->extra_discount = $ext_discount;
        }

        $total_tax_amount = $product_tax;
        try {
            $order->total_tax = $total_tax_amount;
            $order->order_amount = $total_price;

            $order->coupon_discount_amount = $coupon_discount;
            $order->collected_cash = $request->collected_cash ? $request->collected_cash : $total_price + $total_tax_amount - $ext_discount - $coupon_discount;
            $order->save();

            $customer = $this->customer->where('id', $user_id)->first();
            if ($user_id != 0 && $request->type == 0) {
                $grand_total = $total_price + $total_tax_amount - $ext_discount - $coupon_discount;

                if ($request->remaining_balance >= 0) {
                    $payable_account = Account::find(2);
                    $payable_transaction = new Transection;
                    $payable_transaction->tran_type = 'Payable';
                    $payable_transaction->account_id = $payable_account->id;
                    $payable_transaction->amount = $grand_total;
                    $payable_transaction->description = 'POS order';
                    $payable_transaction->debit = 1;
                    $payable_transaction->credit = 0;
                    $payable_transaction->balance = $payable_account->balance - $grand_total;
                    $payable_transaction->date = date("Y/m/d");
                    $payable_transaction->customer_id = $customer->id;
                    $payable_transaction->order_id = $order_id;
                    $payable_transaction->save();

                    $payable_account->total_out = $payable_account->total_out + $grand_total;
                    $payable_account->balance = $payable_account->balance - $grand_total;
                    $payable_account->save();
                } else {

                    if ($customer->balance > 0) {
                        $payable_account = Account::find(2);
                        $payable_transaction = new Transection;
                        $payable_transaction->tran_type = 'Payable';
                        $payable_transaction->account_id = $payable_account->id;
                        $payable_transaction->amount = $customer->balance;
                        $payable_transaction->description = 'POS order';
                        $payable_transaction->debit = 1;
                        $payable_transaction->credit = 0;
                        $payable_transaction->balance = $payable_account->balance - $customer->balance;
                        $payable_transaction->date = date("Y/m/d");
                        $payable_transaction->customer_id = $customer->id;
                        $payable_transaction->order_id = $order_id;
                        $payable_transaction->save();

                        $payable_account->total_out = $payable_account->total_out + $customer->balance;
                        $payable_account->balance = $payable_account->balance - $customer->balance;
                        $payable_account->save();

                        $receivable_account = Account::find(3);
                        $receivable_transaction = new Transection;
                        $receivable_transaction->tran_type = 'Receivable';
                        $receivable_transaction->account_id = $receivable_account->id;
                        $receivable_transaction->amount = -$request->remaining_balance;
                        $receivable_transaction->description = 'POS order';
                        $receivable_transaction->debit = 0;
                        $receivable_transaction->credit = 1;
                        $receivable_transaction->balance = $receivable_account->balance - $request->remaining_balance;
                        $receivable_transaction->date = date("Y/m/d");
                        $receivable_transaction->customer_id = $customer->id;
                        $receivable_transaction->order_id = $order_id;
                        $receivable_transaction->save();

                        $receivable_account->total_in = $receivable_account->total_in - $request->remaining_balance;
                        $receivable_account->balance = $receivable_account->balance - $request->remaining_balance;
                        $receivable_account->save();
                    } else {

                        $receivable_account = Account::find(3);
                        $receivable_transaction = new Transection;
                        $receivable_transaction->tran_type = 'Receivable';
                        $receivable_transaction->account_id = $receivable_account->id;
                        $receivable_transaction->amount = $grand_total;
                        $receivable_transaction->description = 'POS order';
                        $receivable_transaction->debit = 0;
                        $receivable_transaction->credit = 1;
                        $receivable_transaction->balance = $receivable_account->balance + $grand_total;
                        $receivable_transaction->date = date("Y/m/d");
                        $receivable_transaction->customer_id = $customer->id;
                        $receivable_transaction->order_id = $order_id;
                        $receivable_transaction->save();

                        $receivable_account->total_in = $receivable_account->total_in + $grand_total;
                        $receivable_account->balance = $receivable_account->balance + $grand_total;
                        $receivable_account->save();
                    }
                }

                $customer->balance = $request->remaining_balance;
                $customer->save();
            }

            //transection start
            if ($request->type != 0) {
                $account = Account::find($request->type);
                $transection = new Transection;
                $transection->tran_type = 'Income';
                $transection->account_id = $request->type;
                $transection->amount = $total_price + $total_tax_amount - $ext_discount - $coupon_discount;
                $transection->description = 'POS order';
                $transection->debit = 0;
                $transection->credit = 1;
                $transection->balance = $account->balance + $total_price + $total_tax_amount - $ext_discount - $coupon_discount;
                $transection->date = date("Y/m/d");
                $transection->customer_id = $customer->id;
                $transection->order_id = $order_id;
                $transection->save();
                //transection end
                //account
                $account->balance = $account->balance + $total_price + $total_tax_amount - $ext_discount - $coupon_discount;
                $account->total_in = $account->total_in + $total_price + $total_tax_amount - $ext_discount - $coupon_discount;
                $account->save();
            }
            foreach ($order_details as $key => $item) {
                $order_details[$key]['order_id'] = $order->id;
            }
            $this->order_details->insert($order_details);

            session()->forget($cart_id);
            session(['last_order' => $order->id]);
            Toastr::success(translate('order_placed_successfully'));
            return back();
        } catch (\Exception $e) {
            Toastr::warning(translate('failed_to_place_order'));
            return back();
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function search_product(Request $request): JsonResponse
    {

        $request->validate([
            'name' => 'required',
        ], [
            'name.required' => translate('Product name is required'),
        ]);

        $key = explode(' ', $request['name']);
        $products = $this->product->where('quantity', '>', 0)->active()->where(function ($q) use ($key) {
            foreach ($key as $value) {
                $q->where('name', 'like', "%{$value}%");
            }
        })->orWhere(function ($q) use ($key) {
            foreach ($key as $value) {
                $q->where('product_code', 'like', "%{$value}%");
            }
        })->paginate(6);

        $count_p = $products->count();

        return response()->json([
            'result' => view('admin-views.pos._search-result', compact('products'))->render(),
            'count' => $count_p
        ]);
    }


    /**
     * @param Request $request
     * @return JsonResponse|void
     */
    public function search_by_add_product(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ], [
            'name.required' => translate('Product name is required'),
        ]);

        if (is_numeric($request['name'])) {
            $products = $this->product->where('quantity', '>', 0)->active()->where('product_code', $request['name'])->paginate(6);
        } else {
            $products = $this->product->where('quantity', '>', 0)->active()->where('name', $request['name'])->paginate(6);
        }

        $count_p = $products->count();
        if ($count_p > 0) {
            return response()->json([
                'count' => $count_p,
                'id' => $products[0]->id,
            ]);
        }
    }

    /**
     * @param Request $request
     * @return Application|Factory|View
     */
    public function order_list(Request $request): Factory|View|Application
    {
        $query_param = [];
        $search = $request['search'];
        if ($request->has('search')) {
            $orders = $this->order->latest()->where('id', 'like', "%{$search}%")->paginate(Helpers::pagination_limit())->appends($search);
        } else {
            $orders = $this->order->latest()->paginate(Helpers::pagination_limit())->appends($search);
        }

        return view('admin-views.pos.order.list', compact('orders', 'search'));
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function generate_invoice($id)
    {
        $order = $this->order->where('id', $id)->with(['details'])->first();
        //return $order;
        return response()->json([
            'success' => 1,
            'view' => view('admin-views.pos.order.invoice', compact('order'))->render(),
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function get_customers(Request $request): JsonResponse
    {
        $key = explode(' ', $request['q']);
        $data = DB::table('customers')
            ->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('name', 'like', "%{$value}%")
                        ->orWhere('mobile', 'like', "%{$value}%");
                }
            })->limit(6)
            ->get([DB::raw('id, IF(id <> "0",CONCAT(name,  " (", mobile ,")"), name) as text')]);

        return response()->json($data);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function customer_balance(Request $request): JsonResponse
    {
        $customer_balance = $this->customer->where('id', $request->customer_id)->first()->balance;
        return response()->json([
            'customer_balance' => $customer_balance
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function remove_coupon(Request $request): JsonResponse
    {
        $cart_id = ($request->user_id != 0 ? 'sc-' . $request->user_id : 'wc-' . rand(10, 1000));
        if (!in_array($cart_id, session('cart_name') ?? [])) {
            session()->push('cart_name', $cart_id);
        }

        $cart = session(session('current_user'));

        $cart_keeper = [];
        if (session()->has(session('current_user')) && count($cart) > 0) {
            foreach ($cart as $cartItem) {

                array_push($cart_keeper, $cartItem);
            }
        }
        if (session('current_user') != $cart_id) {
            $temp_cart_name = [];
            foreach (session('cart_name') as $cart_name) {
                if ($cart_name != session('current_user')) {
                    $temp_cart_name[] = $cart_name;
                }
            }
            session()->put('cart_name', $temp_cart_name);
        }
        session()->put('cart_name', $temp_cart_name);
        session()->forget(session('current_user'));
        session()->put($cart_id, $cart_keeper);
        session()->put('current_user', $cart_id);
        $user_id = explode('-', session('current_user'))[1];
        $current_customer = '';
        if (explode('-', session('current_user'))[0] == 'wc') {
            $current_customer = 'Walking Customer';
        } else {
            $current = $this->customer->where('id', $user_id)->first();
            $current_customer = $current->name . ' (' . $current->mobile . ')';
        }

        return response()->json([
            'cart_nam' => session('cart_name'),
            'current_user' => session('current_user'),
            'current_customer' => $current_customer,
            'view' => view('admin-views.pos._cart', compact('cart_id'))->render()
        ]);
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function change_cart(Request $request): RedirectResponse
    {

        session()->put('current_user', $request->cart_id);

        return redirect()->route('admin.pos.index');
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function new_cart_id(Request $request): RedirectResponse
    {
        $cart_id = 'wc-' . rand(10, 1000);
        session()->put('current_user', $cart_id);
        if (!in_array($cart_id, session('cart_name') ?? [])) {
            session()->push('cart_name', $cart_id);
        }

        return redirect()->route('admin.pos.index');
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function get_cart_ids(Request $request): JsonResponse
    {
        $cart_id = session('current_user');
        $user_id = 0;
        $user_type = 'wc';
        if (Str::contains(session('current_user'), 'sc')) {
            $user_id = explode('-', session('current_user'))[1];
            $user_type = 'sc';
        }
        $cart = session($cart_id);
        $cart_keeper = [];
        if (session()->has($cart_id) && count($cart) > 0) {
            foreach ($cart as $cartItem) {
                $cart_keeper[] = $cartItem;
            }
        }
        session()->put(session('current_user'), $cart_keeper);
        $user_id = explode('-', session('current_user'))[1];
        $current_customer = '';
        if (explode('-', session('current_user'))[0] == 'wc') {
            $current_customer = 'Walking Customer';
        } else {
            $current = $this->customer->where('id', $user_id)->first();
            $current_customer = $current->name . ' (' . $current->mobile . ')';
        }
        return response()->json([
            'current_user' => session('current_user'),
            'cart_nam' => session('cart_name') ?? '',
            'current_customer' => $current_customer,
            'user_type' => $user_type,
            'user_id' => $user_id,
            'view' => view('admin-views.pos._cart', compact('cart_id'))->render()
        ]);
    }
}
