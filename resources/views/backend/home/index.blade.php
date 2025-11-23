@extends('backend.layouts.auth')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10 py-5 my-5">
        <div class="subscribe-header text-center pb-3">
            <center>
                <img
                    src="{{ asset('assets/backend') }}/img/kaiadmin/logo_light.svg"
                    alt="navbar brand"
                    class="navbar-brand mb-5"
                    height="30"
                />
            </center>
            <h3 class="section-title text-uppercase">Check In/Out Form QR</h3>
            <span class="text-white">Silahkan masukan visitor code atau scan qr untuk Check In atau Check Out.</span>
        </div>
        <form method="POST" id="form-data" class="d-flex flex-wrap gap-2">
            @csrf
            {{-- <div id="reader" width="600px"></div> --}}

            <div class="card w-100">
                <div class="card-body">
                  <div id="reader" style="width:100%;"></div>
    
                  <div class="w-100 btn-group">
                      <button type="button" id="startBtn" class="btn btn-dark"><i class="mdi mdi-qrcode-scan"></i> Start Scan</button>
                      <button type="button" id="stopBtn" class="btn btn-default"><i class="mdi mdi-stop-circle-outline"></i> Stop Scan</button>
                  </div>
                </div>
            </div>

            <input type="text" name="visitor_code" id="visitor_code" placeholder="Visitor Code" class="form-control form-control-lg" required onkeydown="if(event.key === 'Enter') submit();">
            <button type="button" id="btn-submit" onclick="checkInCheckOut()" class="mt-3 btn btn-info btn-lg text-uppercase w-100">Check In/Out</button>
            {{-- <p class="w-100 mt-1 p-0 mb-0 text-muted text-center"><a href="{{ route('home') }}">Input Visitor Code?</a></p> --}}
        </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script>
  let html5QrcodeScanner = null;
  let count = 0;

  $(document).ready(function(){
    let interval = setInterval(() => {
        count++;

        if (count >= 5) {
            clearInterval(interval);   // stop interval
            location.reload();         // reload page
        }

    }, 60000);
  })

    document.getElementById("startBtn").addEventListener("click", function() {
        const qrRegion = document.getElementById("reader");
        html5QrcodeScanner = new Html5Qrcode("reader");
        startScan();
    });

    document.getElementById("stopBtn").addEventListener("click", function() {
        if (html5QrcodeScanner) {
            html5QrcodeScanner.stop().then(() => {
                html5QrcodeScanner.clear();
            });
        }
    });

    function startScan(){
      html5QrcodeScanner.start(
            { facingMode: "environment" }, // Kamera belakang
            { fps: 10, qrbox: 250 },
            async (decodedText) => {
                document.getElementById("visitor_code").value = decodedText;
                // AUTO STOP DI SINI
               html5QrcodeScanner.stop().then(() => {
                   html5QrcodeScanner.clear();
                   console.log("Scan dihentikan otomatis setelah terbaca.");
               });
               await checkInCheckOut()

               setTimeout(() => {
                 startScan()
               }, 1000);
              }
            ).catch(err => console.log(err));
    }


    async function checkInCheckOut(){
      // console.log(visitor_code);
      var postData = new FormData($('#form-data')[0]);
      $.ajax({
          url: "{{ route('scan-qr.process') }}",
          method: 'POST',
          data: postData,
          processData: false,
          contentType: false,
          cache: false,
          beforeSend:function(){
            $('.custom-loader-overlay').css('display', 'flex');
          },
          success: function(response) {
              console.log(response);
              if (response.success == true) {
                  // form.trigger('reset');
                  $('#visitor_code').val('')

                  showToastr('toast-top-right', 'success', response.message)
              } else {
                  showToastr('toast-top-right', 'error', response.message)
              }
          },
          error: function(xhr) {
            console.log(xhr);
            
              var res = xhr.responseJSON;
              if ($.isEmptyObject(res) == false) {
                  console.log(res.errors);
                  
                  $.each(res.errors, function(key, value) {
                      $('#' + key)
                          // .closest('#error')
                          .addClass('is-invalid');
                      $('#' + key + 'Help').text(value.join(', '))
                  });
              }
                  
              showToastr('toast-top-right', 'error', "Please check the form for errors")
          },
          complete:function(){
              $('.custom-loader-overlay').css('display', 'none');
          }
      });
    }

//   function onScanSuccess(decodedText, decodedResult) {
//   // handle the scanned code as you like, for example:
//   // console.log(`Code matched = ${decodedText}`, decodedResult);
//   if(decodedText){
//     checkInCheckOut(decodedText)
//   }
// }

// function onScanFailure(error) {
//   // handle scan failure, usually better to ignore and keep scanning.
//   // for example:
//   console.warn(`Code scan error = ${error}`);
// }

// let html5QrcodeScanner = new Html5QrcodeScanner(
//   "reader",
//   { fps: 10, qrbox: {width: 250, height: 250} },
//   /* verbose= */ false);
// html5QrcodeScanner.render(onScanSuccess, onScanFailure);

//   function clearForm(){
//     $('#visitor_code').val('')
//   }

//   function submit(){
//     if (document.getElementById('form-data').reportValidity()) {
//       submitProcess()
//     } else {
//       console.log("Form belum lengkap");
//     }
//   }

//   function submitProcess(){
//     $('#btn-login').text('Please Wait...').prop('disabled', true)
//     var postData = new FormData($('#form-data')[0]);
//     $.ajax({
//         url: "{{ route('login.process') }}",
//         type: "POST",
//         data: postData,
//         processData: false,
//         contentType: false,
//         cache: false,
//         success: function(res) {
//             console.log(res)
//             if(res.success){
//               showToastr('toast-top-right', 'success', res.message)
//               clearForm()
//               setTimeout(() => {
//                 window.location.href = "{{ route('dashboard') }}"
//               }, 1000);
//             }else{
//               showToastr('toast-top-right', 'error', res.message)
//             }
//         },
//         error:function(res){
//             console.log('error', res);
//         },
//         complete: function(){
//           $('#btn-login').text('Login').prop('disabled', false)
//         }
//     });
//   }
</script>
@endsection