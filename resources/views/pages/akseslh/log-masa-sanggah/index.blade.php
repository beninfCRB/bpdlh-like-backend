@extends('layouts.app')

@section('title', 'Daftar Log Masa Sanggah')

@section('script')
    <script src="{{ asset('app/build/log_masa_sanggah.js') }}" type="text/javascript"></script>
@endsection

@section('content')
    <!-- Page-Title -->
    <div class="row">
        <div class="col-sm-12">
            <h4 class="pull-left page-title">LOG MASA SANGGAH</h4>
            <ol class="breadcrumb pull-right">
                <li><a href="#">Data Master</a></li>
                <li class="active">Daftar Log Masa Sanggah</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Daftar Log Masa Sanggah</h3>
                    <input type="hidden" name="data-table-log-masa-sanggah" id="data-table-log-masa-sanggah"
                        value="{{ route('data-log-masa-sanggah') }}">
                    <input type="hidden" name="log-masa-sanggah-route" id="log-masa-sanggah-route"
                        value="{{ route('log-masa-sanggah.index') }}">
                </div>
                <div class="panel-body">
                    <div class="row">
                        <a href="{{ route('log-masa-sanggah.create') }}"
                            class="btn btn-inverse waves-effect waves-light pull-right"
                            style="margin-right:10px;margin-bottom:10px;">Tambah Data</a>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <table id="dt_log_masa_sanggah" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tanggal Awal</th>
                                        <th>Tanggal Akhir</th>
                                        <th>Jam Awal</th>
                                        <th>Jam Akhir</th>
                                        <th>Batas Pengajuan</th>
                                        <th>Deleted at</th>
                                        <th>Created at</th>
                                        <th>Updated at</th>
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
