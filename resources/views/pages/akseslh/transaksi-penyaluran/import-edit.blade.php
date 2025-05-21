@extends('layouts.app')

@section('title', 'Edit Transaksi Penyaluran')

@section('script')
    <script src="{{ asset('app/build/transaksi_penyaluran.js') }}" type="text/javascript"></script>
@endsection

@section('content')
    <!-- Page-Title -->
    <div class="row">
        <div class="col-sm-12">
            <h4 class="pull-left page-title">KELOLA DATA TRANSAKSI PENYALURAN</h4>
            <ol class="breadcrumb pull-right">
                <li><a href="#">Penyaluran Dana</a></li>
                <li><a href="#">Import Penyaluran</a></li>
                <li class="active">Pengelolaan Data Transaksi Penyaluran</li>
            </ol>
        </div>
    </div>


    <div class="row">
        <!-- Basic example -->
        <div class="col-md-6">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Pengelolaan Data Transaksi Penyaluran</h3>
                </div>
                <div class="panel-body">

                    <form role="form" action="{{ route('transaksi-penyaluran.import-update', $data->id) }}"
                        method="POST" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="form-group @error('master_data_bank_id') has-error @enderror">
                            <label for="master_data_bank_id">Bank <span class="text-danger">*</span></label>
                            <select name="master_data_bank_id" id="master_data_bank_id" class="form-control">
                                <option value="">-- Pilih Bank --</option>
                                @isset($master_data_bank)
                                    @foreach ($master_data_bank as $bank)
                                        <option value="{{ $bank->id }}"
                                            {{ old('master_data_bank_id', $data->master_data_bank_id) == $bank->id ? 'selected' : '' }}>
                                            {{ $bank->nama_bank }}</option>
                                    @endforeach
                                @endisset
                            </select>
                            @error('master_data_bank_id')
                                {{ $message }}
                            @enderror
                        </div>

                        <div class="form-group @error('nomor_pengajuan') has-error @enderror">
                            <label for="nomor_pengajuan">Nomor Pengajuan <span class="text-danger">*</span></label>
                            <input type="text" name="nomor_pengajuan" id="nomor_pengajuan" class="form-control"
                                value="{{ $data->pengajuan_kegiatan->nomor_pengajuan }}" readonly>
                            <input type="hidden" name="pengajuan_kegiatan_id" id="pengajuan_kegiatan_id"
                                class="form-control" value="{{ $data->pengajuan_kegiatan_id }}" readonly>
                            @error('nomor_pengajuan')
                                {{ $message }}
                            @enderror
                        </div>

                        <div class="form-group @error('nomor_rekening') has-error @enderror">
                            <label for="nomor_rekening">Nomor Rekening <span class="text-danger">*</span></label>
                            <input type="text" name="nomor_rekening" id="nomor_rekening" class="form-control"
                                value="{{ old('nomor_rekening', $data->nomor_rekening) }}">
                            @error('nomor_rekening')
                                {{ $message }}
                            @enderror
                        </div>

                        <div class="form-group @error('nama_pemilik_rekening') has-error @enderror">
                            <label for="nama_pemilik_rekening">Nama Pemilik Rekening <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="nama_pemilik_rekening" id="nama_pemilik_rekening"
                                class="form-control"
                                value="{{ old('nama_pemilik_rekening', $data->nama_pemilik_rekening) }}">
                            @error('nama_pemilik_rekening')
                                {{ $message }}
                            @enderror
                        </div>

                        <div class="form-group @error('nilai_penyaluran') has-error @enderror">
                            <label for="nilai_penyaluran">Nilai Penyaluran <span class="text-danger">*</span></label>
                            <input type="text" name="nilai_penyaluran" id="nilai_penyaluran" class="form-control"
                                value="{{ number_format($data->nilai_penyaluran) }}" oninput="formatMoney(this)">
                            @error('nilai_penyaluran')
                                {{ $message }}
                            @enderror
                        </div>

                        <div class="form-group @error('tanggal_penyaluran') has-error @enderror">
                            <label for="tanggal_penyaluran">Tanggal Penyaluran <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_penyaluran" id="tanggal_penyaluran" class="form-control"
                                value="{{ old('tanggal_penyaluran', $data->tanggal_penyaluran) }}">
                            @error('tanggal_penyaluran')
                                {{ $message }}
                            @enderror
                        </div>

                        <div class="form-group @error('surat_keterangan') has-error @enderror">
                            <label for="surat_keterangan">Surat Keterangan <span class="text-danger">*</span></label>
                            <input type="file" name="surat_keterangan" id="surat_keterangan" class="form-control">
                            @error('surat_keterangan')
                                {{ $message }}
                            @enderror
                        </div>

                        <div class="row">
                            <button type="submit" class="btn btn-primary waves-effect waves-light">Simpan</button>
                            <a href="{{ route('transaksi-penyaluran.import-view') }}"
                                class="btn btn-inverse waves-effect waves-light">Kembali</a>
                        </div>
                    </form>
                </div><!-- panel-body -->
            </div> <!-- panel -->
        </div> <!-- col-->
    </div> <!-- End row -->
@endsection
