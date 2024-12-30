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

    <link rel="shortcut icon" href="images/favicon_1.ico" />

    <title>{{ config('app.name') }}</title>

    <!-- Base Css Files -->
    <link href="{{ asset('template/css/bootstrap.min.css') }}" rel="stylesheet" />

    <!-- Font Icons -->
    <link href="{{ asset('template/assets/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('template/assets/ionicon/css/ionicons.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('template/css/material-design-iconic-font.min.css') }}" rel="stylesheet">

    <!-- animate css -->
    <link href="{{ asset('template/css/animate.css') }}" rel="stylesheet" />

    <!-- Waves-effect -->
    <link href="{{ asset('template/css/waves-effect.css') }}" rel="stylesheet">

    <!-- Custom Files -->
    <link href="{{ asset('template/css/helper.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('template/css/style.css') }}" rel="stylesheet" type="text/css" />

    <script src="{{ asset('template/js/modernizr.min.js') }}"></script>
</head>

<body>

    <div class="wrapper-page">
        @yield('content')
    </div>

    <script>
        var resizefunc = [];
    </script>
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

    @include('layouts._partials._message')

</body>

</html>
