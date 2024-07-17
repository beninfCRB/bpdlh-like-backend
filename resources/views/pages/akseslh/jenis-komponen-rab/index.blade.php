@extends('layouts.app')

@section('title', 'Daftar Jenis Kelompok RAB')

@section('script')
  <script src="{{ asset('app/build/jenis_komponen_rab.js') }}" type="text/javascript"></script>
@endsection

@section('content')
  <!-- Page-Title -->
  <div class="row">
    <div class="col-sm-12">
      <h4 class="pull-left page-title">Jenis Kelompok RAB</h4>
      <ol class="breadcrumb pull-right">
        <li><a href="#">Data Master</a></li>
        <li class="active">Daftar Jenis Kelompok RAB</li>
      </ol>
    </div>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="panel panel-primary">
        <div class="panel-heading">
          <h3 class="panel-title">Daftar Jenis Kelompok RAB</h3>
          <input type="hidden" name="data-table-jenis-komponen-rab" id="data-table-jenis-komponen-rab"
            value="{{ route('data-jenis-komponen-rab') }}">
        </div>
        <div class="panel-body">
          <div class="row">
            <a href="{{ route('jenis-komponen-rab.create') }}" class="btn btn-inverse waves-effect waves-light pull-right"
              style="margin-right:10px;margin-bottom:10px;">Tambah Data</a>
          </div>
          <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <table id="dt_jenis_komponen_rab" class="table table-striped table-bordered">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Jenis Komponen RAB</th>
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
