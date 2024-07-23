@extends('layouts.app')

@section('title', 'Daftar Master Data Bank')

@section('script')
  <script src="{{ asset('app/build/master_data_bank.js') }}" type="text/javascript"></script>
@endsection

@section('content')
  <!-- Page-Title -->
  <div class="row">
    <div class="col-sm-12">
      <h4 class="pull-left page-title">Master Data Bank</h4>
      <ol class="breadcrumb pull-right">
        <li><a href="#">Data Master</a></li>
        <li class="active">Daftar Master Data Bank</li>
      </ol>
    </div>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="panel panel-primary">
        <div class="panel-heading">
          <h3 class="panel-title">Daftar Master Data Bank</h3>
          <input type="hidden" name="data-table-master-data-bank" id="data-table-master-data-bank"
            value="{{ route('data-master-data-bank') }}">
          <input type="hidden" name="master-data-bank-route" id="master-data-bank-route"
            value="{{ route('master-data-bank.index') }}">
        </div>
        <div class="panel-body">
          <div class="row">
            <a href="{{ route('master-data-bank.create') }}" class="btn btn-inverse waves-effect waves-light pull-right"
              style="margin-right:10px;margin-bottom:10px;">Tambah Data</a>
          </div>
          <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <table id="dt_master_data_bank" class="table table-striped table-bordered">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Nama Bank</th>
                    <th>Kode Bank</th>
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
