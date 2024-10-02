@extends('layouts.app')

@section('title', 'Buat Jenis Dokumen')

@section('content')
    <!-- Page-Title -->
    <div class="row">
        <div class="col-sm-12">
            <h4 class="pull-left page-title">KELOLA DATA JENIS DOKUMEN</h4>
            <ol class="breadcrumb pull-right">
                <li><a href="#">Data Master</a></li>
                <li><a href="#">Forms</a></li>
                <li class="active">Pengelolaan Data Jenis Dokumen</li>
            </ol>
        </div>
    </div>


    <div class="row">
        <!-- Basic example -->
        <div class="col-md-6">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Pengelolaan Data Jenis Dokumen</h3>
                </div>
                <div class="panel-body">
                    <form role="form" action="{{ route('jenis-dokumen.store') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="form-group @error('tahapan_pengajuan_kegiatan_id') has-error @enderror">
                            <label for="tahapan_pengajuan_kegiatan_id">Tahapan Pengajuan Kegiatan <span
                                    class="text-danger">*</span></label>
                            <select class="form-control" required id="tahapan_pengajuan_kegiatan_id"
                                name="tahapan_pengajuan_kegiatan_id">
                                <option class='form-control' value=''>- Pilih Data -</option>
                                @isset($tahapanPengajuanKegiatan)
                                    @foreach ($tahapanPengajuanKegiatan as $item)
                                        @if (old('tahapan_pengajuan_kegiatan_id') == $item['id'])
                                            <option class='form-control' value="{{ $item['id'] }}" selected>
                                                {{ $item['deskripsi_kegiatan'] }}
                                            </option>
                                        @else
                                            <option class='form-control' value="{{ $item['id'] }}">
                                                {{ $item['deskripsi_kegiatan'] }}
                                            </option>
                                        @endif
                                    @endforeach
                                @endisset
                            </select>
                            @error('tahapan_pengajuan_kegiatan_id')
                                <span class="error">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                        <div class="form-group @error('jenis_dokumen') has-error @enderror">
                            <label for="jenis_dokumen">Jenis Dokumen <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="jenis_dokumen" name="jenis_dokumen"
                                placeholder="Jenis dokumen" value="{{ old('jenis_dokumen') }}">
                            @error('jenis_dokumen')
                                {{ $message }}
                            @enderror
                        </div>
                        <div class="form-group @error('dokumen') has-error @enderror">
                            <label for="dokumen">Dokumen</label>
                            <input type="file" class="form-control" id="dokumen" name="dokumen">
                            @error('dokumen')
                                {{ $message }}
                            @enderror
                        </div>
                        <div class="row">
                            <button type="submit" class="btn btn-primary waves-effect waves-light">Simpan</button>
                            <a href="{{ route('jenis-dokumen.index') }}"
                                class="btn btn-inverse waves-effect waves-light">Kembali</a>
                        </div>
                    </form>
                </div><!-- panel-body -->
            </div> <!-- panel -->
        </div> <!-- col-->

    </div> <!-- End row -->
@endsection
