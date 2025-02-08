@extends('layouts.app')

@section('title', 'Jenis Kelompok Masyarakat')

@section('script')
    <script src="{{ asset('app/build/jenis_kelompok_masyarakat.js') }}" type="text/javascript"></script>
@endsection

@section('content')
    <!-- Page-Title -->
    <div class="row">
        <div class="col-sm-12">
            <h4 class="pull-left page-title">JENIS KELOMPOK MASYARAKAT</h4>
            <ol class="breadcrumb pull-right">
                <li><a href="#">Data Master</a></li>
                <li class="active">Daftar Jenis Kelompok Masyarakat</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Daftar Jenis Kelompok Masyarakat</h3>
                    <input type="hidden" name="data-table-jenis-kelompok-masyarakat"
                        id="data-table-jenis-kelompok-masyarakat" value="{{ route('data-jenis-kelompok-masyarakat') }}">
                    <input type="hidden" name="jenis-kelompok-masyarakat-route" id="jenis-kelompok-masyarakat-route"
                        value="{{ route('jenis-kelompok-masyarakat.index') }}">
                </div>
                <div class="panel-body">
                    <div class="row">
                        <a href="{{ route('jenis-kelompok-masyarakat.create') }}"
                            class="btn btn-inverse waves-effect waves-light pull-right"
                            style="margin-right:10px;margin-bottom:10px;">Tambah
                            Data</a>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <table id="dt_jenis_kelompok_masyarakat" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Jenis Kelompok Masyarakat</th>
                                        <th>Short ID</th>
                                        <th>Code ID</th>
                                        <th>Status</th>
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
