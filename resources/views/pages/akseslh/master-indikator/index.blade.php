@extends('layouts.app')

@section('title', 'Master Indikator')

@section('script')
    <script src="{{ asset('app/build/master_indikator.js') }}" type="text/javascript"></script>
@endsection

@section('content')
    <!-- Page-Title -->
    <div class="row">
        <div class="col-sm-12">
            <h4 class="pull-left page-title">MASTER INDIKATOR</h4>
            <ol class="breadcrumb pull-right">
                <li><a href="#">Data Master</a></li>
                <li class="active">Daftar Master Indikator</li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Daftar Master Indikator</h3>
                    <input type="hidden" name="data-table-master-indikator" id="data-table-master-indikator"
                        value="{{ route('data-master-indikator') }}">
                    <input type="hidden" name="master-indikator-route" id="master-indikator-route"
                        value="{{ route('master-indikator.index') }}">
                </div>
                <div class="panel-body">
                    <div class="row">
                        <a href="{{ route('master-indikator.create') }}"
                            class="btn btn-inverse waves-effect waves-light pull-right"
                            style="margin-right:10px;margin-bottom:10px;">Tambah
                            Data</a>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <table id="dt_master_indikator" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Indikator</th>
                                        <th>Satuan</th>
                                        <th>Tipe Data</th>
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
