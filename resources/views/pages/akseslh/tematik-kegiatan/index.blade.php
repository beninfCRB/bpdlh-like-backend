@extends('layouts.app')

@section('title', 'Daftar Tematik Kegiatan')

@section('script')
<script src="{{asset('app/build/tematik_kegiatan.js')}}" type="text/javascript"></script>
@endsection

@section('content')
<!-- Page-Title -->
<div class="row">
    <div class="col-sm-12">
        <h4 class="pull-left page-title">TEMATIK KEGIATAN</h4>
        <ol class="breadcrumb pull-right">
            <li><a href="#">Data Master</a></li>
            <li class="active">Daftar Tematik Kegiatan</li>
        </ol>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">Daftar Tematik Kegiatan</h3>
                <input type="hidden" name="data-table-tematik-kegiatan" id="data-table-tematik-kegiatan"
                    value="{{ route('data-tematik-kegiatan') }}">
            </div>
            <div class="panel-body">
                <div class="row">
                    <a href="{{ route('tematik-kegiatan.create') }}"
                        class="btn btn-inverse waves-effect waves-light pull-right"
                        style="margin-right:10px;margin-bottom:10px;">Tambah Data</a>
                </div>
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <table id="dt_tematik_kegiatan" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tematik Kegiatan</th>
                                    <th>Short</th>
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