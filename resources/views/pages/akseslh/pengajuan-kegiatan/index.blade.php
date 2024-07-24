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
        </div>
        <div class="panel-body">
          <div class="row">
            <a href="{{ route('pengajuan-kegiatan.create') }}" class="btn btn-inverse waves-effect waves-light pull-right"
              style="margin-right:10px;margin-bottom:10px;">Tambah Data</a>
          </div>
          <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <table id="dt_paket_kegiatan" class="table table-striped table-bordered">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Jenis Kegiatan</th>
                    <th>Tematik Kegiatan</th>
                    <th>Nama Pengajuan Kegiatan</th>
                    <th>Deskripsi Pengajuan Kegiatan</th>
                    <th>Quota Pengajuan Kegiatan</th>
                    <th>Pagu Pengajuan Kegiatan (Rp)</th>
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
