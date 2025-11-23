@extends('backend.email.layouts.email-app')
@section('content')
    <div class="header">
        <a class="text-decoration-none" href="{{ route('home') }}">
            <img src="{{ asset($company_profile && $company_profile->logo ? 'assets/image/logo/'.$company_profile->logo : 'assets/image/logo.png') }}" alt="{{ ($company_profile && $company_profile->name ? $company_profile->name : 'Visitor Management System') }} Logo" style="width: 140px;">
        </a>
    </div>
    <div class="border-bottom" style="padding: 0 25px 80px 25px;">

        <h2 class="header-text" style="margin: 0 0 25px 0;">Hallo, {{ $customer->name }}!</h2>
        <p style="margin: 0 0 10px 0;font-size: 15px;">Don't worry, your account will be restored in one step. Please click the button below to prepare your account for recovery.</p>
        <div style="width: 100%;display: flex;justify-content: center;margin-top: 50px;">
            <a href="{{ $url }}" target="_blank" class="btn">SET UP MY ACCOUNT</a>
        </div>
    </div>
@endsection