@extends('layouts.app')

@section('title', 'Edit Jenis Kegiatan')

@section('content')
    <!-- Page-Title -->
    <div class="row">
        <div class="col-sm-12">
            <h4 class="pull-left page-title">KELOLA DATA JENIS KEGIATAN</h4>
            <ol class="breadcrumb pull-right">
                <li><a href="#">Data Master</a></li>
                <li><a href="#">Forms</a></li>
                <li class="active">Pengelolaan Data Jenis Kegiatan</li>
            </ol>
        </div>
    </div>


    <div class="row">
        <!-- Basic example -->
        <div class="col-md-6">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Pengelolaan Data Jenis Kegiatan</h3>
                </div>
                <div class="panel-body">
                    <form role="form" action="{{ route('jenis-kegiatan.update', $data->data->id) }}" method="POST">
                        @method('PUT')
                        @csrf
                        <div class="form-group @error('jenis_kegiatan') has-error @enderror">
                            <label for="jenis_kegiatan">Jenis Kegiatan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="jenis_kegiatan" name="jenis_kegiatan"
                                placeholder="Jenis Kegiatan"
                                value="{{ old('jenis_kegiatan', $data->data->jenis_kegiatan) }}">
                            @error('jenis_kegiatan')
                                {{ $message }}
                            @enderror
                        </div>
                        <div class="form-group @error('durasi_hari_kegiatan') has-error @enderror">
                            <label for="durasi_hari_kegiatan">Durasi Hari Kegiatan <span
                                    class="text-danger">*</span></label>
                            <input type="number" min="1" class="form-control" id="durasi_hari_kegiatan"
                                name="durasi_hari_kegiatan" placeholder="Durasi Hari Kegiatan"
                                value="{{ old('durasi_hari_kegiatan', $data->data->durasi_hari_kegiatan) }}">
                            @error('durasi_hari_kegiatan')
                                {{ $message }}
                            @enderror
                        </div>
                        <div class="form-group @error('durasi_hari_sptjm') has-error @enderror">
                            <label for="durasi_hari_sptjm">Durasi Hari SPTJM <span class="text-danger">*</span></label>
                            <input type="number" min="1" class="form-control" id="durasi_hari_sptjm"
                                name="durasi_hari_sptjm" placeholder="Durasi Hari SPTJM"
                                value="{{ old('durasi_hari_sptjm', $data->data->durasi_hari_sptjm) }}">
                            @error('durasi_hari_sptjm')
                                {{ $message }}
                            @enderror
                        </div>
                        <div class="form-group @error('durasi_hari_laporan_kegiatan') has-error @enderror">
                            <label for="durasi_hari_laporan_kegiatan">Durasi Hari Laporan Kegiatan <span
                                    class="text-danger">*</span></label>
                            <input type="number" min="1" class="form-control" id="durasi_hari_laporan_kegiatan"
                                name="durasi_hari_laporan_kegiatan" placeholder="Durasi Hari Laporan Kegiatan"
                                value="{{ old('durasi_hari_laporan_kegiatan', $data->data->durasi_hari_laporan_kegiatan) }}">
                            @error('durasi_hari_laporan_kegiatan')
                                {{ $message }}
                            @enderror
                        </div>
                        <div class="form-group @error('short_id') has-error @enderror">
                            <label for="short_id">Nomor Urut <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" min=0 id="short_id" name="short_id"
                                value="{{ old('short_id', $data->data->short_id) }}">
                            @error('short_id')
                                <span class="error">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>

                        <div class="form-group @error('code_id') has-error @enderror">
                            <label for="code_id">Nomor Urut <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" min=0 id="code_id" name="code_id"
                                value="{{ old('code_id', $data->data->code_id) }}">
                            @error('code_id')
                                <span class="error">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                        <div class="row">
                            <button type="submit" class="btn btn-primary waves-effect waves-light">Simpan</button>
                            <a href="{{ route('jenis-kegiatan.index') }}"
                                class="btn btn-inverse waves-effect waves-light">Kembali</a>
                        </div>
                    </form>
                </div><!-- panel-body -->
            </div> <!-- panel -->
        </div> <!-- col-->

    </div> <!-- End row -->
@endsection
