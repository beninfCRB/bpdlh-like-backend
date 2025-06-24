@extends('layouts.app')

@section('title', 'Daftar Jenis Dokumen')

@section('script')
    <script src="{{ asset('app/build/jenis_dokumen.js') }}" type="text/javascript"></script>
@endsection

@section('content')
    <!-- Page-Title -->
    <div class="row">
        <div class="col-sm-12">
            <h4 class="pull-left page-title">JENIS DOKUMEN</h4>
            <ol class="breadcrumb pull-right">
                <li><a href="#">Data Master</a></li>
                <li class="active">Daftar Jenis Dokumen</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Daftar Jenis Dokumen</h3>
                    <input type="hidden" name="data-table-jenis-dokumen" id="data-table-jenis-dokumen"
                        value="{{ route('data-jenis-dokumen') }}">
                    <input type="hidden" name="jenis-dokumen-route" id="jenis-dokumen-route"
                        value="{{ route('jenis-dokumen.index') }}">
                    <input type="hidden" name="app_url" id="app_url" value="{{ env('APP_URL') }}">
                </div>
                <div class="panel-body">
                    <div class="row">
                        <a href="{{ route('jenis-dokumen.create') }}"
                            class="btn btn-inverse waves-effect waves-light pull-right"
                            style="margin-right:10px;margin-bottom:10px;">Tambah Data</a>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <table id="dt_jenis_dokumen" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tahapan Pengajuan Kegiatan</th>
                                        <th>Jenis Dokumen</th>
                                        <th>Url Dokumen</th>
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
