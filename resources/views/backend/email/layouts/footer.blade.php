<footer id="footer">
    <div class="row d-flex flex-wrap justify-content-between" style="padding: 40px 25px;">
        {{-- <div class="col-md-6 col-sm-6">
            <div class="footer-menu footer-menu-001">
                <div class="footer-intro mb-4">
                    <a href="index.html">
                    <img src="{{ asset($company_profile && $company_profile->logo ? 'assets/image/'.$company_profile->logo : 'assets/image/logo.png') }}" alt="{{ ($company_profile && $company_profile->name ? $company_profile->name : 'Visitor Management System') }} Logo" style="width: 150px">
                    </a>
                </div>
            </div>
        </div> --}}
        <div class="col-md-6 col-sm-12">
            <div class="" style="width: 100%;">
                <h5 class="header-text" style="font-size: 18px;padding: 0;margin: 0;">Social Media</h5>
                <div style="padding: 0;margin: 15px 0 30px 0;width: 100%;">
                    <div class="row" style="width: 100%;">
                        @if ($company_profile->facebook)
                        <div class="col-md-6 col-sm-12">
                            <a href="https://www.facebook.com/{{ $company_profile->facebook ? $company_profile->facebook : '?' }}" target="_blank" class="text-decoration-none" style="color: black;padding: 0 0 5px 15px;display: flex;align-items: center;gap: 10px;">
                                <img src="{{ asset('assets/svg/facebook.svg') }}" alt="" style="width: 20px;">
                                Facebook
                            </a>
                        </div>
                        @endif
                        @if ($company_profile->whatsapp)
                        <div class="col-md-6 col-sm-12">
                            <a href="https://wa.me/628{{ $company_profile->whatsapp ? $company_profile->whatsapp : '?' }}" target="_blank" class="text-decoration-none" style="color: black;padding: 0 0 5px 15px;display: flex;align-items: center;gap: 10px;">
                                <img src="{{ asset('assets/svg/whatsapp.svg') }}" alt="" style="width: 20px;">
                                Whatsapp
                            </a>
                        </div>
                        @endif
                        @if ($company_profile->telegram)
                        <div class="col-md-6 col-sm-12">
                            <a href="https://t.me/{{ $company_profile->telegram ? $company_profile->telegram : '?' }}" target="_blank" class="text-decoration-none" style="color: black;padding: 0 0 5px 15px;display: flex;align-items: center;gap: 10px;">
                                <img src="{{ asset('assets/svg/telegram.svg') }}" alt="" style="width: 20px;">
                                Telegram
                            </a>
                        </div>
                        @endif
                        @if ($company_profile->twitter)
                        <div class="col-md-6 col-sm-12">
                            <a href="https://twitter.com/{{ $company_profile->twitter ? $company_profile->twitter : '?' }}" target="_blank" class="text-decoration-none" style="color: black;padding: 0 0 5px 15px;display: flex;align-items: center;gap: 10px;">
                                <img src="{{ asset('assets/svg/twitter-x.svg') }}" alt="" style="width: 20px;">
                                Twitter
                            </a>
                        </div>
                        @endif
                        @if ($company_profile->youtube)
                        <div class="col-md-6 col-sm-12">
                            <a href="https://www.youtube.com/{{ $company_profile->youtube ? '@'.$company_profile->youtube : '?' }}" target="_blank" class="text-decoration-none" style="color: black;padding: 0 0 5px 15px;display: flex;align-items: center;gap: 10px;">
                                <img src="{{ asset('assets/svg/youtube.svg') }}" alt="" style="width: 20px;">
                                Youtube
                            </a>
                        </div>
                        @endif
                        @if ($company_profile->tiktok)
                        <div class="col-md-6 col-sm-12">
                            <a href="https://www.tiktok.com/{{ $company_profile->tiktok ? '@'.$company_profile->tiktok : '?' }}" target="_blank" class="text-decoration-none" style="color: black;padding: 0 0 5px 15px;display: flex;align-items: center;gap: 10px;">
                                <img src="{{ asset('assets/svg/tiktok.svg') }}" alt="" style="width: 20px;">
                                Tiktok
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        {{-- <div class="col-md-6 col-sm-12">
            <h5 class="header-text" style="font-size: 18px;padding: 0;margin: 0;">Help & Info</h5>
            <div class="" style="padding: 0;margin: 15px 0 0 0;">
                <p style="padding: 0;margin: 15px 0 0 0;">
                    Do you have any questions or suggestions?
                    <a class="text-decoration-none" href="mailto:{{ ($company_profile && $company_profile->email ? $company_profile->email : 'example@email.com') }}"
                    class="item-anchor">{{ ($company_profile && $company_profile->email ? $company_profile->email : 'example@email.com') }}</a>
                </p>
                <p style="padding: 0;margin: 15px 0 0 0;">
                    Do you need support? Give us a call. 
                    <a class="text-decoration-none" href="tel:+{{ ($company_profile && $company_profile->phone_number ? $company_profile->phone_number : '628') }}" class="item-anchor">
                    +{{ ($company_profile && $company_profile->phone_number ? $company_profile->phone_number : '628') }}
                    </a>
                </p>
            </div>
        </div> --}}
    </div>
    <div class="border-top py-4" style="padding: 20px 25px;">
        <p style="padding: 0;margin: 0;">
            &copy; 2024 Visitor Management System. All rights reserved.
            {{-- <a href="https://templatesjungle.com" target="_blank">TemplatesJungle</a>
            Distribution By <a href="https://themewagon.com" target="blank">ThemeWagon</a> --}}
        </p>
    </div>
</footer>