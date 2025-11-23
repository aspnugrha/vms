<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Visitor Management System</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Jost:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&family=Marcellus&display=swap"
        rel="stylesheet">

    <style>
        .header-text{
            /* font-family: "Marcellus", Roboto, sans-serif; */
            font-family: "Jost", Roboto, sans-serif;
        }

        body{
            font-family: "Jost", Roboto, sans-serif;
            background-color: rgba(241, 241, 240) !important;
        }

        .header{
            /* display: flex;
            justify-content: center; */
            padding: 40px 25px 10px 25px;
            margin-bottom: 40px;
        }

        .container{
            margin: 10%;
            width: 80%;
            background-color: white;
            border: 1px solid #212529;
        }

        .btn{
            background-color: #212529;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
        }

        .border{
            border: 1px solid #353738 !important;
        }

        .border-left{
            border-left: 1px solid #353738 !important;
        }

        .border-right{
            border-right: 1px solid #353738 !important;
        }

        .border-top{
            border-top: 1px solid #353738 !important;
        }

        .border-bottom{
            border-bottom: 1px solid #353738 !important;
        }

        .row {
            display: flex;
            flex-wrap: wrap;
            margin-left: calc(var(--gutter) / -2);
            margin-right: calc(var(--gutter) / -2);
            box-sizing: border-box;
        }

        /* COL dasar: full width by default (mobile) */
        .col {
            padding-left: calc(var(--gutter) / 2);
            padding-right: calc(var(--gutter) / 2);
            box-sizing: border-box;
            flex: 0 0 100%;
            max-width: 100%;
        }

        /* Utility: offset / auto grow jika diperlukan */
        .col-auto {
            flex: 0 0 auto;
            width: auto;
            max-width: none;
        }
        .col-grow {
            flex: 1 1 0;
        }

        /* ====== SMALL (sm) breakpoint: >=576px ====== */
        @media (min-width: 576px) {
            .col-sm-1  { flex: 0 0 8.333333%;  max-width: 8.333333%; }
            .col-sm-2  { flex: 0 0 16.666667%; max-width: 16.666667%; }
            .col-sm-3  { flex: 0 0 25%;       max-width: 25%; }
            .col-sm-4  { flex: 0 0 33.333333%;max-width: 33.333333%; }
            .col-sm-5  { flex: 0 0 41.666667%;max-width: 41.666667%; }
            .col-sm-6  { flex: 0 0 50%;       max-width: 50%; }
            .col-sm-7  { flex: 0 0 58.333333%;max-width: 58.333333%; }
            .col-sm-8  { flex: 0 0 66.666667%;max-width: 66.666667%; }
            .col-sm-9  { flex: 0 0 75%;       max-width: 75%; }
            .col-sm-10 { flex: 0 0 83.333333%;max-width: 83.333333%; }
            .col-sm-11 { flex: 0 0 91.666667%;max-width: 91.666667%; }
            .col-sm-12 { flex: 0 0 100%;      max-width: 100%; }
        }

        /* ====== MEDIUM (md) breakpoint: >=768px ====== */
        @media (min-width: 768px) {
            .col-md-1  { flex: 0 0 8.333333%;  max-width: 8.333333%; }
            .col-md-2  { flex: 0 0 16.666667%; max-width: 16.666667%; }
            .col-md-3  { flex: 0 0 25%;       max-width: 25%; }
            .col-md-4  { flex: 0 0 33.333333%;max-width: 33.333333%; }
            .col-md-5  { flex: 0 0 41.666667%;max-width: 41.666667%; }
            .col-md-6  { flex: 0 0 50%;       max-width: 50%; }
            .col-md-7  { flex: 0 0 58.333333%;max-width: 58.333333%; }
            .col-md-8  { flex: 0 0 66.666667%;max-width: 66.666667%; }
            .col-md-9  { flex: 0 0 75%;       max-width: 75%; }
            .col-md-10 { flex: 0 0 83.333333%;max-width: 83.333333%; }
            .col-md-11 { flex: 0 0 91.666667%;max-width: 91.666667%; }
            .col-md-12 { flex: 0 0 100%;      max-width: 100%; }
        }

        /* Contoh styling visual (opsional) */
        .row > .col {
            background: #f5f5f5;
            border: 1px solid #e0e0e0;
            padding: 12px;
            margin-bottom: 12px;
        }

        .text-decoration-none{
            text-decoration: none;
        }
    </style>
    
    @yield('styles')
</head>
<body>
    <div class="container">
        @yield('content')
        @include('backend.email.layouts.footer')
    </div>

</body>
</html>