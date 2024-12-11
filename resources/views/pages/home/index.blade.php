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
            <div class="mini-stat clearfix bx-shadow">
                <span class="mini-stat-icon bg-info"><i class="fa fa-users"></i></span>
                <div class="mini-stat-info text-right text-muted">
                    <span class="counter" id="counter-users">0</span>
                    Total Users
                </div>
            </div>
        </div>
        <div class="col-md-6 col-sm-6 col-lg-3">
            <div class="mini-stat clearfix bx-shadow">
                <span class="mini-stat-icon bg-warning"><i class="fa fa-user"></i></span>
                <div class="mini-stat-info text-right text-muted">
                    <span class="counter" id="counter-pic">0</span>
                    Total PIC
                </div>
            </div>
        </div>
        <div class="col-md-6 col-sm-6 col-lg-3">
            <div class="mini-stat clearfix bx-shadow">
                <span class="mini-stat-icon bg-success"><i class="fa fa-eye"></i></span>
                <div class="mini-stat-info text-right text-muted">
                    <span class="counter" id="counter-kelompok">0</span>
                    Total Kelompok
                </div>
            </div>
        </div>
        <div class="col-md-6 col-sm-6 col-lg-3">
            <div class="mini-stat clearfix bx-shadow">
                <span class="mini-stat-icon bg-pink"><i class="fa fa-fax"></i></span>
                <div class="mini-stat-info text-right text-muted">
                    <span class="counter" id="counter-pengajuan">0</span>
                    Total Pengajuan
                </div>
            </div>
        </div>

    </div>
    <!-- End row-->
@endsection

@section('script')
    <script src="{{ asset('app/build/home.js') }}" type="text/javascript"></script>
@endsection
