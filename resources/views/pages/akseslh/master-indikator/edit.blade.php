@extends('layouts.app')

@section('title', 'Edit Master Indikator')

@section('content')
    <!-- Page-Title -->
    <div class="row">
        <div class="col-sm-12">
            <h4 class="pull-left page-title">MASTER INDIKATOR</h4>
            <ol class="breadcrumb pull-right">
                <li><a href="#">Data Master</a></li>
                <li><a href="#">Forms</a></li>
                <li class="active">Master Indikator</li>
            </ol>
        </div>
    </div>


    <div class="row">
        <!-- Basic example -->
        <div class="col-md-6">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Master Indikator</h3>
                </div>
                <div class="panel-body">
                    <form role="form" action="{{ route('master-indikator.update', $data->data->id) }}" method="POST">
                        @method('PUT')
                        @csrf
                        <div class="form-group @error('nama_indikator') has-error @enderror">
                            <label for="nama_indikator">Nama Indikator <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nama_indikator" name="nama_indikator"
                                placeholder="Nama Indikator"
                                value="{{ old('nama_indikator', $data->data->nama_indikator) }}">
                            @error('nama_indikator')
                                <span class="error">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>

                        <div class="form-group @error('satuan') has-error @enderror">
                            <label for="satuan">Satuan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" min=0 id="satuan" name="satuan"
                                value="{{ old('satuan', $data->data->satuan) }}">
                            @error('satuan')
                                <span class="error">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>

                        <div class="form-group @error('tipe_data') has-error @enderror">
                            <label for="tipe_data">Tipe Data <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" min=0 id="tipe_data" name="tipe_data"
                                value="{{ old('tipe_data', $data->data->tipe_data) }}">
                            @error('tipe_data')
                                <span class="error">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                        <div class="row">
                            <button type="submit" class="btn btn-primary waves-effect waves-light">Simpan</button>
                            <a href="{{ route('master-indikator.index') }}"
                                class="btn btn-inverse waves-effect waves-light">Kembali</a>
                        </div>
                    </form>
                </div><!-- panel-body -->
            </div> <!-- panel -->
        </div> <!-- col-->

    </div> <!-- End row -->
@endsection
