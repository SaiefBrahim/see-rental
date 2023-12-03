@extends('layouts.admin.app')

@section('title',\App\CPU\translate('dashboard'))

@section('content')
<div class="content container-fluid">
    <div class="card mb-3 bg-white">
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-12">
                    <label class="badge badge-soft-success float-right mb-2" style="z-index: 9;right: 0.5rem;margin-top: -14px">
                        {{\App\CPU\translate('Software version')}}: {{ env('SOFTWARE_VERSION') }}
                    </label>
                </div>
                <div class="col-md-9">
                    <h4 class="card-header-title">
                        <i class="font-one-dash tio-chart-bar-4"></i>
                        <span>{{\App\CPU\translate('business_statistics')}}</span>
                    </h4>
                </div>
                <div class="col-md-3 float-right">
                    <select class="custom-select" name="statistics_type"
                            onchange="account_stats_update(this.value)">
                        <option
                            value="overall" >
                            {{\App\CPU\translate('overall_statistics')}}
                        </option>
                        <option
                            value="today" >
                            {{\App\CPU\translate("today's_statistics")}}
                        </option>
                        <option
                            value="month" >
                            {{\App\CPU\translate("this_month's_statistics")}}
                        </option>
                    </select>
                </div>
            </div>
            <div class="row gx-2 gx-lg-3" id="account_stats">
                @include('admin-views.partials._dashboard-balance-stats',['account'=>$account])
            </div>
        </div>
    </div>
    <div class="row gx-2 gx-lg-3 mb-3 mb-lg-5">
        <div class="col-lg-12">

            <!-- Card -->
            <div class="card h-100">
                <!-- Body -->
                <div class="card-body">
                    <div class="row mb-4">
                        {{-- <div class=" gx-2 gx-lg-3 mb-2"> --}}
                            <div class="col-md-7">
                                <h5 class="card-header-title mb-2">
                                    <i class="font-one-dash tio-chart-pie-1"></i>
                                    <span>{{\App\CPU\translate('earning_statistics_for_business_analytics')}}</span>
                                </h5>
                            </div>
                            <!-- Legend Indicators -->

                            <div class="col-md-2">
                                <div class="center-div">
                                        <span class="h6 mb-0">
                                            <i class="legend-indicator bg-success"></i>
                                            {{ \App\CPU\translate('income') }}
                                        </span><br>
                                        <span class="h6 mb-0">
                                            <i class="legend-indicator bg-warning"></i>
                                            {{ \App\CPU\translate('expense') }}
                                        </span>
                                </div>
                            </div>
                            <div class="col-md-3 float-right">
                                <select class="custom-select" name="statistics_type"
                                        onchange="chart_statistic(this.value)">
                                    <option
                                        value="yearly" >
                                        {{\App\CPU\translate('yearly_statistics')}}
                                    </option>
                                    <option
                                        value="monthly" >
                                        {{\App\CPU\translate('monthly_statistics')}}
                                    </option>

                                </select>
                            </div>
                            <!-- End Legend Indicators -->
                        {{-- </div> --}}

                    </div>
                     <!-- End Row -->
                    <div class="chartjs-custom" id="lastMonthStatistic">
                        <canvas id="updatingData_monthly"
                                class="h-one-dash"
                                data-hs-chartjs-options='{
                        "type": "line",
                        "data": {
                            "labels": [<?php for ($i=1;$i<=$month;$i++) {
                                if($month ==$i )
                                {
                                    echo $i;
                                }else{
                                    echo $i.',';
                                }

                            } ?>],
                            "datasets": [
                            {
                            "data": [<?php foreach ($last_month_income as $key => $value) {
                                if($total_day ==$key )
                                {
                                    echo $value;
                                }else{
                                    echo $value.',';
                                }


                            } ?>],
                            "backgroundColor": ["rgba(55, 125, 255, 0)", "rgba(255, 255, 255, 0)"],
                            "borderColor": "green",
                            "borderWidth": 2,
                            "pointRadius": 0,
                            "pointBorderColor": "#fff",
                            "pointBackgroundColor": "green",
                            "pointHoverRadius": 0,
                            "hoverBorderColor": "#fff",
                            "hoverBackgroundColor": "#377dff"
                            },
                            {
                            "data": [<?php foreach ($last_month_expense as $key => $value) {
                                if($total_day ==$key )
                                {
                                    echo $value;
                                }else{
                                    echo $value.',';
                                }


                            } ?>],
                            "backgroundColor": ["rgba(0, 201, 219, 0)", "rgba(255, 255, 255, 0)"],
                            "borderColor": "#ec9a3c",
                            "borderWidth": 2,
                            "pointRadius": 0,
                            "pointBorderColor": "#fff",
                            "pointBackgroundColor": "#ec9a3c",
                            "pointHoverRadius": 0,
                            "hoverBorderColor": "#fff",
                            "hoverBackgroundColor": "#00c9db"
                            }
                            ]
                        },
                        "options": {
                            "scales": {
                            "yAxes": [{
                                "gridLines": {
                                "color": "#e7eaf3",
                                "drawBorder": false,
                                "zeroLineColor": "#e7eaf3"
                                },
                                "ticks": {
                                "beginAtZero": true,
                                "stepSize": {{ ($account['total_income']/10)+1000 }},
                                "fontSize": 12,
                                "fontColor": "#97a4af",
                                "fontFamily": "Open Sans, sans-serif",
                                "padding": 10,
                                "postfix": " "
                                }
                            }],
                            "xAxes": [{
                                "gridLines": {
                                "display": false,
                                "drawBorder": false
                                },
                                "ticks": {
                                "fontSize": 12,
                                "fontColor": "#97a4af",
                                "fontFamily": "Open Sans, sans-serif",
                                "padding": 5
                                },
                                "categoryPercentage": 0.5,
                                "maxBarThickness": "10"
                            }]
                            },
                            "cornerRadius": 2,
                            "tooltips": {
                            "prefix": " ",
                            "hasIndicator": true,
                            "mode": "index",
                            "intersect": false
                            },
                            "hover": {
                            "mode": "nearest",
                            "intersect": true
                            }
                        }
                        }'></canvas>
                    </div>
                    <!-- Bar Chart -->
                    <div class="chartjs-custom" id="lastYearStatistic">
                        <canvas id="updatingData_yearly"
                                class="h-one-dash"
                                data-hs-chartjs-options='{
                        "type": "line",
                        "data": {
                            "labels":["Jan","Feb","Mar","April","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"],
                            "datasets": [
                            {
                            "data": [{{$monthly_income[1]}},{{$monthly_income[2]}},{{$monthly_income[3]}},{{$monthly_income[4]}},{{$monthly_income[5]}},{{$monthly_income[6]}},{{$monthly_income[7]}},{{$monthly_income[8]}},{{$monthly_income[9]}},{{$monthly_income[10]}},{{$monthly_income[11]}},{{$monthly_income[12]}}],
                            "backgroundColor": ["rgba(55, 125, 255, 0)", "rgba(255, 255, 255, 0)"],
                            "borderColor": "green",
                            "borderWidth": 2,
                            "pointRadius": 0,
                            "pointBorderColor": "#fff",
                            "pointBackgroundColor": "green",
                            "pointHoverRadius": 0,
                            "hoverBorderColor": "#fff",
                            "hoverBackgroundColor": "#377dff"
                            },
                            {
                            "data": [{{$monthly_expense[1]}},{{$monthly_expense[2]}},{{$monthly_expense[3]}},{{$monthly_expense[4]}},{{$monthly_expense[5]}},{{$monthly_expense[6]}},{{$monthly_expense[7]}},{{$monthly_expense[8]}},{{$monthly_expense[9]}},{{$monthly_expense[10]}},{{$monthly_expense[11]}},{{$monthly_expense[12]}}],
                            "backgroundColor": ["rgba(0, 201, 219, 0)", "rgba(255, 255, 255, 0)"],
                            "borderColor": "#ec9a3c",
                            "borderWidth": 2,
                            "pointRadius": 0,
                            "pointBorderColor": "#fff",
                            "pointBackgroundColor": "#ec9a3c",
                            "pointHoverRadius": 0,
                            "hoverBorderColor": "#fff",
                            "hoverBackgroundColor": "#00c9db"
                            }]
                        },
                        "options": {
                            "scales": {
                            "yAxes": [{
                                "gridLines": {
                                "color": "#e7eaf3",
                                "drawBorder": false,
                                "zeroLineColor": "#e7eaf3"
                                },
                                "ticks": {
                                "beginAtZero": true,
                                "stepSize": {{ ($account['total_income']/10)+1000 }},
                                "fontSize": 12,
                                "fontColor": "#97a4af",
                                "fontFamily": "Open Sans, sans-serif",
                                "padding": 10,
                                "postfix": " "
                                }
                            }],
                            "xAxes": [{
                                "gridLines": {
                                "display": false,
                                "drawBorder": false
                                },
                                "ticks": {
                                "fontSize": 12,
                                "fontColor": "#97a4af",
                                "fontFamily": "Open Sans, sans-serif",
                                "padding": 5
                                },
                                "categoryPercentage": 0.5,
                                "maxBarThickness": "10"
                            }]
                            },
                            "cornerRadius": 2,
                            "tooltips": {
                            "prefix": " ",
                            "hasIndicator": true,
                            "mode": "index",
                            "intersect": false
                            },
                            "hover": {
                            "mode": "nearest",
                            "intersect": true
                            }
                        }
                        }'></canvas>
                    </div>
                    <!-- End Bar Chart -->
                </div>
                <!-- End Body -->
            </div>
            <!-- End Card -->
        </div>
    </div>
    <div class="row gx-2 gx-lg-3 mb-3 mb-lg-5">
        <div class="col-md-6">
            <div class="card ">
                <div class="card-header">
                    <h3>{{\App\CPU\translate('accounts')}}
{{--                        <span class="badge badge-soft-dark ml-2">{{$accounts->total()}}</span>--}}
                    </h3>
                    <a class="" href="{{route('admin.account.list')}}">{{ \App\CPU\translate('View All') }}</a>
                </div>
                <div class="card-body">
                    <!-- Table -->
                    <div class="table-responsive datatable-custom">
                        <table class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                            <thead class="thead-light">
                            <tr>
                                <th>{{ \App\CPU\translate('#') }}</th>
                                <th>{{ \App\CPU\translate('account') }}</th>
                                <th>{{\App\CPU\translate('balance')}}</th>
                            </tr>
                            </thead>

                            <tbody>
                                @foreach ($accounts as $key=>$account)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <a class="text-primary" href="{{ route('admin.account.list') }}">
                                                {{ $account->account }}
                                            </a>
                                        </td>
                                        <td>{{ $account->balance ." ".\App\CPU\Helpers::currency_symbol()}}</td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

{{--                        <div class="page-area">--}}
{{--                            <table>--}}
{{--                                <tfoot class="border-top">--}}
{{--                                {!! $accounts->links() !!}--}}
{{--                                </tfoot>--}}
{{--                            </table>--}}
{{--                        </div>--}}
                        @if(count($accounts)==0)
                            <div class="text-center p-4">
                                <img class="mb-3 img-one-dash" src="{{asset('public/assets/admin')}}/svg/illustrations/sorry.svg" alt="Image Description">
                                <p class="mb-0">{{ \App\CPU\translate('No_data_to_show')}}</p>
                            </div>
                        @endif
                    </div>
                    <!-- End Table -->
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card ">
                <div class="card-header">
                    <h3>{{\App\CPU\translate('stock_limit_products')}}
{{--                        <span class="badge badge-soft-dark ml-2">{{$products->total()}}</span>--}}
                    </h3>
                    <a class="" href="{{route('admin.stock.stock-limit')}}">{{ \App\CPU\translate('View All') }}</a>
                </div>
                <div class="card-body">
                    <!-- Table -->
                    <div class="table-responsive datatable-custom">
                        <table class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                            <thead class="thead-light">
                            <tr>
                                <th>{{ \App\CPU\translate('#') }}</th>
                                <th>{{ \App\CPU\translate('name') }}</th>
                                <th>{{\App\CPU\translate('quantity')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach ($products as $key=>$product)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <a class="text-primary" href="{{ route('admin.stock.stock-limit') }}">
                                                {{ Str::limit($product->name,40) }}
                                            </a>
                                        </td>
                                        <td>{{ $product->quantity }}</td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

{{--                        <div class="page-area">--}}
{{--                            <table>--}}
{{--                                <tfoot class="border-top">--}}
{{--                                {!! $products->links() !!}--}}
{{--                                </tfoot>--}}
{{--                            </table>--}}
{{--                        </div>--}}
                        @if(count($products)==0)
                            <div class="text-center p-4">
                                <img class="mb-3 img-one-dash" src="{{asset('public/assets/admin')}}/svg/illustrations/sorry.svg" alt="{{\App\CPU\translate('image_description')}}">
                                <p class="mb-0">{{ \App\CPU\translate('No_data_to_show')}}</p>
                            </div>
                        @endif
                    </div>
                    <!-- End Table -->
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('script')
    <script src="{{asset('public/assets/admin')}}/vendor/chart.js/dist/Chart.min.js"></script>
    <script src="{{asset('public/assets/admin')}}/vendor/chart.js.extensions/chartjs-extensions.js"></script>
    <script src="{{asset('public/assets/admin')}}/vendor/chartjs-plugin-datalabels/dist/chartjs-plugin-datalabels.min.js"></script>
@endpush

@push('script_2')
    <script>
        "use strict";
        function account_stats_update(type) {
            //console.log(type)
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '{{route('admin.account-status')}}',
                data: {
                    statistics_type: type
                },
                beforeSend: function () {
                    $('#loading').show()
                },
                success: function (data) {
                    $('#account_stats').html(data.view)
                },
                complete: function () {
                    $('#loading').hide()
                }
            });
        }
    </script>

    <script src={{asset("public/assets/admin/js/global.js")}}></script>
@endpush
