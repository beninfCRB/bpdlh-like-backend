@extends('layouts.app')

@section('title', 'Buat Log Jadwal Pembukaan')

@section('script')
    <script src="{{ asset('app/build/log_jadwal_pembukaan.js') }}" type="text/javascript"></script>
@endsection

@section('content')
    <!-- Page-Title -->
    <div class="row">
        <div class="col-sm-12">
            <h4 class="pull-left page-title">KELOLA DATA LOG JADWAL PEMBUKAAN</h4>
            <ol class="breadcrumb pull-right">
                <li><a href="#">Data Master</a></li>
                <li><a href="#">Forms</a></li>
                <li class="active">Pengelolaan Data Log Jadwal Pembukaan</li>
            </ol>
        </div>
    </div>


    <div class="row">
        <!-- Basic example -->
        <div class="col-md-6">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Pengelolaan Data Jenis Kelompok Masyarakat</h3>
                </div>
                <div class="panel-body">
                    <form role="form" action="{{ route('log-jadwal-pembukaan.store') }}" method="POST">
                        @csrf
                        <div class="form-group @error('tanggal_awal') has-error @enderror">
                            <label for="tanggal_awal">Tanggal Awal <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="tanggal_awal" name="tanggal_awal"
                                value="{{ old('tanggal_awal') }}">
                            @error('tanggal_awal')
                                <span class="error">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                        <div class="form-group @error('jam_awal') has-error @enderror">
                            <label for="jam_awal">Jam Awal <span class="text-danger">*</span></label>
                            <input type="time" class="form-control" id="jam_awal" name="jam_awal"
                                value="{{ old('jam_awal') }}">
                            @error('jam_awal')
                                <span class="error">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                        <div class="form-group @error('tanggal_akhir') has-error @enderror">
                            <label for="tanggal_akhir">Tanggal Akhir <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="tanggal_akhir" name="tanggal_akhir"
                                value="{{ old('tanggal_akhir') }}">
                            @error('tanggal_akhir')
                                <span class="error">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                        <div class="form-group @error('jam_akhir') has-error @enderror">
                            <label for="jam_akhir">Jam Akhir <span class="text-danger">*</span></label>
                            <input type="time" class="form-control" id="jam_akhir" name="jam_akhir"
                                value="{{ old('jam_akhir') }}">
                            @error('jam_akhir')
                                <span class="error">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                        <div class="form-group @error('batas_pengajuan') has-error @enderror">
                            <label for="batas_pengajuan">Batas Pengajuan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="batas_pengajuan" name="batas_pengajuan"
                                value="{{ old('batas_pengajuan') }}" oninput="formatMoney(this)">
                            @error('batas_pengajuan')
                                <span class="error">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                        <div class="row">
                            <button type="submit" class="btn btn-primary waves-effect waves-light">Simpan</button>
                            <a href="{{ route('log-jadwal-pembukaan.index') }}"
                                class="btn btn-inverse waves-effect waves-light">Kembali</a>
                        </div>
                    </form>
                </div><!-- panel-body -->
            </div> <!-- panel -->
        </div> <!-- col-->

    </div> <!-- End row -->
@endsection
