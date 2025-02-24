@extends('layouts.app')

@section('title', 'Data PIC Kelompok Masyarakat')

@section('script')
    <script src="{{ asset('app/build/pic_kelompok_masyarakat.js') }}" type="text/javascript"></script>
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
                    <div class="row" style="margin-bottom: 10px;">
                        <div class="col-md-12">
                            <div class="pull-right">
                                <a href="{{ route('pic-kelompok-masyarakat.create') }}"
                                    class="btn btn-inverse waves-effect waves-light">Tambah Data</a>
                                {{-- <button class="btn btn-success waves-effect waves-light" data-toggle="modal"
                                    data-target=".bs-example-modal-sm">Import Excel</button> --}}
                                <a href="{{ route('pic-kelompok-masyarakat.export') }}"
                                    class="btn btn-success waves-effect waves-light">Export Excel</a>
                            </div>
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
                                        <th>NPWP</th>
                                        <th>Alamat E-Mail</th>
                                        <th>Provinsi</th>
                                        <th>Kota/Kabupaten</th>
                                        <th>Kecamatan</th>
                                        <th>Kelurahan</th>
                                        <th>Alamat</th>
                                        <th>Tempat Lahir</th>
                                        <th>Tanggal Lahir</th>
                                        <th>Agama</th>
                                        <th>Status Perkawinan</th>
                                        <th>Jenis Pekerjaan</th>
                                        <th>Pendidikan Terakhir</th>
                                        <th>No HP</th>
                                        <th>Status Akun</th>
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

            <div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel"
                aria-hidden="true" style="display: none;">
                <div class="modal-dialog modal-sm">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            <h4 class="modal-title" id="mySmallModalLabel">Import Data PIC</h4>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('pic-kelompok-masyarakat.import') }}" method="post"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <label for="fileExcel">File Excel</label>
                                    <input type="file" class="form-control" id="fileExcel" name="fileExcel">
                                </div>
                                <button type="submit" class="btn btn-primary waves-effect waves-light">Submit</button>
                            </form>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->
        </div>

    </div>
    <!-- End Row -->
@endsection
