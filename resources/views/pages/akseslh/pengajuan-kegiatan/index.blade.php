@extends('layouts.app')

@section('title', 'Data Pengajuan Kegiatan')

@section('script')
    <script src="{{ asset('app/build/pengajuan_kegiatan.js') }}" type="text/javascript"></script>
@endsection

@section('content')
    <!-- Page-Title -->
    <div class="row">
        <div class="col-sm-12">
            <h4 class="pull-left page-title">PENGAJUAN KEGIATAN</h4>
            <ol class="breadcrumb pull-right">
                <li><a href="#">Data Master</a></li>
                <li class="active">Daftar Pengajuan Kegiatan</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Daftar Pengajuan Kegiatan</h3>
                    <input type="hidden" name="data-table-pengajuan-kegiatan" id="data-table-pengajuan-kegiatan"
                        value="{{ route('data-pengajuan-kegiatan') }}">
                    <input type="hidden" name="pengajuan-kegiatan-route" id="pengajuan-kegiatan-route"
                        value="{{ route('pengajuan-kegiatan.index') }}">
                </div>
                <div class="panel-body">
                    <div class="row m-b-10">
                        <div class="col-md-4"></div>
                        <div class="col-md-8 text-right">
                            <form class="form-inline" role="form" action="{{ route('export-excel-pengajuan') }}"
                                method="POST">
                                @csrf
                                <div class="form-group m-l-10">
                                    <label for="tanggal_awal">Tanggal Awal</label>
                                    <input type="date" class="form-control" id="tanggal_awal" name="tanggal_awal"
                                        value="{{ old('tanggal_awal') }}" />
                                    @error('tanggal_awal')
                                        <span class="error">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group m-l-10">
                                    <label for="tanggal_akhir">Tanggal Akhir</label>
                                    <input type="date" class="form-control" id="tanggal_akhir" name="tanggal_akhir"
                                        value="{{ old('tanggal_akhir') }}" />
                                    @error('tanggal_akhir')
                                        <span class="error">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                                <button type="submit" class="btn btn-success waves-effect waves-light m-l-10">
                                    Export Excel
                                </button>
                            </form>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <table id="dt_pengajuan_kegiatan" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Jenis Kelompok Masyarakat</th>
                                        <th>Kelompok Masyarakat</th>
                                        <th>Nama PIC</th>
                                        <th>Jenis Identitas PIC</th>
                                        <th>Nomor Identitas PIC</th>
                                        <th>Kelurahan PIC</th>
                                        <th>Kecamatan PIC</th>
                                        <th>Kabupaten PIC</th>
                                        <th>Provinsi PIC</th>
                                        <th>Email</th>
                                        <th>No. HP</th>
                                        <th>Status User</th>
                                        <th>Role User</th>
                                        <th>Nomor Pengajuan</th>
                                        <th>Tematik Kegiatan</th>
                                        <th>Sub Tematik Kegiatan</th>
                                        <th>Jenis Kegiatan</th>
                                        <th>Nama Kelurahan Kegiatan</th>
                                        <th>Nama Kecamatan Kegiatan</th>
                                        <th>Nama Kabupaten Kegiatan</th>
                                        <th>Nama Provinsi Kegiatan</th>
                                        <th>Jumlah Peserta</th>
                                        <th>Judul Pengajuan Kegiatan</th>
                                        <th>Alamat Kegiatan</th>
                                        <th>Tanggal Kegiatan</th>
                                        <th>Waktu Kegiatan</th>
                                        <th>Proposal Kegiatan</th>
                                        <th>Ruang Lingkup Kegiatan</th>
                                        <th>Total RAB</th>
                                        <th>Total Dana Dicairkan</th>
                                        <th>Flag</th>
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

            <!--  Modal content for the above example -->
            <div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
                aria-hidden="true" style="display: none;">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            <h4 class="modal-title" id="myLargeModalLabel">Large modal</h4>
                        </div>
                        <div class="modal-body">
                            ...
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->
        </div>
    </div>
    <!-- End Row -->
@endsection
