@extends('layouts.app')

@section('title', 'Home')

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <h4 class="pull-left page-title">Home Page</h4>
            <ol class="breadcrumb pull-right">
                <li><a href="{{ route('home') }}">Dashboard</a></li>
                <li class="active">Home Page</li>
            </ol>
        </div>
    </div>

    <!--Widget-4 -->
    <div class="row">
        <div class="col-md-6 col-sm-6 col-lg-3">
            <div class="mini-stat clearfix bx-shadow">
                <span class="mini-stat-icon bg-info"><i class="fa fa-usd"></i></span>
                <div class="mini-stat-info text-right text-muted">
                    <span class="counter">15852</span>
                    Total Users
                </div>
            </div>
        </div>
        <div class="col-md-6 col-sm-6 col-lg-3">
            <div class="mini-stat clearfix bx-shadow">
                <span class="mini-stat-icon bg-warning"><i class="fa fa-shopping-cart"></i></span>
                <div class="mini-stat-info text-right text-muted">
                    <span class="counter">956</span>
                    Total PIC
                </div>
            </div>
        </div>
        <div class="col-md-6 col-sm-6 col-lg-3">
            <div class="mini-stat clearfix bx-shadow">
                <span class="mini-stat-icon bg-pink"><i class="fa fa-user"></i></span>
                <div class="mini-stat-info text-right text-muted">
                    <span class="counter">5210</span>
                    New Users
                </div>
            </div>
        </div>
        <div class="col-md-6 col-sm-6 col-lg-3">
            <div class="mini-stat clearfix bx-shadow">
                <span class="mini-stat-icon bg-success"><i class="fa fa-eye"></i></span>
                <div class="mini-stat-info text-right text-muted">
                    <span class="counter">20544</span>
                    Unique Visitors
                </div>
            </div>
        </div>
    </div>
    <!-- End row-->
@endsection

@section('script')
    <script src="{{ asset('app/build/home.js') }}" type="text/javascript"></script>
@endsection
