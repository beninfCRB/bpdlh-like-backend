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
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet" />

    <!-- Font Icons -->
    <link href="{{ asset('assets/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/ionicon/css/ionicons.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/material-design-iconic-font.min.css') }}" rel="stylesheet">

    <!-- animate css -->
    <link href="{{ asset('css/animate.css') }}" rel="stylesheet" />

    <!-- Waves-effect -->
    <link href="{{ asset('css/waves-effect.css') }}" rel="stylesheet">

    <!-- Custom Files -->
    <link href="{{ asset('css/helper.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('css/style.css') }}" rel="stylesheet" type="text/css" />

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->

    <script src="{{ asset('js/modernizr.min.js') }}"></script>

</head>

<body>

    <div class="wrapper-page">
        @yield('content')
    </div>

    <script>
        var resizefunc = [];
    </script>
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/waves.js') }}"></script>
    <script src="{{ asset('js/wow.min.js') }}"></script>
    <script src="{{ asset('js/jquery.nicescroll.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/jquery.scrollTo.min.js') }}"></script>
    <script src="{{ asset('assets/jquery-detectmobile/detect.js') }}"></script>
    <script src="{{ asset('assets/fastclick/fastclick.js') }}"></script>
    <script src="{{ asset('assets/jquery-slimscroll/jquery.slimscroll.js') }}"></script>
    <script src="{{ asset('assets/jquery-blockui/jquery.blockUI.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/dayjs@1/dayjs.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- CUSTOM JS -->
    <script src="{{ asset('js/jquery.app.js') }}"></script>

    @include('layouts._partials._message')

</body>

</html>
