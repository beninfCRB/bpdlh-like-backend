@extends('layouts.app')

@section('title', 'Ubah Data Indikator Laporan')

@section('content')
    <!-- Page-Title -->
    <div class="row">
        <div class="col-sm-12">
            <h4 class="pull-left page-title">KELOLA DATA INDIKATOR LAPORAN</h4>
            <ol class="breadcrumb pull-right">
                <li><a href="#">Data Master</a></li>
                <li><a href="#">Forms</a></li>
                <li class="active">Pengelolaan Data Indikator Laporan</li>
            </ol>
        </div>
    </div>


    <div class="row">
        <!-- Basic example -->
        <div class="col-md-6">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Pengelolaan Data Indikator Laporan</h3>
                </div>
                <div class="panel-body">
                    <form role="form" action="{{ route('master-data-indikator-laporan.update', $data->id) }}"
                        method="POST">
                        @method('PUT')
                        @csrf
                        <div class="form-group @error('jenis_kegiatan_id') has-error @enderror">
                            <label for="jenis_kegiatan_id">Jenis Kegiatan <span class="text-danger">*</span></label>
                            <select class="form-control" required id="jenis_kegiatan_id" name="jenis_kegiatan_id">
                                <option class='form-control' value=''>- Pilih Data -</option>
                                @isset($jenisKegiatan)
                                    @foreach ($jenisKegiatan as $item)
                                        @if (old('jenis_kegiatan_id', $data->jenis_kegiatan_id) == $item['id'])
                                            <option class='form-control' value="{{ $item['id'] }}" selected>
                                                {{ $item['jenis_kegiatan'] }}
                                            </option>
                                        @else
                                            <option class='form-control' value="{{ $item['id'] }}">
                                                {{ $item['jenis_kegiatan'] }}
                                            </option>
                                        @endif
                                    @endforeach
                                @endisset
                            </select>
                            @error('jenis_kegiatan_id')
                                <span class="error">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                        <div class="form-group @error('sub_tematik_kegiatan_id') has-error @enderror">
                            <label for="sub_tematik_kegiatan_id">Sub Tematik Kegiatan <span
                                    class="text-danger">*</span></label>
                            <select class="form-control" required id="sub_tematik_kegiatan_id"
                                name="sub_tematik_kegiatan_id">
                                <option class='form-control' value=''>- Pilih Data -</option>
                                @isset($subTematikKegiatan)
                                    @foreach ($subTematikKegiatan as $item)
                                        @if (old('sub_tematik_kegiatan_id', $data->sub_tematik_kegiatan_id) == $item['id'])
                                            <option class='form-control' value="{{ $item['id'] }}" selected>
                                                {{ $item['sub_tematik_kegiatan'] }}
                                            </option>
                                        @else
                                            <option class='form-control' value="{{ $item['id'] }}">
                                                {{ $item['sub_tematik_kegiatan'] }}
                                            </option>
                                        @endif
                                    @endforeach
                                @endisset
                            </select>
                            @error('sub_tematik_kegiatan_id')
                                <span class="error">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                        <div class="form-group @error('nama_indikator') has-error @enderror">
                            <label for="nama_indikator">Nama Indikator <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nama_indikator" name="nama_indikator"
                                placeholder="Nama Indikator" value="{{ old('nama_indikator', $data->nama_indikator) }}">
                            <span class="help-block">
                                <small>
                                    E.g: Perempuan, Laki-laki, Sampah, Pohon
                                </small>
                            </span>
                            @error('nama_indikator')
                                <span class="error">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                        <div class="form-group @error('satuan') has-error @enderror">
                            <label for="satuan">Satuan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="satuan" name="satuan" placeholder="Satuan"
                                value="{{ old('satuan', $data->satuan) }}">
                            <span class="help-block">
                                <small>
                                    E.g: hektar, M2, orang, kg
                                </small>
                            </span>
                            @error('satuan')
                                <span class="error">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                        <div class="form-group @error('tipe_data') has-error @enderror">
                            <label for="tipe_data">Tipe Data <span class="text-danger">*</span></label>
                            <select name="tipe_data" id="tipe_data" class="form-control">
                                <option value="text" @if (old('tipe_data', $data->tipe_data) == 'text') required @endif>text</option>
                                <option value="numeric" @if (old('tipe_data') == 'numeric') required @endif>numeric</option>
                            </select>
                            @error('tipe_data')
                                <span class="error">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                        <div class="row">
                            <button type="submit" class="btn btn-primary waves-effect waves-light">Simpan</button>
                            <a href="{{ route('master-data-indikator-laporan.index') }}"
                                class="btn btn-inverse waves-effect waves-light">Kembali</a>
                        </div>
                    </form>
                </div><!-- panel-body -->
            </div> <!-- panel -->
        </div> <!-- col-->

    </div> <!-- End row -->
@endsection
