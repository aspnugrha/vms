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
          <h4 class="mb-4 mt-1">Hello! Set up your account</h4>
          <p class="mb-4">{{ $data['message'] }}</p>

          <div class="col-12 col-md-12">
            <form method="POST" id="form-data" class="d-flex flex-wrap gap-2 mb-2 container">
                @csrf
                <input type="hidden" name="email" id="email" placeholder="" class="form-control form-control-lg" required value="{{ $data['data']['email'] ? $data['data']['email'] : '' }}">
                <input type="hidden" name="code" id="code" placeholder="" class="form-control form-control-lg" required value="{{ $data['data']['code'] ? $data['data']['code'] : '' }}">
                <input type="password" name="password" id="password" placeholder="New password" class="form-control form-control-lg" required onkeydown="if(event.key === 'Enter') createNewPassword();">
                <div class="invalid-feedback" id="password-feedback"></div>
                <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm new password" class="form-control form-control-lg" required onkeydown="if(event.key === 'Enter') createNewPassword();">
                <div class="invalid-feedback" id="confirm_password-feedback"></div>
                {{-- <span class="text-muted my-2">Don't have an account? <a href="{{ route('register').(request()->email? '?email='.request()->email : '') }}">Register</a></span> --}}
                <button type="button" id="btn-create" onclick="createNewPassword()" class="btn btn-info btn-lg text-uppercase w-100">Create New Password</button>
                {{-- <p class="w-100 mt-2 p-0 mb-0 text-muted text-center">Your account isn't active yet? <a href="{{ route('activation') }}">Activate it here</a>.</p>
                <p class="w-100 mt-1 p-0 mb-0 text-muted text-center"><a href="{{ route('forgot-password') }}">Forgot Password?</a></p> --}}
            </form>
          </div>
          {{-- <div class="d-flex justify-content-center">
              <a href="{{ route('login') }}" class="btn btn-info btn-lg text-uppercase w-100" style="width: 200px !important;">Login</a>
          </div> --}}
        @else
          @if ($data['status'] == 'activation-code')
              <h4 class="mb-4 mt-1">There is something wrong!</h4>
              <p class="mb-4">{{ $data['message'] }}</p>
              <div class="d-flex justify-content-center">
                  <a href="{{ route('forgot-password') }}" class="btn btn-info btn-lg text-uppercase w-100" style="width: 280px !important;">Forgot Password</a>
              </div>
          @elseif ($data['status'] == 'email')
              <h4 class="mb-4 mt-1">There is something wrong!</h4>
              <p class="mb-4">{{ $data['message'] }}</p>
              <div class="d-flex justify-content-center">
                  <a href="{{ route('forgot-password') }}" class="btn btn-info btn-lg text-uppercase w-100" style="width: 280px !important;">Forgot Password</a>
              </div>
          @endif
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
  function clearForm(){
    $('#email').val('')
    $('#password').val('')
  }

  function createNewPassword(){
    if (document.getElementById('form-data').reportValidity()) {
      createNewPasswordProcess()
    } else {
      console.log("Form belum lengkap");
    }
  }

  function createNewPasswordProcess(){
    $('#btn-create').text('Please Wait...').prop('disabled', true)

    var postData = new FormData($('#form-data')[0]);
    $.ajax({
        url: "{{ route('set-new-password.process') }}",
        type: "POST",
        data: postData,
        processData: false,
        contentType: false,
        cache: false,
        success: function(res) {
            console.log(res)
            if(res.success){
              clearForm()
              showToastr('toast-top-right', 'success', res.message)
              setTimeout(() => {
                window.location.href = "{{ route('login') }}"
              }, 1000);
            }else{
              if(res.status == 'validation'){
                if(res.errors){
                  Object.entries(res.errors).forEach(([key, error]) => {
                    $('#'+key).addClass('is-invalid');
                    const msg = error ? error.join(', ') : ''; 
                    $('#'+key+'-feedback').html(msg);
                  });
                }
              }
              showToastr('toast-top-right', 'error', res.message)
            }
        },
        error:function(res){
            console.log('error', res);
        },
        complete: function(){
          $('#btn-create').text('Create New Password').prop('disabled', false)
        }
    });
  }
</script>
@endsection