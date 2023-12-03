<div class="col-sm-12 col-lg-4 mb-3 mt-3 mb-lg-5"><!-- Card -->
    <a class="card card-hover-shadow h-100 color-one" href="#">
        <div class="card-body">
            <div class="flex-between align-items-center mb-1">
                <div>
                    <h6 class="card-subtitle text-white">{{\App\CPU\translate('total_revenue')}}</h6>
                    <span class="card-title h2 text-white">
                        {{ $account['total_income']-$account['total_expense'] ." ".\App\CPU\Helpers::currency_symbol()}}
                    </span>
                </div>
                <div class="mt-2">
                    <i class="tio-money-vs text-white font-one"></i>
                </div>
            </div>
            <!-- End Row -->
        </div>
    </a>
    <!-- End Card -->
</div>
<div class="col-sm-6 col-lg-4 mb-3 mt-3 mb-lg-5">
<!-- Card -->
<a class="card card-hover-shadow h-100 color-two" href="#">
    <div class="card-body">
        <div class="flex-between align-items-center mb-1">
            <div>
                <h6 class="card-subtitle text-white">{{\App\CPU\translate('total_income')}}</h6>
                <span class="card-title h2 text-white">
                    {{ $account['total_income'] ." ".\App\CPU\Helpers::currency_symbol()}}
                </span>
            </div>
            <div class="mt-2">
                <i class="tio-money-vs text-white font-one"></i>
            </div>
        </div>
        <!-- End Row -->
    </div>
</a>
<!-- End Card -->
</div>
<div class="col-sm-6 col-lg-4 mb-3 mt-3 mb-lg-5">
<!-- Card -->
<a class="card card-hover-shadow h-100 color-three" href="#">
    <div class="card-body">
        <div class="flex-between align-items-center mb-1">
            <div>
                <h6 class="card-subtitle text-white">{{\App\CPU\translate('total_expense')}}</h6>
                <span class="card-title h2 text-white">
                    {{ $account['total_expense'] ." ".\App\CPU\Helpers::currency_symbol()}}
                </span>
            </div>
            <div class="mt-2">
                <i class="tio-money-vs font-one text-white"></i>
            </div>
        </div>
        <!-- End Row -->
    </div>
</a>
<!-- End Card -->
</div>

<div class="col-sm-6 col-lg-6 mb-3 mt-3 mb-lg-5">
<!-- Card -->
<a class="card card-hover-shadow h-100 color-four" href="#">
    <div class="card-body">
        <div class="flex-between align-items-center mb-1">
            <div>
                <h6 class="card-subtitle text-white">{{\App\CPU\translate('account_payable')}}</h6>
                <span class="card-title h2 text-white">
                    {{ $account['total_payable'] ." ".\App\CPU\Helpers::currency_symbol()}}
                </span>
            </div>
            <div class="mt-2">
                <i class="tio-money-vs text-white font-one"></i>
            </div>
        </div>
        <!-- End Row -->
    </div>
</a>
<!-- End Card -->
</div>
<div class="col-sm-6 col-lg-6 mb-3 mt-3 mb-lg-5">
<!-- Card -->
<a class="card card-hover-shadow h-100 color-five" href="#">
    <div class="card-body">
        <div class="flex-between align-items-center mb-1">
            <div>
                <h6 class="card-subtitle text-white">{{\App\CPU\translate('account_receivable')}}</h6>
                <span class="card-title h2 text-white">
                    {{ $account['total_receivable'] ." ".\App\CPU\Helpers::currency_symbol()}}
                </span>
            </div>
            <div class="mt-2">
                <i class="tio-money-vs text-white font-one"></i>
            </div>
        </div>
        <!-- End Row -->
    </div>
</a>
<!-- End Card -->
</div>
