@extends('backend.layouts.auth')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-8 py-5 my-5">
      <div class="subscribe-header text-center pb-3">
        <center>
            <img
                src="{{ asset('assets/backend') }}/img/kaiadmin/logo_light.svg"
                alt="navbar brand"
                class="navbar-brand mb-5"
                height="30"
            />
        </center>
        <h3 class="section-title text-uppercase">Forgot Password Form</h3>
        <span class="text-white">Don't worry, it only takes a few moments for your account to be back.</span>
      </div>
      <form method="POST" id="form-data" class="d-flex flex-wrap gap-2">
        @csrf
        <input type="email" name="email" id="email" placeholder="Your Email Addresss" class="form-control form-control-lg" required value="{{ request()->email ? request()->email : old('email') }}" onkeydown="if(event.key === 'Enter') event.preventDefault();">
        {{-- <span class="text-muted my-2">Don't have an account? <a href="{{ route('register').(request()->email? '?email='.request()->email : '') }}">Register</a></span> --}}
        <button type="button" id="btn-forgot" onclick="forgot()" class="mt-3 btn btn-info btn-lg text-uppercase w-100">Send Me An Email</button>
        {{-- <p class="w-100 my-2 text-muted text-center">Your account isn't active yet? <a href="">Activate it here</a>.</p> --}}
        <p class="w-100 mt-2 p-0 mb-0 text-white text-center">Your account isn't active yet? <a href="{{ route('activation') }}">Activate it here</a>.</p>
        <p class="w-100 mt-2 p-0 mb-0 text-white text-center">Already have an account? <a href="{{ route('login') }}">Login</a></p>
        {{-- <p class="w-100 mt-1 p-0 mb-0 text-muted text-center"><a href="{{ route('forgot-password') }}">Forgot Password?</a></p> --}}
      </form>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
  function clearForm(){
    $('#email').val('')
  }

  function forgot(){
    if (document.getElementById('form-data').reportValidity()) {
      forgotProcess()
    } else {
      console.log("Form belum lengkap");
    }
  }

  function forgotProcess(){
    $('#btn-forgot').text('Please Wait...').prop('disabled', true)

    var postData = new FormData($('#form-data')[0]);
    $.ajax({
        url: "{{ route('forgot-password.process') }}",
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
            }else{
              showToastr('toast-top-right', 'error', res.message)
            }
        },
        error:function(res){
            console.log('error', res);
        },
        complete: function(){
          $('#btn-forgot').text('Send Me An Email').prop('disabled', false)
        }
    });
  }
</script>
@endsection