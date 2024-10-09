@extends('layouts.app')

@section('title', 'Tahapan Pengajuan Kegiatan')

@section('script')
    <script src="{{ asset('app/build/tahapan_pengajuan_kegiatan.js') }}" type="text/javascript"></script>
@endsection

@section('content')
    <!-- Page-Title -->
    <div class="row">
        <div class="col-sm-12">
            <h4 class="pull-left page-title">TAHAPAN PENGAJUAN KEGIATAN</h4>
            <ol class="breadcrumb pull-right">
                <li><a href="#">Data Master</a></li>
                <li class="active">Tahapan Pengajuan Kegiatan</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Tahapan Pengajuan Kegiatan</h3>
                    <input type="hidden" name="data-tahapan-pengajuan-kegiatan" id="data-tahapan-pengajuan-kegiatan"
                        value="{{ route('data-tahapan-pengajuan-kegiatan') }}">
                    <input type="hidden" name="tahapan-pengajuan-kegiatan-route" id="tahapan-pengajuan-kegiatan-route"
                        value="{{ route('tahapan-pengajuan-kegiatan.index') }}">
                </div>
                <div class="panel-body">
                    <div class="row">
                        <a href="{{ route('tahapan-pengajuan-kegiatan.create') }}"
                            class="btn btn-inverse waves-effect waves-light pull-right"
                            style="margin-right:10px;margin-bottom:10px;">Tambah Data</a>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <table id="dt_tahapan_pengajuan_kegiatan" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Deskripsi Kegiatan</th>
                                        <th>Sort/Urutan</th>
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
