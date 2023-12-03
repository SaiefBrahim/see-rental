@extends('layouts.admin.app')

@section('title',\App\CPU\translate('update_account'))

@push('css_or_js')

@endpush

@section('content')
<div class="content container-fluid">
        <!-- Page Header -->
        <div class="mb-3">
            <h1 class="page-header-title d-flex align-items-center g-2px text-capitalize">
                <i class="tio-edit"></i>
                <span>{{\App\CPU\translate('update_account')}}</span>
            </h1>
        </div>
        <!-- End Page Header -->
        <div class="row gx-2 gx-lg-3">
            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <div class="card">
                    <div class="card-body">
                        <form action="{{route('admin.account.update',[$account->id])}}" method="post">
                            @csrf
                                <div class="row pl-2" >
                                    <div class="col-12 col-sm-6">
                                        <div class="form-group">
                                            <label class="input-label" >{{\App\CPU\translate('account_title')}}</label>
                                            <input type="text" name="account" class="form-control" value="{{ $account->account }}"  placeholder="{{\App\CPU\translate('account_title')}}" required>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6">
                                        <div class="form-group">
                                            <label class="input-label">{{\App\CPU\translate('description')}} </label>
                                            <input type="text" name="description" class="form-control" value="{{ $account->description }}"  placeholder="{{\App\CPU\translate('description')}}" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row pl-2" >
                                    <div class="col-12 col-sm-6">
                                        <div class="form-group">
                                            <label class="input-label" >{{\App\CPU\translate('account_number')}}</label>
                                            <input type="text" name="account_number" class="form-control" value="{{ $account->account_number }}"  placeholder="{{\App\CPU\translate('account_number')}}" required>
                                        </div>
                                    </div>
                                </div>
                            <hr>
                            <button type="submit" class="btn btn-primary">{{\App\CPU\translate('update')}}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('script_2')

@endpush
