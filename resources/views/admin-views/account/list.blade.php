@extends('layouts.admin.app')

@section('title',\App\CPU\translate('account_list'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="mb-3">
            <h1 class="page-header-title d-flex align-items-center g-2px text-capitalize">
                <i class="tio-filter-list"></i>
                <span>{{\App\CPU\translate('account_list')}} <span
                        class="badge badge-soft-dark ml-2">{{$accounts->total()}}</span></span>
            </h1>
        </div>
        <!-- End Page Header -->
        <div class="row gx-2 gx-lg-3">
            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <!-- Card -->
                <div class="card">
                    <!-- Header -->
                    <div class="card-header">
                        <div class="row justify-content-between align-items-center flex-grow-1">
                            <div class="col-10 mb-1 mb-md-0 col-sm-7 col-md-6">
                                <form action="{{url()->current()}}" method="GET">
                                    <!-- Search -->
                                    <div class="input-group input-group-merge input-group-flush">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="tio-search"></i>
                                            </div>
                                        </div>
                                        <input id="datatableSearch_" type="search" name="search" class="form-control"
                                               placeholder="{{\App\CPU\translate('search_by_account_title')}}"
                                               value="{{ $search }}" required>
                                        <button type="submit"
                                                class="btn btn-primary">{{\App\CPU\translate('search')}} </button>

                                    </div>
                                    <!-- End Search -->
                                </form>
                            </div>
                            <div class="col-12 col-sm-5  col-md-4">
                                <a href="{{route('admin.account.add')}}" class="btn btn-primary float-right"><i
                                        class="tio-add-circle"></i> {{\App\CPU\translate('add_new_account')}}
                                </a>
                            </div>
                        </div>
                    </div>
                    <!-- End Header -->

                    <!-- Table -->
                    <div class="table-responsive datatable-custom">
                        <table
                            class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                            <thead class="thead-light">
                            <tr>
                                <th>{{\App\CPU\translate('#')}}</th>
                                <th>{{\App\CPU\translate('account_info')}}</th>
                                <th>{{\App\CPU\translate('balance_info')}}</th>
                                <th class="w-fp-acc">{{\App\CPU\translate('action')}}</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach ($accounts as $key=>$account)
                                <tr>
                                    <td>{{ $accounts->firstItem()+$key }}</td>
                                    <td>
                                        {{ $account->account }} <br>

                                        @if ($account->id !=1 && $account->id !=2 && $account->id !=3)
                                            {{ $account->account_number }} <br>
                                            {{ $account->description }}
                                        @endif
                                    </td>

                                    <td>
                                        {{\App\CPU\translate('balance')}}
                                        : {{ $account->balance . ' ' . \App\CPU\Helpers::currency_symbol()}} <br>
                                        {{\App\CPU\translate('total_in')}}
                                        : {{ $account->total_in ? $account->total_in . ' ' . \App\CPU\Helpers::currency_symbol() : 0   . ' ' . \App\CPU\Helpers::currency_symbol()}}
                                        <br>
                                        {{ \App\CPU\translate('total_out') }}
                                        : {{ $account->total_out ? $account->total_out . ' ' . \App\CPU\Helpers::currency_symbol() : 0 . ' ' . \App\CPU\Helpers::currency_symbol()}} <br>
                                    </td>
                                    <td>
                                        @if ($account->id !=1 && $account->id !=2 && $account->id !=3)
                                            <a class="btn btn-white mr-1"
                                               href="{{route('admin.account.edit',[$account['id']])}}">
                                                <span class="tio-edit"></span>
                                            </a>
                                            <a class="btn btn-white mr-1" href="javascript:"
                                               onclick="form_alert('account-{{$account['id']}}','Want to delete this account?')"><span
                                                    class="tio-delete"></span></a>
                                            <form action="{{route('admin.account.delete',[$account['id']])}}"
                                                  method="post" id="account-{{$account['id']}}">
                                                @csrf @method('delete')
                                            </form>
                                        @else
                                            <span>{{\App\CPU\translate('default')}}</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                        <div class="page-area">
                            <table>
                                <tfoot class="border-top">
                                {!! $accounts->links() !!}
                                </tfoot>
                            </table>
                        </div>
                        @if(count($accounts)==0)
                            @include('layouts.admin.partials._no-data-section')
                        @endif
                    </div>
                    <!-- End Table -->
                </div>
                <!-- End Card -->
            </div>
        </div>
    </div>

@endsection

@push('script_2')

@endpush
