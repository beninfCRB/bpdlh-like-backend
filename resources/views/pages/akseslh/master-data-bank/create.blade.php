@extends('layouts.app')

@section('title', 'Buat Master Data Bank')

@section('content')
  <!-- Page-Title -->
  <div class="row">
    <div class="col-sm-12">
      <h4 class="pull-left page-title">KELOLA DATA MASTER DATA BANK</h4>
      <ol class="breadcrumb pull-right">
        <li><a href="#">Data Master</a></li>
        <li><a href="{{ route('master-data-bank.index') }}">Forms</a></li>
        <li class="active">Pengelolaan Data Master Data Bank</li>
      </ol>
    </div>
  </div>


  <div class="row">
    <!-- Basic example -->
    <div class="col-md-6">
      <div class="panel panel-primary">
        <div class="panel-heading">
          <h3 class="panel-title">Pengelolaan Data Master Data Bank</h3>
        </div>
        <div class="panel-body">
          <form role="form" action="{{ route('master-data-bank.store') }}" method="POST" autocomplete="off">
            @csrf
            <div class="form-group @error('nama_bank') has-error @enderror">
              <label for="nama-bank">Nama Bank <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="nama-bank" name="nama_bank" placeholder="Nama Bank">
              @error('nama_bank')
                {{ $message }}
              @enderror
            </div>
            <div class="form-group @error('kode_bank') has-error @enderror">
              <label for="kode-bank">Kode Bank <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="kode-bank" name="kode_bank" placeholder="Kode Bank">
              @error('kode_bank')
                {{ $message }}
              @enderror
            </div>
            <div class="row">
              <button type="submit" class="btn btn-primary waves-effect waves-light">Simpan</button>
              <a href="{{ route('master-data-bank.index') }}" class="btn btn-inverse waves-effect waves-light">Kembali</a>
            </div>
          </form>
        </div><!-- panel-body -->
      </div> <!-- panel -->
    </div> <!-- col-->

  </div> <!-- End row -->
@endsection
