@extends('layouts.app')

@section('title', 'Laporan Kegiatan')

@section('script')
    <script src="{{ asset('app/build/laporan_kegiatan.js') }}" type="text/javascript"></script>
@endsection

@section('content')
    <!-- Page-Title -->
    <div class="row">
        <div class="col-sm-12">
            <h4 class="pull-left page-title">LAPORAN KEGIATAN</h4>
            <ol class="breadcrumb pull-right">
                <li><a href="#">Pengajuan Kegiatan</a></li>
                <li class="active">Daftar Laporan Kegiatan</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Daftar Laporan Kegiatan</h3>
                    <input type="hidden" name="data-table-laporan-kegiatan" id="data-table-laporan-kegiatan"
                        value="{{ route('data-laporan-kegiatan') }}">
                    <input type="hidden" name="laporan-kegiatan-route" id="laporan-kegiatan-route"
                        value="{{ route('laporan-kegiatan.index') }}">
                </div>
                <div class="panel-body">
                    <form action="#" method="post" enctype="multipart/form-data" id="upload-form">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="input-group m-t-10 m-b-10">
                                    <input type="file" id="file" name="file" class="form-control"
                                        placeholder="File" />
                                    <span class="input-group-btn">
                                        <button class="btn waves-effect waves-light btn-success">
                                            Submit
                                        </button>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <table id="dt_laporan_kegiatan" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>
                                                <input type="checkbox" name="select-all" id="select-all">
                                            </th>
                                            <th>No</th>
                                            <th>Nomor Pengajuan</th>
                                            <th>User Akseslh</th>
                                            <th>Judul Pengajuan</th>
                                            <th>Status</th>
                                            <th>Created at</th>
                                            <th>Updated at</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- End Row -->
@endsection
