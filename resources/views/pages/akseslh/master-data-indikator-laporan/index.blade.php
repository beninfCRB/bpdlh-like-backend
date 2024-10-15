@extends('layouts.app')

@section('title', 'Data Master Indikator Laporan')

@section('script')
    <script src="{{ asset('app/build/master_data_indikator_laporan.js') }}" type="text/javascript"></script>
@endsection

@section('content')
    <!-- Page-Title -->
    <div class="row">
        <div class="col-sm-12">
            <h4 class="pull-left page-title">DATA MASTER INDIKATOR LAPORAN</h4>
            <ol class="breadcrumb pull-right">
                <li><a href="#">Data Master</a></li>
                <li class="active">Indikator Laporan</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Daftar Master Data Indikator Laporan</h3>
                    <input type="hidden" name="data-table-master-data-indikator-laporan"
                        id="data-table-master-data-indikator-laporan"
                        value="{{ route('data-master-data-indikator-laporan') }}">
                    <input type="hidden" name="master-data-indikator-laporan-route"
                        id="master-data-indikator-laporan-route" value="{{ route('master-data-indikator-laporan.index') }}">
                </div>
                <div class="panel-body">
                    <div class="row">
                        <a href="{{ route('master-data-indikator-laporan.create') }}"
                            class="btn btn-inverse waves-effect waves-light pull-right"
                            style="margin-right:10px;margin-bottom:10px;">Tambah
                            Data</a>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <table id="dt_master_data_indikator_laporan" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Jenis Kegiatan</th>
                                        <th>Sub Tematik Kegiatan</th>
                                        <th>Nama Indikator</th>
                                        <th>Satuan</th>
                                        <th>Tipe Data</th>
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
