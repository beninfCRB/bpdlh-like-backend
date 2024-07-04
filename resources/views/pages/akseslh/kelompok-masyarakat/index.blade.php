@extends('layouts.app')

@section('title', 'Data Kelompok Masyarakat')

@section('script')
<script src="{{asset('app/build/akseslh_kelompok_masyarakat.js')}}" type="text/javascript"></script>
@endsection

@section('content')
<!-- Page-Title -->
<div class="row">
    <div class="col-sm-12">
        <h4 class="pull-left page-title">KELOMPOK MASYARAKAT</h4>
        <ol class="breadcrumb pull-right">
            <li><a href="#">Data Master</a></li>
            <li class="active">Daftar Kelompok Masyarakat</li>
        </ol>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">Daftar Kelompok Masyarakat</h3>
            </div>
            <div class="panel-body">
                <div class="row">
                    <button type="button" class="btn btn-inverse waves-effect waves-light pull-right" style="margin-right:10px;margin-bottom:10px;" onclick="window.location='/akseslh/kelompok-masyarakat/create';">Tambah Data</button>
                </div>
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <table id="dt_kelompok_masyarakat" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Jenis Kelompok Masyarakat</th>
                                    <th>Nama Kelompok Masyarakat</th>
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