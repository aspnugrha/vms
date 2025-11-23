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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">

    @yield('styles')
  </head>
  <body>
    @yield('content')
      
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
    <link rel="stylesheet" href="{{ asset('assets/backend') }}/css/custom.css" />

    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
