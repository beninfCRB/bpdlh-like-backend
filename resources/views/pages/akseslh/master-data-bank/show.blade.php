@extends('layouts.app')

@section('title', 'Edit Master Data Bank')

@section('content')
  <!-- Page-Title -->
  <div class="row">
    <div class="col-sm-12">
      <h4 class="pull-left page-title">KELOLA MASTER DATA BANK</h4>
      <ol class="breadcrumb pull-right">
        <li><a href="#">Data Master</a></li>
        <li><a href="#">Forms</a></li>
        <li class="active">Pengelolaan Data Jenis Komponen RAB</li>
      </ol>
    </div>
  </div>


  <div class="row">
    <!-- Basic example -->
    <div class="col-md-6">
      <div class="panel panel-primary">
        <div class="panel-heading">
          <h3 class="panel-title">Pengelolaan Data Jenis Komponen RAB</h3>
        </div>
        <div class="panel-body">
          <form role="form" action="{{ route('jenis-komponen-rab.update', $data->data->id) }}" method="POST">
            @method('PUT')
            @csrf
            <div class="form-group @error('jenis-komponen-rab') has-error @enderror">
              <label for="jenis-komponen-rab">Jenis Komponen RAB <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="jenis-komponen-rab" name="jenis_komponen_rab"
                placeholder="Jenis Komponen RAB" value="{{ old('jenis_komponen_rab', $data->data->jenis_komponen_rab) }}">
              @error('jenis_komponen_rab')
                {{ $message }}
              @enderror
            </div>
            {{-- <button type="submit" class="btn btn-primary waves-effect waves-light">Submit</button> --}}
          </form>
        </div><!-- panel-body -->
      </div> <!-- panel -->
    </div> <!-- col-->

  </div> <!-- End row -->
@endsection
