@extends('layouts.app')

@section('title', 'Data PIC Kelompok Masyarakat')

@section('script')
<script src="{{asset('app/build/pic_kelompok_masyarakat.js')}}" type="text/javascript"></script>
@endsection

@section('content')
<!-- Page-Title -->
<div class="row">
    <div class="col-sm-12">
        <h4 class="pull-left page-title">DATA PIC KELOMPOK MASYARAKAT</h4>
        <ol class="breadcrumb pull-right">
            <li><a href="#">Data Master</a></li>
            <li class="active">Daftar PIC Kelompok Masyarakat</li>
        </ol>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">Daftar PIC Kelompok Masyarakat</h3>
                <input type="hidden" name="data-table-pic-kelompok-masyarakat" id="data-table-pic-kelompok-masyarakat"
                    value="{{ route('data-pic-kelompok-masyarakat') }}">
                <input type="hidden" name="pic-kelompok-masyarakat-route" id="pic-kelompok-masyarakat-route"
                    value="{{ route('pic-kelompok-masyarakat.index') }}">
            </div>
            <div class="panel-body">
                <div class="row justify-content-end">
                    <div class="col-md-6"></div>
                    <div class="col col-md-6 bg-danger">
                        <a href="{{ route('pic-kelompok-masyarakat.create') }}"
                            class="btn btn-inverse waves-effect waves-light">Tambah Data</a>
                        <button class="btn btn-success waves-effect waves-light">Import Data</button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <table id="dt_pic_kelompok_masyarakat" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Kelompok</th>
                                    <th>Jenis Kelompok</th>
                                    <th>Nama PIC</th>
                                    <th>Jenis Identitas PIC</th>
                                    <th>No Identitas PIC</th>
                                    <th>Alamat E-Mail</th>
                                    <th>No HP</th>
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