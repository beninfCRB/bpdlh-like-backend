@extends('layouts.app')

@section('title', 'Tolak Pengajuan dan Profil')

@section('script')
    {{-- <script src="{{ asset('app/build/laporan_akhir_kegiatan.js') }}" type="text/javascript"></script> --}}
@endsection

@section('content')
    <!-- Page-Title -->
    <div class="row">
        <div class="col-sm-12">
            <h4 class="pull-left page-title">TOLAK PENGAJUAN DAN PROFIL</h4>
            <ol class="breadcrumb pull-right">
                <li><a href="#">Kelola Penolakan</a></li>
                <li class="active">Tolak Pengajuan dan Profil</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Unggah Template</h3>
                    <input type="hidden" name="data-table-tolak-pengajuan-dan-profil"
                        id="data-table-tolak-pengajuan-dan-profil" value="{{ route('data-tolak-pengajuan-dan-profil') }}">
                    <input type="hidden" name="tolak-pengajuan-dan-profil-route" id="tolak-pengajuan-dan-profil-route"
                        value="{{ route('tolak-pengajuan-dan-profil.index') }}">
                </div>
                <div class="panel-body">
                    <form action="#" method="post" enctype="multipart/form-data" id="upload-form">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="input-group m-t-10 m-b-10">
                                    <input type="file" id="file-input" name="file" class="form-control"
                                        placeholder="File"
                                        accept=".csv, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" />
                                    <span class="input-group-btn">
                                        <button class="btn waves-effect waves-light btn-success" id="unggah-button"
                                            onclick="unggah()" type="button">
                                            Unggah
                                        </button>
                                        <button class="btn waves-effect waves-light btn-info" id="submit-button">
                                            Submit
                                        </button>
                                        <button class="btn waves-effect waves-light btn-primary" id="unduh-button">
                                            Unduh Template
                                        </button>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <table id="dt_tolak_pengajuan_dan_profil" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nomor Pengajuan</th>
                                        <th>Email PIC</th>
                                        <th>Status Penolakan</th>
                                        <th>Catatan Penolakan</th>
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
