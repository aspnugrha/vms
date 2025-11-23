@extends('backend.layouts.auth')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 pt-5 mt-5">
            <div class="subscribe-header text-center">
                <center>
                    <img
                        src="{{ asset('assets/backend') }}/img/kaiadmin/logo_light.svg"
                        alt="navbar brand"
                        class="navbar-brand mb-5"
                        height="30"
                    />
                </center>
                {{-- <h3 class="section-title text-uppercase">Login Form</h3>
                <span class="text-white">Welcome back! Please log in to continue.</span> --}}
            </div>
        </div>
        @if ($data['success'])
            <h4 class="mb-4 mt-1 text-light">Your account is now active!</h4>
            <p class="mb-4 text-light">{{ $data['message'] }}</p>
            <div class="d-flex justify-content-center">
                <a href="{{ route('login') }}" class="btn btn-info btn-lg text-uppercase w-100" style="width: 200px !important;">Login</a>
            </div>
        @else
            @if ($data['status'] == 'activation-code')
                <h4 class="mb-4 mt-1 text-light">There is something wrong!</h4>
                <p class="mb-4 text-light">{{ $data['message'] }}</p>
                <div class="d-flex justify-content-center">
                    <a href="{{ route('activation') }}" class="btn btn-info btn-lg text-uppercase w-100" style="width: 280px !important;">Activate Account</a>
                </div>
            @elseif ($data['status'] == 'email')
                <h4 class="mb-4 mt-1 text-light">There is something wrong!</h4>
                <p class="mb-4 text-light">{{ $data['message'] }}</p>
                <div class="d-flex justify-content-center">
                    <a href="{{ route('register') }}" class="btn btn-info btn-lg text-uppercase w-100" style="width: 280px !important;">Register Account</a>
                </div>
            @endif
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
</script>
@endsection