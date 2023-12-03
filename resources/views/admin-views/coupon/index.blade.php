@extends('layouts.admin.app')

@section('title',\App\CPU\translate('add_new_coupon'))

@push('css_or_js')
    <link rel="stylesheet" href="{{asset('public/assets/admin')}}/css/custom.css"/>
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="">
            <h1 class="page-header-title d-flex align-items-center g-2px text-capitalize mb-3">
                <i class="tio-add-circle-outlined"></i>
                <span>{{\App\CPU\translate('add_new_coupon')}}</span>
            </h1>
        </div>
        <!-- End Page Header -->
        <div class="row gx-2 gx-lg-3">
            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <div class="card">
                    <div class="card-body">
                        <form action="{{route('admin.coupon.store')}}" method="post">
                                @csrf
                            <div class="row">
                                <div class="col-12 col-sm-6">
                                    <div class="form-group">
                                        <label class="input-label" for="exampleFormControlInput1">{{\App\CPU\translate('title')}}</label>
                                        <input type="text" name="title" class="form-control" placeholder="{{\App\CPU\translate('new_coupon')}}" required>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6">
                                    <div class="form-group">
                                        <div class="d-flex justify-content-between">
                                            <label class="input-label" for="exampleFormControlInput1">{{\App\CPU\translate('coupon_code')}}</label>
                                            <a href="javascript:void(0)" class="float-right c1 fz-12" onclick="generateCode()">{{\App\CPU\translate('generate_code')}}</a>
                                        </div>
                                        <input type="text" name="code" class="form-control" value="" id="code"
                                            placeholder="{{\Illuminate\Support\Str::random(8)}}" required>
                                    </div>
                                </div>

                            </div>

                                <div class="row">

                                <div class="col-12 col-sm-6">
                                    <div class="form-group">
                                        <label class="input-label" for="exampleFormControlInput1">{{\App\CPU\translate('coupon_type')}} </label>
                                        <select name="coupon_type" class="form-control" onchange="coupon_type_change(this.value)">
                                            <option value="default">{{\App\CPU\translate('default')}}</option>
                                            <option value="first_order">{{\App\CPU\translate('first_order')}}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6" id="limit-for-user">
                                    <div class="form-group">
                                        <label class="input-label" for="exampleFormControlInput1">{{\App\CPU\translate('limit_for_same_user')}} </label>
                                        <input min="1" type="number" name="user_limit" class="form-control" placeholder="{{\App\CPU\translate('EX:_10')}}">
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6">
                                    <div class="form-group">
                                        <label class="input-label" for="exampleFormControlInput1">{{\App\CPU\translate('start_date')}} </label>
                                        <input id="start_date" onchange="checkstartDate();" type="date" name="start_date" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6">
                                    <div class="form-group">
                                        <label class="input-label" for="exampleFormControlInput1">{{\App\CPU\translate('expire_date')}} </label>
                                        <input onchange="checkDate();" id="expire_date" type="date" name="expire_date" class="form-control" required>
                                    </div>
                                </div>
                                </div>

                                <div class="row">
                                    <div class="col-12 col-sm-6">
                                        <div class="form-group">
                                            <label class="input-label" for="exampleFormControlInput1">{{\App\CPU\translate('min_purchase')}} </label>
                                            <input type="number" step="0.01" name="min_purchase" value="0" min="0" max="100000" class="form-control"
                                                placeholder="{{\App\CPU\translate('100')}}">
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6" id="max_discount">
                                        <div class="form-group">
                                            <label class="input-label" for="exampleFormControlInput1">{{\App\CPU\translate('max_discount')}}</label>
                                            <input type="number" step="0.01" min="0" value="0" max="1000000" name="max_discount" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6">
                                        <div class="form-group">
                                            <label class="input-label" for="exampleFormControlInput1">{{\App\CPU\translate('discount')}}</label>
                                            <input type="number" step="0.01" min="1" max="10000" name="discount" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6">
                                        <div class="form-group">
                                            <label class="input-label" for="exampleFormControlInput1">{{\App\CPU\translate('discount')}} {{\App\CPU\translate('type')}}</label>
                                            <select  name="discount_type" class="form-control" onchange="discount_amount(this.value)">
                                                <option value="percent">{{\App\CPU\translate('percent')}}</option>
                                                <option value="amount">{{\App\CPU\translate('amount')}}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary">{{\App\CPU\translate('submit')}}</button>
                            </form>
                    </div>
                </div>
            </div>

            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <div class="card">
                    <div class="card-header">
                        <div class="w-100">
                            <div class="row">
                                <div class="col-12 col-sm-4 col-md-6">
                                    <h5 class="card-header-title">
                                        <span>{{\App\CPU\translate('coupon_table')}}</span>
                                        <span class="badge badge-soft-dark ml-2">{{$coupons->total()}}</span>
                                    </h5>

                                </div>
                                <div class="col-12 col-sm-8 col-md-6 mt-1 mt-sm-0">
                                    <form action="{{url()->current()}}" method="GET">
                                        <!-- Search -->
                                        <div class="input-group input-group-merge input-group-flush">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <i class="tio-search"></i>
                                                </div>
                                            </div>
                                            <input id="datatableSearch_" type="search" name="search" class="form-control"
                                                   placeholder="{{\App\CPU\translate('search_by_code_or_title')}}" aria-label="Search" value="{{$search}}" required>
                                            <button type="submit" class="btn btn-primary">{{\App\CPU\translate('search')}} </button>

                                        </div>
                                        <!-- End Search -->
                                    </form>
                                </div>
                            </div>
                        </div>

                    </div>
                    <!-- Table -->
                    <div class="table-responsive datatable-custom">
                        <table class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                            <thead class="thead-light">
                                <tr>
                                    <th>{{\App\CPU\translate('#')}}</th>
                                    <th>{{\App\CPU\translate('title')}}</th>
                                    <th>{{\App\CPU\translate('code')}}</th>
                                    <th>{{\App\CPU\translate('min')}} {{\App\CPU\translate('purchase')}}</th>
                                    <th>{{\App\CPU\translate('max')}} {{\App\CPU\translate('discount')}}</th>
                                    <th>{{\App\CPU\translate('discount')}}</th>
                                    <th>{{\App\CPU\translate('discount')}} {{\App\CPU\translate('type')}}</th>
                                    <th>{{\App\CPU\translate('start')}} {{\App\CPU\translate('date')}}</th>
                                    <th>{{\App\CPU\translate('expire')}} {{\App\CPU\translate('date')}}</th>
                                    <th>{{\App\CPU\translate('status')}}</th>
                                    <th>{{\App\CPU\translate('action')}}</th>
                                </tr>
                            </thead>

                            <tbody>
                            @foreach($coupons as $key=>$coupon)
                                <tr>
                                    <td>{{$coupons->firstitem()+$key}}</td>
                                    <td>
                                    <span class="d-block font-size-sm text-body">
                                        {{$coupon['title']}}
                                    </span>
                                    </td>
                                    <td>{{$coupon['code']}}</td>
                                    <td>{{$coupon['min_purchase']." ".\App\CPU\Helpers::currency_symbol()}}</td>
                                    <td>{{$coupon['max_discount']." ".\App\CPU\Helpers::currency_symbol()}}</td>
                                    <td>{{$coupon['discount']." ".\App\CPU\Helpers::currency_symbol()}}</td>
                                    <td>{{$coupon['discount_type']}}</td>
                                    <td>{{$coupon['start_date']}}</td>
                                    <td>{{$coupon['expire_date']}}</td>
                                    <td>
                                        <label class="toggle-switch toggle-switch-sm">
                                                <input type="checkbox" class="toggle-switch-input"
                                                       onclick="location.href='{{route('admin.coupon.status',[$coupon['id'],$coupon->status?0:1])}}'"
                                                       class="toggle-switch-input" {{$coupon->status?'checked':''}}>
                                                <span class="toggle-switch-label">
                                            <span class="toggle-switch-indicator"></span>
                                            </span>
                                            </label>
                                    </td>
                                    <td>
                                        <a class="btn btn-white mr-1"
                                                   href="{{route('admin.coupon.edit',[$coupon['id']])}}"><span class="tio-edit"></span></a>
                                        <a class="btn btn-white mr-1" href="javascript:"
                                                onclick="form_alert('coupon-{{$coupon['id']}}','Want to delete this coupon ?')"><span class="tio-delete"></span></a>
                                            <form action="{{route('admin.coupon.delete',[$coupon['id']])}}"
                                                    method="post" id="coupon-{{$coupon['id']}}">
                                                @csrf @method('delete')
                                            </form>

                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                        <table>
                            <tfoot>
                            {!! $coupons->links() !!}
                            </tfoot>
                        </table>
                         @if(count($coupons)==0)
                            <div class="text-center p-4">
                                <img class="mb-3 w-one-carsi" src="{{asset('public/assets/admin')}}/svg/illustrations/sorry.svg" alt="{{\App\CPU\translate('Image Description')}}">
                                <p class="mb-0">{{ \App\CPU\translate('No_data_to_show')}}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <!-- End Table -->
        </div>
    </div>

@endsection

@push('script_2')
    <script src={{asset("public/assets/admin/js/coupon-index.js")}}></script>

    <script>
        function  generateCode(){
            let code = Math.random().toString(36).substring(2,12);
            $('#code').val(code)
        }
    </script>
@endpush
