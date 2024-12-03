@extends('layouts.app')

@section('title', 'Banner Informasi')

@section('script')
    <script src="{{ asset('app/build/banner_informasi.js') }}" type="text/javascript"></script>
@endsection

@section('content')
    <!-- Page-Title -->
    <div class="row">
        <div class="col-sm-12">
            <h4 class="pull-left page-title">BANNER INFORMASI</h4>
            <ol class="breadcrumb pull-right">
                <li><a href="#">Data Master</a></li>
                <li class="active">Daftar Banner Informasi</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Daftar Banner Informasi</h3>
                    <input type="hidden" name="data-table-banner-informasi" id="data-table-banner-informasi"
                        value="{{ route('data-banner-informasi') }}">
                    <input type="hidden" name="banner-informasi-route" id="banner-informasi-route"
                        value="{{ route('banner-informasi.index') }}">
                </div>
                <div class="panel-body">
                    <div class="row">
                        <a href="{{ route('banner-informasi.create') }}"
                            class="btn btn-inverse waves-effect waves-light pull-right"
                            style="margin-right:10px;margin-bottom:10px;">Tambah
                            Data</a>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <table id="dt_banner_informasi" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Deskripsi</th>
                                        <th>Created at</th>
                                        <th>Updated at</th>
                                        <th></th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!-- End Row -->
@endsection
