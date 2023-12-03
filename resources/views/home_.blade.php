@extends('layouts.blank')

@section('title',\App\CPU\translate("home"))

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 mt-3">
                <div class="card mt-3">
                    <div class="card-body text-center">
                        @php($shop_logo=\App\Models\BusinessSetting::where(['key'=>'shop_logo'])->first()->value)
                        <img width="210"
                             onerror="this.src='{{asset('public/assets/admin/img/160x160/img2.jpg')}}'"
                             src="{{asset('storage/app/public/shop')}}/{{ $shop_logo }}"
                             alt="{{\App\CPU\translate('logo')}}">
                        <br><hr>

                        <a class="btn btn-primary" href="{{ route('admin.dashboard') }}">{{\App\CPU\translate('dashboard')}}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
