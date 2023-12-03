@extends('layouts.admin.app')

@section('title',\App\CPU\translate('Settings'))

@section('content')
    <div class="content container-fluid">
        <div class="row">
            <div class="col-6">
                <div class="card">
                    <div class="card-header">
                        <h3>{{\App\CPU\translate('short_cut_key_list')}}</h3>
                    </div>
                    <div class="card-body">
                        <span>{{\App\CPU\translate('to_click_order')}} : alt + O</span><br>
                        <span>{{\App\CPU\translate('to_click_payment_submit')}} : alt + S</span><br>
                        <span>{{\App\CPU\translate('to_click_cancel_cart_item_all')}} : alt + C</span><br>
                        <span>{{\App\CPU\translate('to_click_add_new_customer')}} : alt + A</span> <br>
                        <span>{{\App\CPU\translate('to_click_add_new_customer_form')}} : alt + N</span><br>
                        <span>{{\App\CPU\translate('to_click_short_cut_keys')}} : alt + K</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
