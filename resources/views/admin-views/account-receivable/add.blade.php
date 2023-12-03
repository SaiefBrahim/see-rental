@extends('layouts.admin.app')

@section('title',\App\CPU\translate('add_new_receivable'))

@push('css_or_js')

@endpush

@section('content')
<div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <h1 class="page-header-title"><i
                            class="tio-add-circle-outlined"></i> {{\App\CPU\translate('add_new_receivable_balance')}}
                    </h1>
                </div>
            </div>
        </div>
        <!-- End Page Header -->
        <div class="row gx-2 gx-lg-3">
            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <form action="{{route('admin.account.store-receivable')}}" method="post" >
                    @csrf
                        <div class="row pl-2" >
                            <div class="col-6">
                                <div class="form-group">
                                    <label class="input-label" for="exampleFormControlInput1">{{\App\CPU\translate('account')}}</label>
                                    <h2>{{\App\CPU\translate('account_receivable')}}</h2>
                                    <input type="hidden" name="account_id" value="3">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label class="input-label">{{\App\CPU\translate('description')}} </label>
                                    <input type="text" name="description" class="form-control" placeholder="{{\App\CPU\translate('description')}}" >
                                </div>
                            </div>
                        </div>
                        <div class="row pl-2" >
                            <div class="col-6">
                                <div class="form-group">
                                    <label class="input-label" >{{\App\CPU\translate('amount')}}</label>
                                    <input type="number" step="0.01" min="0" name="amount" class="form-control" placeholder="{{\App\CPU\translate('amount')}}" required>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label class="input-label" for="exampleFormControlInput1">{{\App\CPU\translate('date')}} </label>
                                    <input type="date" name="date" class="form-control" required>
                                </div>
                            </div>
                        </div>
                    <hr>
                    <button type="submit" class="btn btn-primary">{{\App\CPU\translate('submit')}}</button>
                </form>
            </div>
        </div>
    </div>
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <h1 class="page-header-title"><i
                            class="tio-files"></i> {{\App\CPU\translate('receivable_list')}}</h1>
                </div>
            </div>
        </div>
        <!-- End Page Header -->
        <div class="row gx-2 gx-lg-3">
            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <!-- Card -->
                <div class="card">
                    <!-- Header -->
                    <div class="card-header">
                        <div class="row justify-content-between align-items-center flex-grow-1">
                            <div class="col-lg-5 mb-3 mb-lg-0">
                                <form action="{{url()->current()}}" method="GET">
                                    <!-- Search -->
                                    <div class="input-group input-group-merge input-group-flush">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="tio-search"></i>
                                            </div>
                                        </div>
                                        <input id="datatableSearch_" type="search" name="search" class="form-control"
                                               placeholder="{{\App\CPU\translate('search_by_description')}}" value="{{ $search }}"   required>
                                        <button type="submit" class="btn btn-primary">{{\App\CPU\translate('search')}} </button>

                                    </div>
                                    <!-- End Search -->
                                </form>
                            </div>
                            <div class="col-7">
                                <form action="{{url()->current()}}" method="GET">
                                <div class="row">
                                    <div class="col-md-5">
                                    <div class="form-group">
                                        <label class="input-label" for="exampleFormControlInput1">{{\App\CPU\translate('from')}} </label>
                                        <input type="date" name="from" class="form-control" value="{{ $from }}" required>
                                    </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label class="input-label" for="exampleFormControlInput1">{{\App\CPU\translate('to')}} </label>
                                            <input type="date" name="to" class="form-control" value="{{ $to }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <button href="" class="btn btn-success mt-4"> {{\App\CPU\translate('filter')}}</button>
                                    </div>
                                </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- End Header -->

                    <!-- Table -->
                    <div class="table-responsive datatable-custom">
                        <table class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                            <thead class="thead-light">
                            <tr>
                                <th>{{ \App\CPU\translate('date') }}</th>
                                <th >{{ \App\CPU\translate('account') }}</th>
                                <th>{{\App\CPU\translate('type')}}</th>
                                <th>{{\App\CPU\translate('amount')}}</th>
                                <th class="w-one-payable">{{\App\CPU\translate('description')}}</th>
                                <th>{{ \App\CPU\translate('debit') }}</th>
                                <th >{{\App\CPU\translate('credit')}}</th>
                                <th >{{\App\CPU\translate('balance')}}</th>
                                <th >{{\App\CPU\translate('action')}}</th>
                            </tr>
                            </thead>

                            <tbody>
                                @foreach ($receivables as $key=>$receivable)
                                    <tr>
                                        <input type="hidden" id="available_balance-{{ $receivable->id }}" value="{{ $receivable->amount }}">
                                        <td>{{ $receivable->date }}</td>
                                        <td>
                                            {{ $receivable->account->account}} <br>
                                        </td>
                                        <td>
                                            <span class="badge badge-info">
                                                {{ $receivable->tran_type}} <br>
                                            </span>
                                        </td>
                                        <td>
                                            {{ $receivable->amount ." ".\App\CPU\Helpers::currency_symbol()}}
                                        </td>
                                        <td>
                                            {{ Str::limit($receivable->description,30) }}
                                        </td>
                                        <td>
                                            @if ($receivable->debit)
                                                {{ $receivable->amount ." ".\App\CPU\Helpers::currency_symbol()}}
                                            @else
                                                 {{ 0 ." ".\App\CPU\Helpers::currency_symbol()}}
                                            @endif
                                        </td>
                                        <td>
                                            @if ($receivable->credit)
                                                {{ $receivable->amount ." ".\App\CPU\Helpers::currency_symbol()}}
                                            @else
                                                 {{ 0 ." ".\App\CPU\Helpers::currency_symbol()}}
                                            @endif
                                        </td>
                                        <td>
                                            {{ $receivable->balance ." ".\App\CPU\Helpers::currency_symbol()}}
                                        </td>
                                        <td>
                                            <button class="btn btn-sm" id="{{ $receivable->id }}" onclick="balance_transfer_rec({{ $receivable->id }})" type="button" data-toggle="modal" data-target="#balance-transfer">
                                            <i class="tio-edit"></i>
                                        </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="page-area">
                            <table>
                                <tfoot class="border-top">
                                {!! $receivables->links() !!}
                                </tfoot>
                            </table>
                        </div>
                        @if(count($receivables)==0)
                            @include('layouts.admin.partials._no-data-section')
                        @endif
                    </div>
                    <!-- End Table -->
                </div>
                <!-- End Card -->
            </div>
        </div>
    </div>
<div class="modal fade" id="balance-transfer" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{\App\CPU\translate('payable_balance_transfer_rec')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{route('admin.account.receivable-transfer')}}" method="post" class="row">
                    @csrf
                    <input type="hidden" id="transection_id" name="transection_id">
                    <div class="col-6">
                        <div class="form-group">
                            <label class="input-label" for="exampleFormControlInput1">{{\App\CPU\translate('account')}}</label>
                            <h2>{{\App\CPU\translate('account_receivable')}}</h2>
                            <input type="hidden" name="account_id" value="3">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label class="input-label" for="exampleFormControlInput1">{{\App\CPU\translate('payment_from')}}</label>
                            <select name="receive_account_id" class="form-control js-select2-custom" >
                                @foreach ($accounts as $account)
                                @if ($account['id']!=2 && $account['id']!=3)
                                    <option value="{{$account['id']}}">{{$account['account']}}</option>
                                @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label class="input-label" >{{\App\CPU\translate('avaiable_balance')}}</label>
                            <input id="payment_balance" type="number" step="0.01" min= "0" name="amount" class="form-control" placeholder="{{\App\CPU\translate('amount')}}" required>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label class="input-label" for="exampleFormControlInput1">{{\App\CPU\translate('date')}} </label>
                            <input type="date" name="date" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label class="input-label">{{\App\CPU\translate('description')}} </label>
                            <input type="text" name="description" class="form-control" placeholder="{{\App\CPU\translate('description')}}" >
                        </div>
                    </div>
                    <div class="form-group col-sm-12">
                        <button class="btn btn-sm btn-primary" type="submit">{{\App\CPU\translate('submit')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script_2')
    <script src={{asset("public/assets/admin/js/accounts.js")}}></script>
@endpush
