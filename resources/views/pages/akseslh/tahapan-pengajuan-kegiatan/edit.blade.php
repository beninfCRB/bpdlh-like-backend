@extends('layouts.app')

@section('title', 'Edit Tahapan Pengajuan Kegiatan')

@section('content')
    <!-- Page-Title -->
    <div class="row">
        <div class="col-sm-12">
            <h4 class="pull-left page-title">TAHAPAN PENGAJUAN KEGIATAN</h4>
            <ol class="breadcrumb pull-right">
                <li><a href="#">Data Master</a></li>
                <li class="active">Tahapan Pengajuan Kegiatan</li>
            </ol>
        </div>
    </div>


    <div class="row">
        <!-- Basic example -->
        <div class="col-md-6">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Pengelolaan Data Tahapan Pengajuan Kegiatan</h3>
                </div>
                <div class="panel-body">
                    <form role="form" action="{{ route('tahapan-pengajuan-kegiatan.update', $data->id) }}"
                        method="POST">
                        @method('PUT')
                        @csrf
                        <div class="form-group @error('deskripsi_kegiatan') has-error @enderror">
                            <label for="deskripsi_kegiatan">Deskripsi Kegiatan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="deskripsi_kegiatan" name="deskripsi_kegiatan"
                                value="{{ old('deskripsi_kegiatan', $data->deskripsi_kegiatan) }}"
                                placeholder="Jenis Kegiatan">
                            @error('deskripsi_kegiatan')
                                {{ $message }}
                            @enderror
                        </div>
                        <div class="form-group @error('sort') has-error @enderror">
                            <label for="sort">Sort/Urutan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="sort" name="sort"
                                value="{{ old('sort', $data->sort) }}" placeholder="Jenis Kegiatan">
                            @error('sort')
                                {{ $message }}
                            @enderror
                        </div>
                        <div class="row">
                            <button type="submit" class="btn btn-primary waves-effect waves-light">Simpan</button>
                            <a href="{{ route('tahapan-pengajuan-kegiatan.index') }}"
                                class="btn btn-inverse waves-effect waves-light">Kembali</a>
                        </div>
                    </form>
                </div><!-- panel-body -->
            </div> <!-- panel -->
        </div> <!-- col-->

    </div> <!-- End row -->
@endsection
