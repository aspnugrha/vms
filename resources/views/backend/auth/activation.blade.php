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
        <h3 class="section-title text-uppercase">Activation Account Form</h3>
        <span class="text-white">Activate account to get full access</span>
      </div>
      <form method="POST" id="form-data" class="d-flex flex-wrap gap-2">
        @csrf
        <input type="email" name="email" id="email" placeholder="Your Email Addresss" class="form-control form-control-lg" required value="{{ request()->email ? request()->email : old('email') }}" onkeydown="if(event.key === 'Enter') event.preventDefault();">
        {{-- <span class="text-white my-2">Already have an account? <a href="{{ route('login') }}">Login</a></span> --}}
        <button type="button" id="btn-activate" onclick="activate()" class="mt-3 btn btn-info btn-lg text-uppercase w-100">Activate Account</button>
        <p class="w-100 mt-2 p-0 mb-0 text-white text-center">Already have an account? <a href="{{ route('login') }}">Login</a></p>
        <p class="w-100 mt-1 p-0 mb-0 text-muted text-center"><a href="{{ route('forgot-password') }}">Forgot Password?</a></p>
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

  function activate(){
    if (document.getElementById('form-data').reportValidity()) {
      activateProcess()
    } else {
      console.log("Form belum lengkap");
    }
  }

  function activateProcess(){
    $('#btn-activate').text('Please Wait...').prop('disabled', true)

    var postData = new FormData($('#form-data')[0]);
    $.ajax({
        url: "{{ route('activation.process') }}",
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
          $('#btn-activate').text('Activate Account').prop('disabled', false)
        }
    });
  }
</script>
@endsection