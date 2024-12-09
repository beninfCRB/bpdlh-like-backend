@extends('layouts.app')

@section('title', 'Daftar Master Kelompok RAB')

@section('script')
    <script src="{{ asset('app/build/master_komponen_rab.js') }}" type="text/javascript"></script>
@endsection

@section('content')
    <!-- Page-Title -->
    <div class="row">
        <div class="col-sm-12">
            <h4 class="pull-left page-title">Master Kelompok RAB</h4>
            <ol class="breadcrumb pull-right">
                <li><a href="#">Data Master</a></li>
                <li class="active">Daftar Master Kelompok RAB</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Daftar Master Kelompok RAB</h3>
                    <input type="hidden" name="data-table-master-komponen-rab" id="data-table-master-komponen-rab"
                        value="{{ route('data-master-komponen-rab') }}">
                    <input type="hidden" name="master-komponen-rab-route" id="master-komponen-rab-route"
                        value="{{ route('master-komponen-rab.index') }}">
                </div>
                <div class="panel-body">
                    <div class="row">
                        <a href="{{ route('master-komponen-rab.create') }}"
                            class="btn btn-inverse waves-effect waves-light pull-right"
                            style="margin-right:10px;margin-bottom:10px;">Tambah Data</a>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <table id="dt_master_komponen_rab" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Jenis Komponen RAB</th>
                                        <th>Satuan</th>
                                        <th>Komponen RAB</th>
                                        <th>Satuan Harga Unit</th>
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
