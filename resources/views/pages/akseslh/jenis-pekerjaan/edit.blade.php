@extends('layouts.app')

@section('title', 'Edit Jenis Pekerjaan')

@section('content')
    <!-- Page-Title -->
    <div class="row">
        <div class="col-sm-12">
            <h4 class="pull-left page-title">KELOLA DATA JENIS PEKERJAAN</h4>
            <ol class="breadcrumb pull-right">
                <li><a href="#">Data Master</a></li>
                <li><a href="#">Forms</a></li>
                <li class="active">Pengelolaan Data Jenis Pekerjaan</li>
            </ol>
        </div>
    </div>


    <div class="row">
        <!-- Basic example -->
        <div class="col-md-6">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Pengelolaan Data Jenis Pekerjaan</h3>
                </div>
                <div class="panel-body">
                    <form role="form" action="{{ route('jenis-pekerjaan.update', $data->data->id) }}" method="POST">
                        @method('PUT')
                        @csrf
                        <div class="form-group @error('jenis_pekerjaan') has-error @enderror">
                            <label for="jenis_pekerjaan">Jenis Pekerjaan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="jenis_pekerjaan" name="jenis_pekerjaan"
                                placeholder="Jenis Pekerjaan"
                                value="{{ old('jenis_pekerjaan', $data->data->jenis_pekerjaan) }}">
                            @error('jenis_pekerjaan')
                                {{ $message }}
                            @enderror
                        </div>
                        <div class="row">
                            <button type="submit" class="btn btn-primary waves-effect waves-light">Simpan</button>
                            <a href="{{ route('jenis-pekerjaan.index') }}"
                                class="btn btn-inverse waves-effect waves-light">Kembali</a>
                        </div>
                    </form>
                </div><!-- panel-body -->
            </div> <!-- panel -->
        </div> <!-- col-->

    </div> <!-- End row -->
@endsection
