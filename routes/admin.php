<?php

use App\Models\Product;
use Illuminate\Support\Facades\Route;

Route::group(['namespace'=>'Admin', 'as' => 'admin.', 'prefix'=>'admin'] ,function(){
    Route::group(['namespace' => 'Auth', 'prefix' => 'auth', 'as' => 'auth.'], function(){
        Route::get('login', 'LoginController@login')->name('login');
        Route::post('login', 'LoginController@submit');
        Route::get('logout', 'LoginController@logout')->name('logout');
    });

    Route::group(['middleware' => ['admin']], function(){
        Route::get('/', 'DashboardController@dashboard')->name('dashboard');
        Route::post('account-status','DashboardController@account_stats')->name('account-status');
        Route::get('settings', 'SystemController@settings')->name('settings');
        Route::post('settings', 'SystemController@settings_update');
        Route::get('settings-password', 'SystemController@settings')->name('settings.password');
        Route::post('settings-password', 'SystemController@settings_password_update')->name('settings-password');

        Route::group(['prefix' => 'category', 'as' => 'category.'], function () {
            Route::get('add', 'CategoryController@index')->name('add');
            Route::get('add-sub-category', 'CategoryController@sub_index')->name('add-sub-category');
            //Route::get('add-sub-sub-category', 'CategoryController@sub_sub_index')->name('add-sub-sub-category');
            Route::post('store', 'CategoryController@store')->name('store');
            Route::get('edit/{id}', 'CategoryController@edit')->name('edit');
            Route::get('sub-edit/{id}', 'CategoryController@edit_sub')->name('sub-edit');
            Route::post('update/{id}', 'CategoryController@update')->name('update');
            Route::post('update-sub/{id}', 'CategoryController@update_sub')->name('update-sub');
            Route::post('store', 'CategoryController@store')->name('store');
            Route::get('status/{id}/{status}', 'CategoryController@status')->name('status');
            Route::delete('delete/{id}', 'CategoryController@delete')->name('delete');
            //Route::post('search', 'CategoryController@search')->name('search');
        });

        Route::group(['prefix' => 'brand', 'as' => 'brand.'], function () {
            Route::get('add', 'BrandController@index')->name('add');
            Route::post('store','BrandController@store')->name('store');
            Route::get('edit/{id}', 'BrandController@edit')->name('edit');
            Route::post('update/{id}', 'BrandController@update')->name('update');
            Route::delete('delete/{id}', 'BrandController@delete')->name('delete');
        });
        //unit
        Route::group(['prefix' => 'unit', 'as' => 'unit.'], function () {
            Route::get('index', 'UnitController@index')->name('index');
            Route::post('store', 'UnitController@store')->name('store');
            Route::get('edit/{id}', 'UnitController@edit')->name('edit');
            Route::post('update/{id}', 'UnitController@update')->name('update');
             Route::delete('delete/{id}', 'UnitController@delete')->name('delete');
        });

        Route::group(['prefix' => 'product', 'as' => 'product.'], function () {
            Route::get('add', 'ProductController@index')->name('add');
            Route::post('store', 'ProductController@store')->name('store');
            Route::get('list', 'ProductController@list')->name('list');
            Route::get('edit/{id}', 'ProductController@edit')->name('edit');
            Route::post('update/{id}', 'ProductController@update')->name('update');
            Route::delete('delete/{id}', 'ProductController@delete')->name('delete');
            Route::get('barcode-generate/{id}', 'ProductController@barcode_generate')->name('barcode-generate');
            Route::get('barcode/{id}', 'ProductController@barcode')->name('barcode');
            Route::get('bulk-import', 'ProductController@bulk_import_index')->name('bulk-import');
            Route::post('bulk-import', 'ProductController@bulk_import_data');
            Route::get('bulk-export', 'ProductController@bulk_export_data')->name('bulk-export');

            //ajax request
            Route::get('get-categories', 'ProductController@get_categories')->name('get-categories');
            Route::get('remove-image/{id}/{name}', 'ProductController@remove_image')->name('remove-image');
        });

        Route::group(['prefix' => 'pos', 'as' => 'pos.'], function () {
            Route::get('/', 'POSController@index')->name('index');
            Route::get('quick-view', 'POSController@quick_view')->name('quick-view');
            Route::post('variant_price', 'POSController@variant_price')->name('variant_price');
            Route::post('add-to-cart', 'POSController@addToCart')->name('add-to-cart');
            Route::post('remove-from-cart', 'POSController@removeFromCart')->name('remove-from-cart');
            Route::post('cart-items', 'POSController@cart_items')->name('cart_items');
            Route::post('update-quantity', 'POSController@updateQuantity')->name('updateQuantity');
            Route::post('empty-cart', 'POSController@emptyCart')->name('emptyCart');
            Route::post('tax', 'POSController@update_tax')->name('tax');
            Route::post('discount', 'POSController@update_discount')->name('discount');
            Route::get('customers', 'POSController@get_customers')->name('customers');
            Route::get('customer-balance', 'POSController@customer_balance')->name('customer-balance');
            Route::post('order', 'POSController@place_order')->name('order');
            Route::get('orders', 'POSController@order_list')->name('orders');
            Route::get('order-details/{id}', 'POSController@order_details')->name('order-details');
            Route::get('invoice/{id}', 'POSController@generate_invoice');
            Route::get('search-products','POSController@search_product')->name('search-products');
            Route::get('search-by-add','POSController@search_by_add_product')->name('search-by-add');

            Route::post('coupon-discount', 'POSController@coupon_discount')->name('coupon-discount');
            Route::post('remove-coupon','POSController@remove_coupon')->name('remove-coupon');
            Route::get('change-cart','POSController@change_cart')->name('change-cart');
            Route::get('new-cart-id','POSController@new_cart_id')->name('new-cart-id');
            Route::get('clear-cart-ids','POSController@clear_cart_ids')->name('clear-cart-ids');
            Route::get('get-cart-ids','POSController@get_cart_ids')->name('get-cart-ids');
        });

        //account
        Route::group(['prefix' => 'account', 'as' => 'account.'], function () {
            Route::get('add','AccountController@add')->name('add');
            Route::post('store', 'AccountController@store')->name('store');
            Route::get('list', 'AccountController@list')->name('list');
            Route::get('view/{id}', 'AccountController@view')->name('view');
            Route::get('edit/{id}', 'AccountController@edit')->name('edit');
            Route::post('update/{id}', 'AccountController@update')->name('update');
            Route::delete('delete/{id}', 'AccountController@delete')->name('delete');

            //expense
            Route::get('add-expense','ExpenseController@add')->name('add-expense');
            Route::post('store-expense', 'ExpenseController@store')->name('store-expense');

            //income
            Route::get('add-income', 'IncomeController@add')->name('add-income');
            Route::post('store-income', 'IncomeController@store')->name('store-income');
            //transfer
            Route::get('add-transfer', 'TransferController@add')->name('add-transfer');
            Route::post('store-transfer', 'TransferController@store')->name('store-transfer');
            //transection
            Route::get('list-transection', 'TransectionController@list')->name('list-transection');
            Route::get('transection-export', 'TransectionController@export')->name('transection-export');

            //payable
            Route::get('add-payable', 'PayableController@add')->name('add-payable');
            Route::post('store-payable', 'PayableController@store')->name('store-payable');
            Route::post('payable-transfer','PayableController@transfer')->name('payable-transfer');

            //receivable
            Route::get('add-receivable', 'ReceivableController@add')->name('add-receivable');
            Route::post('store-receivable', 'ReceivableController@store')->name('store-receivable');
            Route::post('receivable-transfer','ReceivableController@transfer')->name('receivable-transfer');
        });

        //customer
        Route::group(['prefix' => 'customer', 'as' => 'customer.'], function () {
            Route::get('add','CustomerController@index')->name('add');
            Route::post('store', 'CustomerController@store')->name('store');
            Route::get('list', 'CustomerController@list')->name('list');
            Route::get('view/{id}', 'CustomerController@view')->name('view');
            Route::get('edit/{id}', 'CustomerController@edit')->name('edit');
            Route::post('update/{id}', 'CustomerController@update')->name('update');
            Route::delete('delete/{id}', 'CustomerController@delete')->name('delete');
            Route::post('update-balance','CustomerController@update_balance')->name('update-balance');
            Route::get('transaction-list/{id}', 'CustomerController@transaction_list')->name('transaction-list');
        });
        //supplier
        Route::group(['prefix' => 'supplier', 'as' => 'supplier.'], function () {
            Route::get('add','SupplierController@index')->name('add');
            Route::post('store', 'SupplierController@store')->name('store');
            Route::get('list', 'SupplierController@list')->name('list');
            Route::get('view/{id}', 'SupplierController@view')->name('view');
            Route::get('edit/{id}', 'SupplierController@edit')->name('edit');
            Route::post('update/{id}', 'SupplierController@update')->name('update');
            Route::delete('delete/{id}', 'SupplierController@delete')->name('delete');
            Route::get('products/{id}', 'SupplierController@product_list')->name('products');
            Route::get('transaction-list/{id}', 'SupplierController@transaction_list')->name('transaction-list');
            Route::post('add-new-purchase','SupplierController@add_new_purchase')->name('add-new-purchase');
            Route::post('pay-due','SupplierController@pay_due')->name('pay-due');
        });
        //stock limit
        Route::group(['prefix' => 'stock', 'as' => 'stock.'], function () {
            Route::get('stock-limit', 'StocklimitController@stock_limit')->name('stock-limit');
            Route::post('update-quantity', 'StocklimitController@update_quantity')->name('update-quantity');
        });
        //business settings
        Route::group(['prefix' => 'business-settings', 'as' => 'business-settings.','middleware'=>['actch']], function () {
            Route::get('shop-setup', 'BusinessSettingsController@shop_index')->name('shop-setup');
            Route::post('update-setup', 'BusinessSettingsController@shop_setup')->name('update-setup');
            Route::get('shortcut-keys', 'BusinessSettingsController@shortcut_key')->name('shortcut-keys');
        });

        //coupon
        Route::group(['prefix' => 'coupon', 'as' => 'coupon.'], function () {
            Route::get('add-new', 'CouponController@add_new')->name('add-new');
            Route::post('store', 'CouponController@store')->name('store');
            Route::get('edit/{id}', 'CouponController@edit')->name('edit');
            Route::post('update/{id}', 'CouponController@update')->name('update');
            Route::get('status/{id}/{status}', 'CouponController@status')->name('status');
            Route::delete('delete/{id}', 'CouponController@delete')->name('delete');
        });
    });
});
