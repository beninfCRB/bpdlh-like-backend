@extends('layouts.app')

@section('title', 'Buat Data Paket Kegiatan')

@section('content')
<!-- Page-Title -->
<div class="row">
    <div class="col-sm-12">
        <h4 class="pull-left page-title">KELOLA DATA PAKET KEGIATAN</h4>
        <ol class="breadcrumb pull-right">
            <li><a href="#">Data Master</a></li>
            <li><a href="#">Forms</a></li>
            <li class="active">Pengelolaan Data Paket Kegiatan</li>
        </ol>
    </div>
</div>


<div class="row">
    <!-- Basic example -->
    <div class="col-md-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">Pengelolaan Data Paket Kegiatan</h3>
            </div>
            <div class="panel-body">
                <form role="form" action="{{ route('paket-kegiatan.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="form-group @error('akseslh_jenis_kegiatan_id') has-error @enderror col-md-4">
                            <label for="akseslh_jenis_kegiatan_id">Jenis Kegiatan <span
                                    class="text-danger">*</span></label>
                            <select class="form-control" required id="akseslh_jenis_kegiatan_id"
                                name="akseslh_jenis_kegiatan_id" required>
                                <option class='form-control' value=''>- Pilih Data -</option>
                                @isset($jenisKegiatan)
                                @foreach ($jenisKegiatan as $item)
                                @if (old('akseslh_jenis_kegiatan_id') == $item['id'])
                                <option class='form-control' value="{{ $item['id'] }}" selected>{{
                                    $item['jenis_kegiatan'] }}
                                </option>
                                @else
                                <option class='form-control' value="{{ $item['id'] }}">{{
                                    $item['jenis_kegiatan'] }}
                                </option>
                                @endif
                                @endforeach
                                @endisset
                            </select>
                            @error('akseslh_jenis_kegiatan_id')
                            <span class="error">
                                {{ $message }}
                            </span>
                            @enderror
                        </div>
                        <div class="form-group @error('nama_paket_kegiatan') has-error @enderror col-md-8">
                            <label for="nama_paket_kegiatan">Nama Paket Kegiatan <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nama_paket_kegiatan" name="nama_paket_kegiatan"
                                placeholder="Nama Paket Kegiatan" value="{{ old('nama_paket_kegiatan') }}">
                            @error('nama_paket_kegiatan')
                            <span class="error">
                                {{ $message }}
                            </span>
                            @enderror
                        </div>
                        <div class="form-group @error('deskripsi_paket_kegiatan') has-error @enderror col-md-12">
                            <label for="deskripsi_paket_kegiatan">Deskripsi Paket Kegiatan <span
                                    class="text-danger">*</span></label>
                            <textarea class="form-control" id="deskripsi_paket_kegiatan" name="deskripsi_paket_kegiatan"
                                rows="3"
                                placeholder="Deskripsi Paket Kegiatan">{{ old('deskripsi_paket_kegiatan') }}</textarea>
                            @error('deskripsi_paket_kegiatan')
                            <span class="error">
                                {{ $message }}
                            </span>
                            @enderror
                        </div>
                        <div class="form-group @error('quota_paket_kegiatan') has-error @enderror col-md-4">
                            <label for="quota_paket_kegiatan">Quota Paket Kegiatan <span
                                    class="text-danger">*</span></label>
                            <input type="number" min=0 class="form-control" id="quota_paket_kegiatan"
                                name="quota_paket_kegiatan" value="{{ old('quota_paket_kegiatan') }}">
                            @error('quota_paket_kegiatan')
                            <span class="error">
                                {{ $message }}
                            </span>
                            @enderror
                        </div>
                        <div class="form-group @error('pagu_paket_kegiatan') has-error @enderror col-md-4">
                            <label for="pagu_paket_kegiatan">Pagu Paket Kegiatan (Rp) <span
                                    class="text-danger">*</span></label>
                            <input type="number" min=0 step="0.00" class="form-control" id="pagu_paket_kegiatan"
                                name="pagu_paket_kegiatan" value="{{ old('pagu_paket_kegiatan') }}">
                            @error('pagu_paket_kegiatan')
                            <span class="error">
                                {{ $message }}
                            </span>
                            @enderror
                        </div>
                        <div class="form-group @error('tahap_pencairan_paket_kegiatan') has-error @enderror col-md-4">
                            <label for="tahap_pencairan_paket_kegiatan">Jml Tahap Pencairan <span
                                    class="text-danger">*</span></label>
                            <input type="number" min=0 class="form-control" id="tahap_pencairan_paket_kegiatan"
                                name="tahap_pencairan_paket_kegiatan"
                                value="{{ old('tahap_pencairan_paket_kegiatan') }}">
                            @error('tahap_pencairan_paket_kegiatan')
                            <span class="error">
                                {{ $message }}
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <button type="submit" class="btn btn-primary waves-effect waves-light">Simpan</button>
                        <a href="{{ route('paket-kegiatan.index') }}"
                            class="btn btn-inverse waves-effect waves-light">Kembali</a>
                    </div>
                </form>
            </div><!-- panel-body -->
        </div> <!-- panel -->
    </div> <!-- col-->

</div> <!-- End row -->
@endsection