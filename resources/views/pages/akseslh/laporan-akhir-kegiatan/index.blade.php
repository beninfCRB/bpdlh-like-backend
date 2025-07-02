@extends('layouts.app')

@section('title', 'Laporan Kegiatan')

@section('script')
    <script src="{{ asset('app/build/laporan_akhir_kegiatan.js') }}" type="text/javascript"></script>
@endsection

@section('content')
    <!-- Page-Title -->
    <div class="row">
        <div class="col-sm-12">
            <h4 class="pull-left page-title">LAPORAN AKHIR KEGIATAN</h4>
            <ol class="breadcrumb pull-right">
                <li><a href="#">Pengajuan Kegiatan</a></li>
                <li class="active">Unggah Laporan Akhir Kegiatan</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Unggah Laporan Akhir Kegiatan</h3>
                    <input type="hidden" name="data-table-laporan-akhir-kegiatan" id="data-table-laporan-akhir-kegiatan"
                        value="{{ route('data-laporan-akhir-kegiatan') }}">
                    <input type="hidden" name="laporan-akhir-kegiatan-route" id="laporan-akhir-kegiatan-route"
                        value="{{ route('laporan-akhir-kegiatan.index') }}">
                </div>
                <div class="panel-body">
                    <form action="#" method="post" enctype="multipart/form-data" id="upload-form">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="input-group m-t-10 m-b-10">
                                    <input type="file" id="file-input" name="file" class="form-control"
                                        placeholder="File" />
                                    <span class="input-group-btn">
                                        <button class="btn waves-effect waves-light btn-success" id="submit-button">
                                            Submit
                                        </button>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <table id="dt_laporan_akhir_kegiatan" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>
                                            <input type="checkbox" name="select-all" id="select-all">
                                        </th>
                                        <th>No</th>
                                        <th>Nomor Pengajuan</th>
                                        <th>Kelompok Masyarakat</th>
                                        <th>Nama Pic</th>
                                        <th>Judul Pengajuan</th>
                                        <th>Status</th>
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
