@extends('layouts.app')

@section('title', 'Buat Tematik Kegiatan')

@section('content')
<!-- Page-Title -->
<div class="row">
    <div class="col-sm-12">
        <h4 class="pull-left page-title">KELOLA DATA TEMATIK KEGIATAN</h4>
        <ol class="breadcrumb pull-right">
            <li><a href="#">Data Master</a></li>
            <li><a href="#">Forms</a></li>
            <li class="active">Pengelolaan Data Tematik Kegiatan</li>
        </ol>
    </div>
</div>


<div class="row">
    <!-- Basic example -->
    <div class="col-md-6">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">Pengelolaan Data Tematik Kegiatan</h3>
            </div>
            <div class="panel-body">
                <form role="form" action="{{ route('sub-tematik-kegiatan.store') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="form-group @error('sub_tematik_kegiatan') has-error @enderror">
                        <label for="sub_tematik_kegiatan">Sub Tematik Kegiatan <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="sub_tematik_kegiatan" name="sub_tematik_kegiatan"
                            value="{{ old('sub_tematik_kegiatan') }}" placeholder="Jenis Kegiatan">
                        @error('sub_tematik_kegiatan')
                        {{ $message }}
                        @enderror
                    </div>
                    <div class="form-group @error('short_id') has-error @enderror">
                        <label for="short_id">Nomor Urut <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="short_id" name="short_id" min="1"
                            placeholder="Jenis Kegiatan" value="{{ old('short_id') }}">
                        @error('short_id')
                        {{ $message }}
                        @enderror
                    </div>
                    <div class="form-group @error('code_id') has-error @enderror">
                        <label for="code_id">Code ID <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="code_id" name="code_id" min="1"
                            placeholder="Jenis Kegiatan" value="{{ old('code_id') }}">
                        @error('code_id')
                        {{ $message }}
                        @enderror
                    </div>
                    <div class="form-group @error('deskripsi_tematik') has-error @enderror">
                        <label for="deskripsi_tematik">Deskripsi Tematik <span class="text-danger">*</span></label>
                        <textarea name="deskripsi_tematik" id="deskripsi_tematik" cols="30" rows="10"
                            class="form-control">{{ old('deskripsi_tematik') }}</textarea>
                        @error('deskripsi_tematik')
                        {{ $message }}
                        @enderror
                    </div>
                    <div class="form-group @error('fileImage') has-error @enderror">
                        <label for="fileImage">Gambar <span class="text-danger">*</span></label>
                        <input type="file" class="form-control" id="fileImage" name="fileImage">
                        @error('fileImage')
                        {{ $message }}
                        @enderror
                    </div>
                    <div class="row">
                        <button type="submit" class="btn btn-primary waves-effect waves-light">Simpan</button>
                        <a href="{{ route('sub-tematik-kegiatan.index') }}"
                            class="btn btn-inverse waves-effect waves-light">Kembali</a>
                    </div>
                </form>
            </div><!-- panel-body -->
        </div> <!-- panel -->
    </div> <!-- col-->

</div> <!-- End row -->
@endsection