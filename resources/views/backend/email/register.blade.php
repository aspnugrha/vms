@extends('backend.email.layouts.email-app')
@section('content')
    <div class="header">
        <a class="text-decoration-none" href="{{ route('home') }}">
            <img src="{{ asset($company_profile && $company_profile->logo ? 'assets/image/logo/'.$company_profile->logo : 'assets/image/logo.png') }}" alt="{{ ($company_profile && $company_profile->name ? $company_profile->name : 'Visitor Management System') }} Logo" style="width: 140px;">
        </a>
    </div>
    <div class="border-bottom" style="padding: 0 25px 80px 25px;">

        <h2 class="header-text" style="margin: 0 0 25px 0;">Hallo, {{ $customer->name }}!</h2>
        <p style="margin: 0 0 10px 0;font-size: 15px;">Thank you for joining {{ $company_profile->name ? $company_profile->name : 'Visitor Management System' }}. You're one step closer to enjoying full access to our website.</p>
        <p style="margin: 0 0 10px 0;font-size: 15px;">Please activate your account by clicking the link below.</p>
        <div style="width: 100%;display: flex;justify-content: center;margin-top: 50px;">
            <a href="{{ $url }}" target="_blank" class="btn">ACTIVATE NOW</a>
        </div>
    </div>

@endsection