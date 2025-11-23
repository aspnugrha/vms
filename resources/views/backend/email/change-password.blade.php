@extends('backend.email.layouts.email-app')
@section('content')
    <div class="header">
        <a class="text-decoration-none" href="{{ route('home') }}">
            <img src="{{ asset($company_profile && $company_profile->logo ? 'assets/image/logo/'.$company_profile->logo : 'assets/image/logo.png') }}" alt="{{ ($company_profile && $company_profile->name ? $company_profile->name : 'Visitor Management System') }} Logo" style="width: 140px;">
        </a>
    </div>
    <div class="border-bottom" style="padding: 0 25px 80px 25px;">

        <h2 class="header-text" style="margin: 0 0 25px 0;">Hallo, {{ $customer->name }}!</h2>
        <p style="margin: 0 0 10px 0;font-size: 15px;">We would like to inform you that your account password has been changed.</p>
    </div>
@endsection