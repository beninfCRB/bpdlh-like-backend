<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="" />
    <meta name="author" content="" />

    <meta name="application-name" content="{{ config('app.name') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="url" content="{{ env('APP_URL') }}">

    <link rel="shortcut icon" href="{{ asset('template/images/favicon_1.ico') }}" />

    <title>{{ Str::words(config('app.name'), 3) }} - @yield('title')</title>

    <!-- Base Css Files -->
    <link href="{{ asset('template/css/bootstrap.min.css') }}" rel="stylesheet" />

    <!-- Font Icons -->
    <link href="{{ asset('template/assets/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('template/assets/ionicon/css/ionicons.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('template/css/material-design-iconic-font.min.css') }}" rel="stylesheet" />

    <!-- animate css -->
    <link href="{{ asset('template/css/animate.css') }}" rel="stylesheet" />

    <!-- Waves-effect -->
    <link href="{{ asset('template/css/waves-effect.css') }}" rel="stylesheet" />

    <!-- DataTables -->
    <link href="{{ asset('template/assets/datatables/jquery.dataTables.min.css') }}" rel="stylesheet"
        type="text/css" />

    <!-- Custom Files -->
    <link href="{{ asset('template/css/helper.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('template/css/style.css') }}" rel="stylesheet" type="text/css" />

    <!-- Select2 -->
    <link href="{{ asset('template/assets/select2/select2.css') }}" rel="stylesheet" type="text/css" />

    <link href="{{ asset('template/assets/responsive-table/rwd-table.min.css') }}" rel="stylesheet" type="text/css"
        media="screen" />

    <link href="{{ asset('template/assets/summernote/summernote.css') }}" rel="stylesheet" />

    @yield('style')

    <script src="{{ asset('template/js/modernizr.min.js') }}"></script>
</head>

<body class="{{ Route::is('home') ? 'widescreen fixed-left-void' : 'fixed-left' }}">
    <!-- Begin page -->
    <div id="wrapper" class="{{ Route::is('home') ? 'forced enlarged' : '' }}">
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
                    <div style=""></div>
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
    <script src="{{ asset('template/js/jquery.min.js') }}"></script>
    <script src="{{ asset('template/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('template/js/waves.js') }}"></script>
    <script src="{{ asset('template/js/wow.min.js') }}"></script>
    <script src="{{ asset('template/js/jquery.nicescroll.js') }}" type="text/javascript"></script>
    <script src="{{ asset('template/js/jquery.scrollTo.min.js') }}"></script>
    <script src="{{ asset('template/assets/jquery-detectmobile/detect.js') }}"></script>
    <script src="{{ asset('template/assets/fastclick/fastclick.js') }}"></script>
    <script src="{{ asset('template/assets/jquery-slimscroll/jquery.slimscroll.js') }}"></script>
    <script src="{{ asset('template/assets/jquery-blockui/jquery.blockUI.js') }}"></script>

    <!-- CUSTOM JS -->
    <script src="{{ asset('template/js/jquery.app.js') }}"></script>

    {{-- Datatable --}}
    <script src="{{ asset('template/assets/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('template/assets/datatables/dataTables.bootstrap.js') }}"></script>

    {{-- Select 2 --}}
    <script src="{{ asset('template/assets/select2/select2.min.js') }}" type="text/javascript"></script>

    {{-- Mix --}}
    <script src="{{ asset('app/build/app.js') }}" type="text/javascript"></script>

    <script src="{{ asset('template/assets/summernote/summernote.min.js') }}"></script>

    @include('layouts._partials._message')

    @yield('script')
</body>

</html>
