@extends('layouts.app')

@section('title', 'Daftar Log Jadwal Pembukaan')

@section('script')
    <script src="{{ asset('app/build/log_jadwal_pembukaan.js') }}" type="text/javascript"></script>
@endsection

@section('content')
    <!-- Page-Title -->
    <div class="row">
        <div class="col-sm-12">
            <h4 class="pull-left page-title">LOG JADWAL PEMBUKAAN</h4>
            <ol class="breadcrumb pull-right">
                <li><a href="#">Data Master</a></li>
                <li class="active">Daftar Log Jadwal Pembukaan</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Daftar Jenis Kegiatan</h3>
                    <input type="hidden" name="data-table-log-jadwal-pembukaan" id="data-table-log-jadwal-pembukaan"
                        value="{{ route('data-log-jadwal-pembukaan') }}">
                    <input type="hidden" name="log-jadwal-pembukaan-route" id="log-jadwal-pembukaan-route"
                        value="{{ route('log-jadwal-pembukaan.index') }}">
                    <input type="hidden" name="user-role" id="user-role" value="{{ auth()->user()->role_user }}">
                </div>
                <div class="panel-body">
                    <div class="row">
                        <a href="{{ route('log-jadwal-pembukaan.create') }}"
                            class="btn btn-inverse waves-effect waves-light pull-right"
                            style="margin-right:10px;margin-bottom:10px;">Tambah Data</a>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <table id="dt_log_jadwal_pembukaan" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tanggal Awal</th>
                                        <th>Jam Awal</th>
                                        <th>Tanggal Akhir</th>
                                        <th>Jam Akhir</th>
                                        <th>Batch</th>
                                        <th>Batas Pengajuan</th>
                                        <th>Deleted at</th>
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
