@extends('layouts.blank')
@section('content')
    <div class="container">
        <div class="row pt-5">
            <div class="col-md-12">
                @if(session()->has('error'))
                    <div class="alert alert-danger" role="alert">
                        {{session('error')}}
                    </div>
                @endif
                <div class="mar-ver pad-btm text-center">
                    <h1 class="h3">Purchase Code</h1>
                    <p>Provide your codecanyon purchase code.<br>
                        <a href="https://help.market.envato.com/hc/en-us/articles/202822600-Where-Is-My-Purchase-Code"
                           class="text-info">Where to get purchase code?</a>
                    </p>
                </div>
                <div class="text-muted font-13">
                    <form method="POST" action="{{ route('purchase.code',['token'=>bcrypt('step_3')]) }}">
                        @csrf
                        <div class="form-group">
                            <label for="purchase_code">Your Email</label>
                            <input type="email" value=""
                                   class="form-control" id="email"
                                   name="email">
                        </div>

                        <div class="form-group">
                            <label for="purchase_code">Codecanyon Username</label>
                            <input type="text" value=""
                                   class="form-control" id="username"
                                   name="username" required>
                        </div>

                        <div class="form-group">
                            <label for="purchase_code">Purchase Code</label>
                            <input type="text"
                                   value=""
                                   class="form-control" id="purchase_key"
                                   name="purchase_key" required>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-info">Proceed to install</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
