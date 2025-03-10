@extends('layouts.app')

@section('title', 'Home')

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <h4 class="pull-left page-title">Home Page</h4>
            <ol class="breadcrumb pull-right">
                <li><a href="{{ route('home') }}">Dashboard</a></li>
                <li class="active">Home Page</li>
                <input type="hidden" id="url" name="url" value="{{ url('') }}">
            </ol>
        </div>
    </div>

    <!--Widget-4 -->
    <div class="row">
        <div class="col-md-6 col-sm-6 col-lg-3">
            <a href="{{ route('user-akseslh.index') }}">
                <div class="mini-stat clearfix bx-shadow">
                    <span class="mini-stat-icon bg-info"><i class="fa fa-users"></i></span>
                    <div class="mini-stat-info text-right text-muted">
                        <span class="counter" id="counter-users">0</span>
                        Total Users
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-6 col-sm-6 col-lg-3">
            <a href="{{ route('pic-kelompok-masyarakat.index') }}">
                <div class="mini-stat clearfix bx-shadow">
                    <span class="mini-stat-icon bg-warning"><i class="fa fa-user"></i></span>
                    <div class="mini-stat-info text-right text-muted">
                        <span class="counter" id="counter-pic">0</span>
                        Total PIC
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-6 col-sm-6 col-lg-3">
            <a href="{{ route('kelompok-masyarakat.index') }}">
                <div class="mini-stat clearfix bx-shadow">
                    <span class="mini-stat-icon bg-success"><i class="fa fa-eye"></i></span>
                    <div class="mini-stat-info text-right text-muted">
                        <span class="counter" id="counter-kelompok">0</span>
                        Total Kelompok
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-6 col-sm-6 col-lg-3">
            <a href="{{ route('pengajuan-kegiatan.index') }}">
                <div class="mini-stat clearfix bx-shadow">
                    <span class="mini-stat-icon bg-pink"><i class="fa fa-fax"></i></span>
                    <div class="mini-stat-info text-right text-muted">
                        <span class="counter" id="counter-pengajuan">0</span>
                        Total Pengajuan
                    </div>
                </div>
            </a>
        </div>

    </div>
    <!-- End row-->

    <div style="
    zoom: 0.7;
    -moz-transform: scale(0.7);
    ">
        <div class='tableauPlaceholder' id='viz1741573110369' style='position: relative'><noscript><a href='#'><img
                        alt='Dashboard '
                        src='https:&#47;&#47;public.tableau.com&#47;static&#47;images&#47;La&#47;LayananDanaMasyarakat&#47;Dashboard&#47;1_rss.png'
                        style='border: none' /></a></noscript><object class='tableauViz' style='display:none;'>
                <param name='host_url' value='https%3A%2F%2Fpublic.tableau.com%2F' />
                <param name='embed_code_version' value='3' />
                <param name='path'
                    value='views&#47;LayananDanaMasyarakat&#47;Dashboard?:language=en-US&amp;:embed=true&amp;publish=yes&amp;:sid=&amp;:redirect=auth' />
                <param name='toolbar' value='yes' />
                <param name='static_image'
                    value='https:&#47;&#47;public.tableau.com&#47;static&#47;images&#47;La&#47;LayananDanaMasyarakat&#47;Dashboard&#47;1.png' />
                <param name='animate_transition' value='yes' />
                <param name='display_static_image' value='yes' />
                <param name='display_spinner' value='yes' />
                <param name='display_overlay' value='yes' />
                <param name='display_count' value='yes' />
                <param name='language' value='en-US' />
                <param name='filter' value='publish=yes' />
            </object></div>
        <script type='text/javascript'>
            var divElement = document.getElementById('viz1741573110369');
            var vizElement = divElement.getElementsByTagName('object')[0];
            if (divElement.offsetWidth > 800) {
                vizElement.style.width = '100%';
                vizElement.style.height = (divElement.offsetWidth * 0.75) + 'px';
            } else if (divElement.offsetWidth > 500) {
                vizElement.style.width = '100%';
                vizElement.style.height = (divElement.offsetWidth * 0.75) + 'px';
            } else {
                vizElement.style.width = '100%';
                vizElement.style.height = '1977px';
            }
            var scriptElement = document.createElement('script');
            scriptElement.src = 'https://public.tableau.com/javascripts/api/viz_v1.js';
            vizElement.parentNode.insertBefore(scriptElement, vizElement);
        </script>
    </div>

@endsection

@section('script')
    <script src="{{ asset('app/build/home.js') }}" type="text/javascript"></script>
@endsection
