@extends('layouts.app')

@section('title', 'Daftar Satuan')

@section('script')
<script src="{{ asset('app/build/satuan.js') }}" type="text/javascript"></script>
@endsection

@section('content')
<!-- Page-Title -->
<div class="row">
  <div class="col-sm-12">
    <h4 class="pull-left page-title">Satuan</h4>
    <ol class="breadcrumb pull-right">
      <li><a href="#">Data Master</a></li>
      <li class="active">Daftar Satuan</li>
    </ol>
  </div>
</div>

<div class="row">
  <div class="col-md-12">
    <div class="panel panel-primary">
      <div class="panel-heading">
        <h3 class="panel-title">Daftar Satuan</h3>
        <input type="hidden" name="data-table-satuan" id="data-table-satuan" value="{{ route('data-satuan') }}">
        <input type="hidden" name="satuan-route" id="satuan-route" value="{{ route('satuan.index') }}">
      </div>
      <div class="panel-body">
        <div class="row">
          <a href="{{ route('satuan.create') }}" class="btn btn-inverse waves-effect waves-light pull-right"
            style="margin-right:10px;margin-bottom:10px;">Tambah Data</a>
        </div>
        <div class="row">
          <div class="col-md-12 col-sm-12 col-xs-12">
            <table id="dt_satuan" class="table table-striped table-bordered">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Satuan</th>
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