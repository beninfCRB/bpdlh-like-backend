<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="A fully featured admin theme which can be used to build CRM, CMS, etc." />
    <meta name="author" content="Coderthemes" />

    <meta name="application-name" content="{{ config('app.name') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="url" content="{{ env('APP_URL') }}">

    <link rel="shortcut icon" href="{{ asset('images/favicon_1.ico') }}" />

    <title>{{ config('app.name') }} - @yield('title')</title>

    <!-- Base Css Files -->
    <link href="{{asset('css/bootstrap.min.css')}}" rel="stylesheet" />

    <!-- Font Icons -->
    <link href="{{asset('assets/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet" />
    <link href="{{asset('assets/ionicon/css/ionicons.min.css')}}" rel="stylesheet" />
    <link href="{{asset('css/material-design-iconic-font.min.css')}}" rel="stylesheet" />

    <!-- animate css -->
    <link href="{{asset('css/animate.css')}}" rel="stylesheet" />

    <!-- Waves-effect -->
    <link href="{{asset('css/waves-effect.css')}}" rel="stylesheet" />

    <!-- DataTables -->
    <link href="{{ asset('assets/datatables/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css" />

    <!-- sweet alerts -->
    <link href="{{ asset('assets/sweet-alert/sweet-alert.min.css') }}" rel="stylesheet">

    <!-- Custom Files -->
    <link href="{{asset('css/helper.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('css/style.css')}}" rel="stylesheet" type="text/css" />

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->

    <script src="{{ asset('js/modernizr.min.js') }}"></script>
</head>

<body class="fixed-left">
    <!-- Begin page -->
    <div id="wrapper">
        <!-- Top Bar Start -->
        @include('layouts._partials._topbar')
        <!-- Top Bar End -->

        <!-- ========== Left Sidebar Start ========== -->

        @include('layouts._partials._sidebar')
        <!-- Left Sidebar End -->

        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="content-page">
            <!-- Start content -->
            <div class="content">
                <div class="container">
                    @yield('content')

                    <!-- Pls Remove -->
                    <div style="height: 600px"></div>
                </div>
                <!-- container -->
            </div>
            <!-- content -->

            <footer class="footer text-right">2015 © Moltran.</footer>
        </div>
        <!-- ============================================================== -->
        <!-- End Right content here -->
        <!-- ============================================================== -->
    </div>
    <!-- END wrapper -->

    <script>
        var resizefunc = [];
    </script>

    <!-- jQuery  -->
    <script src="{{asset('js/jquery.min.js')}}"></script>
    <script src="{{asset('js/bootstrap.min.js')}}"></script>
    <script src="{{asset('js/waves.js')}}"></script>
    <script src="{{asset('js/wow.min.js')}}"></script>
    <script src="{{asset('js/jquery.nicescroll.js')}}" type="text/javascript"></script>
    <script src="{{asset('js/jquery.scrollTo.min.js')}}"></script>
    <script src="{{asset('assets/jquery-detectmobile/detect.js')}}"></script>
    <script src="{{asset('assets/fastclick/fastclick.js')}}"></script>
    <script src="{{asset('assets/jquery-slimscroll/jquery.slimscroll.js')}}"></script>
    <script src="{{asset('assets/jquery-blockui/jquery.blockUI.js')}}"></script>

    {{-- Sweetalert --}}
    <script src="{{ asset('assets/sweet-alert/sweet-alert.min.js') }}"></script>

    <!-- CUSTOM JS -->
    <script src="{{asset('js/jquery.app.js')}}"></script>

    {{-- Datatable --}}
    <script src="{{asset('assets/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('assets/datatables/dataTables.bootstrap.js')}}"></script>

    @include('layouts._partials._message')

    @yield('script')
</body>

</html>