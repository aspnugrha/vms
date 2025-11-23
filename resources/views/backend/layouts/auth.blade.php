<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Visitor Management System</title>
    <meta
      content="width=device-width, initial-scale=1.0, shrink-to-fit=no"
      name="viewport"
    />

    <!-- CSRF Token -->
		<meta name="csrf-token" content="{{ csrf_token() }}">
    
    <link
      rel="icon"
      href="{{ asset('assets/backend') }}/img/kaiadmin/favicon.ico"
      type="image/x-icon"
    />

    <!-- Fonts and icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@icon/themify-icons@1.0.1-alpha.3/themify-icons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@7.4.47/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

    <script src="{{ asset('assets/backend') }}/js/plugin/webfont/webfont.min.js"></script>
    <script>
      WebFont.load({
        google: { families: ["Public Sans:300,400,500,600,700"] },
        custom: {
          families: [
            "Font Awesome 5 Solid",
            "Font Awesome 5 Regular",
            "Font Awesome 5 Brands",
            "simple-line-icons",
          ],
          urls: ["{{ asset('assets/backend') }}/css/fonts.min.css"],
        },
        active: function () {
          sessionStorage.fonts = true;
        },
      });
    </script>

    <!-- CSS Files -->
    <link rel="stylesheet" href="{{ asset('assets/backend') }}/css/bootstrap.min.css" />
    <link rel="stylesheet" href="{{ asset('assets/backend') }}/css/plugins.min.css" />
    <link rel="stylesheet" href="{{ asset('assets/backend') }}/css/kaiadmin.min.css" />
    <link rel="stylesheet" href="{{ asset('assets/backend') }}/css/custom.css" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">

    <style>
        /* Fullscreen overlay */
      .custom-loader-overlay{
          position:fixed;
          inset:0;
          display:none;
          align-items:center;
          justify-content:center;
          background:rgba(0,0,0,0.45);
          backdrop-filter:blur(1px);
          z-index:9999;
      }

      /* Simple smoke blob */
      .custom-smoke{
          position:absolute;
          width:180px;
          height:180px;
          border-radius:50%;
          background:radial-gradient(circle, rgba(255,255,255,0.25), rgba(255,255,255,0.05), transparent);
          filter:blur(18px);
          animation:float 3.5s ease-in-out infinite;
          opacity:0.55;
      }

      @keyframes float{
          0%,100%{transform:translateY(0)}
          50%{transform:translateY(-18px)}
      }

      /* Spinner */
      .custom-spinner{
          width:60px;
          height:60px;
          border:6px solid rgba(255,255,255,0.2);
          border-top-color:#fff;
          border-radius:50%;
          animation:spin 0.9s linear infinite;
          z-index:10;
      }

      @keyframes spin{to{transform:rotate(360deg)}}

      .custom-loader-text{
          margin-top:15px;
          font-size:14px;
          color:#fff;
          opacity:0.9;
          text-align:center;
      }

      .select2-container{
        width: 100% !important;
      }
    </style>
    @yield('styles')
  </head>
  <body>
    <div id="page-loader" class="custom-loader-overlay">
      <div class="custom-smoke"></div>
      <div style="text-align:center; z-index:10;">
        <div class="custom-spinner"></div>
        <div class="custom-loader-text">Loading...</div>
      </div>
    </div>
    
    <div class="wrapper">
      <div class="w-100" style="height: 100vh !important;">
        <div class="row g-0" style="height: 100% !important;">
            <div class="col-12 col-md-6 text-center text-white" style="background: rgb(26, 32, 53) !important;">
              @yield('content')
            </div>
            <div class="col-12 col-md-6">
                <img class="w-100 h-100" src="https://picsum.photos/1000" alt="Image" style="width: 100% !important;height: 100% !important;object-fit: cover;">
            </div>
        </div>
      </div>
    </div>
    <!--   Core JS Files   -->
    <script src="{{ asset('assets/backend') }}/js/core/jquery-3.7.1.min.js"></script>
    <script src="{{ asset('assets/backend') }}/js/core/popper.min.js"></script>
    <script src="{{ asset('assets/backend') }}/js/core/bootstrap.min.js"></script>

    <!-- jQuery Scrollbar -->
    <script src="{{ asset('assets/backend') }}/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>

    <!-- Chart JS -->
    <script src="{{ asset('assets/backend') }}/js/plugin/chart.js/chart.min.js"></script>

    <!-- jQuery Sparkline -->
    <script src="{{ asset('assets/backend') }}/js/plugin/jquery.sparkline/jquery.sparkline.min.js"></script>

    <!-- Chart Circle -->
    <script src="{{ asset('assets/backend') }}/js/plugin/chart-circle/circles.min.js"></script>

    <!-- Datatables -->
    <script src="{{ asset('assets/backend') }}/js/plugin/datatables/datatables.min.js"></script>

    <!-- Bootstrap Notify -->
    <script src="{{ asset('assets/backend') }}/js/plugin/bootstrap-notify/bootstrap-notify.min.js"></script>

    <!-- jQuery Vector Maps -->
    <script src="{{ asset('assets/backend') }}/js/plugin/jsvectormap/jsvectormap.min.js"></script>
    <script src="{{ asset('assets/backend') }}/js/plugin/jsvectormap/world.js"></script>

    <!-- Sweet Alert -->
    <script src="{{ asset('assets/backend') }}/js/plugin/sweetalert/sweetalert.min.js"></script>

    <!-- Kaiadmin JS -->
    <script src="{{ asset('assets/backend') }}/js/kaiadmin.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

    <script>
      function showToastr(position, type, message){
        toastr.options = {
          "closeButton": false,
          "debug": false,
          "newestOnTop": false,
          "progressBar": false,
          "positionClass": position,
          "preventDuplicates": false,
          "onclick": null,
          "showDuration": "300",
          "hideDuration": "1000",
          "timeOut": "5000",
          "extendedTimeOut": "1000",
          "showEasing": "swing",
          "hideEasing": "linear",
          "showMethod": "fadeIn",
          "hideMethod": "fadeOut",
        }
        toastr[type](message)
      }
    </script>

    @yield('scripts')
  </body>
</html>
