@extends('layouts.admin.app')

@section('title',\App\CPU\translate('customer_details'))

@push('css_or_js')
    <link rel="stylesheet" href="{{asset('public/assets/admin')}}/css/custom.css"/>
@endpush

@section('content')
<div class="content container-fluid">
        <!-- Page Header -->
        <div class="d-print-none pb-2">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <div class="page-header">
                        <div class="js-nav-scroller hs-nav-scroller-horizontal">
                            <ul class="nav nav-tabs page-header-tabs">
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('admin.customer.view',[$customer['id']]) }}">{{\App\CPU\translate('order_list')}}</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link active" href="{{ route('admin.customer.transaction-list',[$customer['id']]) }}">{{\App\CPU\translate('transaction_list')}}</a>
                                </li>

                            </ul>

                        </div>
                    </div>
                    <div class="d-sm-flex align-items-sm-center">
                        <h4 class="page-header-title">{{\App\CPU\translate('customer')}} {{\App\CPU\translate('id')}}
                            #{{$customer['id']}}</h4>
                        <span class="ml-2 ml-sm-3">
                        <i class="tio-date-range">
                        </i> {{\App\CPU\translate('joined_at')}} : {{date('d M Y H:i:s',strtotime($customer['created_at']))}}
                        </span>
                    </div>

                </div>
            </div>
        </div>
        <!-- End Page Header -->
        <div class="row" id="">
            <div class="col-lg-8 mb-3 mb-lg-0">
                <div class="card">
                    <div class="card-header">
                        <div class="w-100">
                            <h3>{{\App\CPU\translate('transaction_list')}}
                                <span class="badge badge-soft-dark ml-2">{{$transactions->total()}}</span>
                            </h3>
                            <form action="{{url()->current()}}" method="GET">
                                <div class="row">
                                    {{-- <div class="col-lg-5 mb-3 mb-lg-0"> --}}
                                        <div class="form-group col-12 col-sm-5">
                                            <label class="input-label" for="exampleFormControlInput1">{{\App\CPU\translate('account')}} </label>
                                            <select id="account_id" name="account_id" class="form-control js-select2-custom">
                                                <option value="">---{{\App\CPU\translate('select')}}---</option>
                                                @foreach ($accounts as $account)

                                                        <option value="{{$account['id']}}" {{ $acc_id==$account['id']?'selected':''}}>{{$account['account']}}</option>

                                                @endforeach
                                            </select>
                                        </div>
                                    {{-- </div>
                                    <div class="col-lg-5 mb-3 mb-lg-0"> --}}
                                        <div class="form-group col-12 col-sm-5">
                                            <label class="input-label" for="exampleFormControlInput1">{{\App\CPU\translate('type')}} </label>
                                            <select id="tran_type" name="tran_type" class="form-control js-select2-custom">
                                                <option value="">---{{\App\CPU\translate('select')}}---</option>
                                                <option value="Income" {{ $tran_type=='Income'?'selected':''}}>{{\App\CPU\translate('income')}}</option>
                                                <option value="Payable" {{ $tran_type=='Payable'?'selected':''}}>{{\App\CPU\translate('payable')}}</option>
                                                <option value="Receivable" {{ $tran_type=='Receivable'?'selected':''}}>{{\App\CPU\translate('receivable')}}</option>
                                            </select>
                                        </div>
                                    {{-- </div> --}}
                                    <div class="text-center col-12 col-sm-2  mt-sm-5">
                                        <button class="btn btn-success">{{\App\CPU\translate('filter')}}</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- Table -->
                    <div class="table-responsive datatable-custom">
                        <table
                               class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
                               >
                            <thead class="thead-light">
                            <tr>
                                <th>{{\App\CPU\translate('#')}}</th>
                                <th>{{ \App\CPU\translate('account') }}</th>
                                <th>{{ \App\CPU\translate('type') }}</th>
                                <th>{{ \App\CPU\translate('amount') }}</th>
                                <th >{{\App\CPU\translate('date')}}</th>
                                <th >{{\App\CPU\translate('debit')}}</th>
                                <th>{{\App\CPU\translate('credit')}}</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach($transactions as $key=>$transaction)
                                <tr>
                                    <td>{{$transactions->firstItem()+$key}}</td>
                                    <td>{{ $transaction->account?->account}}</td>
                                    <td>
                                        @if ($transaction->tran_type == 'Expense')
                                            <span class="badge badge-danger">
                                                {{ $transaction->tran_type}} <br>
                                            </span>
                                        @elseif($transaction->tran_type == 'Deposit')
                                            <span class="badge badge-info">
                                                {{ $transaction->tran_type}} <br>
                                            </span>
                                        @elseif($transaction->tran_type == 'Transfer')
                                            <span class="badge badge-warning">
                                                {{ $transaction->tran_type}} <br>
                                            </span>
                                        @elseif($transaction->tran_type == 'Income')
                                            <span class="badge badge-success">
                                                {{ $transaction->tran_type}} <br>
                                            </span>
                                        @elseif($transaction->tran_type == 'Payable')
                                            <span class="badge badge-soft-warning">
                                                {{ $transaction->tran_type}} <br>
                                            </span>
                                        @elseif($transaction->tran_type == 'Receivable')
                                            <span class="badge badge-soft-success">
                                                {{ $transaction->tran_type}} <br>
                                            </span>
                                        @endif
                                    </td>
                                    <td>{{ $transaction->amount ." ".\App\CPU\Helpers::currency_symbol()}}</td>

                                    <td>{{ $transaction->date }}</td>
                                    <td>
                                        @if ($transaction->debit)
                                                {{ $transaction->amount ." ".\App\CPU\Helpers::currency_symbol()}}
                                        @else
                                                {{ 0 ." ".\App\CPU\Helpers::currency_symbol()}}
                                        @endif
                                    </td>
                                    <td>
                                        @if ($transaction->credit)
                                                {{ $transaction->amount ." ".\App\CPU\Helpers::currency_symbol()}}
                                            @else
                                                 {{ 0 ." ".\App\CPU\Helpers::currency_symbol()}}
                                            @endif
                                    </td>

                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <!-- Footer -->
                        <div class="page-area">
                            <table>
                                <tfoot class="border-top">
                                    {!! $transactions->links() !!}
                                </tfoot>
                            </table>
                        </div>
                        @if(count($transactions)==0)
                            <div class="text-center p-4">
                                <img class="mb-3 w-one-tl" src="{{asset('public/assets/admin')}}/svg/illustrations/sorry.svg" alt="{{\App\CPU\translate('Image Description')}}">
                                <p class="mb-0">{{ \App\CPU\translate('No_data_to_show')}}</p>
                            </div>
                        @endif
                        <!-- End Footer -->
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Card -->
                <div class="card">
                    <!-- Header -->
                    <div class="card-header">
                        <h4 class="card-header-title">{{\App\CPU\translate('customer')}}</h4>
                    </div>
                    <!-- End Header -->

                    <!-- Body -->
                    @if($customer)
                        <div class="card-body">
                            <div class="media align-items-center" href="javascript:">
                                <div class="avatar avatar-circle mr-3">
                                    <img
                                        class="avatar-img"
                                        onerror="this.src='{{asset('public/assets/admin/img/160x160/img1.jpg')}}'"
                                        src="{{asset('storage/app/public/customer/'.$customer->image)}}"
                                        alt="{{\App\CPU\translate('image_description')}}">
                                </div>
                                <div class="media-body">
                                <span
                                    class="text-body text-hover-primary">{{$customer['name']}}</span>
                                </div>
                            </div>

                            <hr>

                            <div class="media align-items-center" href="javascript:">
                                <div class="icon icon-soft-info icon-circle mr-3">
                                    <i class="tio-shopping-basket-outlined"></i>
                                </div>
                                <div class="media-body">
                                    <span
                                        class="text-body text-hover-primary">{{ $orders->count() }} {{\App\CPU\translate('orders')}}</span>
                                </div>
                                <div class="media-body text-right">
                                    {{--<i class="tio-chevron-right text-body"></i>--}}
                                </div>
                            </div>
                            <div class="media align-items-center mt-1" href="javascript:">
                                <div class="icon icon-soft-info icon-circle mr-3">
                                    <i class="tio-money"></i>
                                </div>
                                <div class="media-body">
                                    <span
                                        class="text-body text-hover-primary">{{$customer->balance. ' ' . \App\CPU\Helpers::currency_symbol() }}</span>
                                </div>
                            </div>

                            @if($customer->id != 0)
                                <hr>

                                <div class="d-flex justify-content-between align-items-center">
                                    <h5>{{\App\CPU\translate('contact_info')}}</h5>
                                </div>

                                <ul class="list-unstyled list-unstyled-py-2">

                                    <li>
                                        <i class="tio-android-phone-vs mr-2"></i>
                                        {{$customer['mobile']}}
                                    </li>
                                    @if ($customer['email'])
                                        <li>
                                            <i class="tio-online mr-2"></i>
                                            {{$customer['email']}}
                                        </li>
                                    @endif
                                </ul>
                                <hr>
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5>{{\App\CPU\translate('addresses')}}</h5>
                                </div>
                                <ul class="list-unstyled list-unstyled-py-2">
                                    <li>{{\App\CPU\translate('state')}}: {{$customer['state']}}</li>
                                    <li>{{\App\CPU\translate('city')}}: {{$customer['city']}}</li>
                                    <li>{{\App\CPU\translate('zip_code')}}: {{$customer['zip_code']}}</li>
                                    <li>{{\App\CPU\translate('address')}}: {{$customer['address']}}</li>
                                </ul>
                            @endif



                        </div>
                @endif
                <!-- End Body -->
                </div>
                <!-- End Card -->
            </div>
        </div>
        <!-- End Row -->
    </div>
<div class="modal fade" id="print-invoice" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{\App\CPU\translate('print')}} {{\App\CPU\translate('invoice')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body row font-one-tl">
                    <div class="col-md-12">
                        <center>
                            <input type="button" class="btn btn-primary non-printable" onclick="printDiv('printableArea')"
                                value="{{\App\CPU\translate('Proceed, If thermal printer is ready')}}."/>
                            <a href="{{url()->previous()}}" class="btn btn-danger non-printable">{{\App\CPU\translate('Back')}}</a>
                        </center>
                        <hr class="non-printable">
                    </div>
                    <div class="row m-auto" id="printableArea">

                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

@push('script_2')
    <script>
        "use strict";
        function print_invoice(order_id) {
            $.get({
                url: '{{url('/')}}/admin/pos/invoice/'+order_id,
                dataType: 'json',
                beforeSend: function () {
                    $('#loading').show();
                },
                success: function (data) {
                    console.log("success...")
                    $('#print-invoice').modal('show');
                    $('#printableArea').empty().html(data.view);
                },
                complete: function () {
                    $('#loading').hide();
                },
            });
        }
    </script>

    <script src={{asset("public/assets/admin/js/global.js")}}></script>
@endpush
