@extends('layouts.app')

@section('title', 'Data Paket Kegiatan')

@section('script')
<script src="{{asset('app/build/akseslh_paket_kegiatan.js')}}" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function() {
    $('#dt_paket_kegiatan').dataTable();
} );
</script>
@endsection

@section('content')
<!-- Page-Title -->
<div class="row">
    <div class="col-sm-12">
        <h4 class="pull-left page-title">PAKET KEGIATAN</h4>
        <ol class="breadcrumb pull-right">
            <li><a href="#">Data Master</a></li>
            <li class="active">Daftar Paket Kegiatan</li>
        </ol>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">Daftar Paket Kegiatan</h3>
            </div>
            <div class="panel-body">
                <div class="row">
                    <button type="button" class="btn btn-inverse waves-effect waves-light pull-right" style="margin-right:10px;margin-bottom:10px;" onclick="window.location='/akseslh/paket-kegiatan/create';">Tambah Data</button>
                </div>
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <table id="dt_paket_kegiatan" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Jenis Kegiatan</th>
                                    <th>Nama Paket Kegiatan</th>
                                    <th>Deskripsi Paket Kegiatan</th>
                                    <th>Quota Paket Kegiatan</th>
                                    <th>Pagu Paket Kegiatan (Rp)</th>
                                    <th>Tahap Pencairan</th>
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