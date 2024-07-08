@extends('layouts.app')

@section('title', 'Edit Data PIC Kelompok Masyarakat')

@section('content')
<!-- Page-Title -->
<div class="row">
    <div class="col-sm-12">
        <h4 class="pull-left page-title">KELOLA DATA PIC KELOMPOK MASYARAKAT</h4>
        <ol class="breadcrumb pull-right">
            <li><a href="#">Data Master</a></li>
            <li><a href="#">Forms</a></li>
            <li class="active">Pengelolaan Data PIC Kelompok Masyarakat</li>
        </ol>
    </div>
</div>


<div class="row">
    <!-- Basic example -->
    <div class="col-md-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">Pengelolaan Data PIC Kelompok Masyaakat</h3>
            </div>
            <div class="panel-body">
                <form role="form" action="{{ route('pic-kelompok-masyarakat.update', $data->id) }}" method="POST">
                    @method('PUT')
                    @csrf
                    <div class="row">
                        <div class="form-group @error('akseslh_kelompok_masyarakat_id') has-error @enderror col-md-5">
                            <label for="akseslh_kelompok_masyarakat_id">Kelompok Masyarakat <span
                                    class="text-danger">*</span></label>
                            <select class="form-control" required id="akseslh_kelompok_masyarakat_id"
                                name="akseslh_kelompok_masyarakat_id" required>
                                <option class='form-control' value=''>- Pilih Data -</option>
                                @isset($kelompokMasyarakat)
                                @foreach ($kelompokMasyarakat as $item)
                                @if (old('akseslh_kelompok_masyarakat_id', $data->akseslh_kelompok_masyarakat_id) ==
                                $item['id'])
                                <option class='form-control' value="{{ $item['id'] }}" selected>{{
                                    $item['kelompok_masyarakat'] }}</option>
                                @else
                                <option class='form-control' value="{{ $item['id'] }}">{{ $item['kelompok_masyarakat']
                                    }}</option>
                                @endif
                                @endforeach
                                @endisset
                            </select>
                            @error('akseslh_kelompok_masyarakat_id')
                            {{ $message }}
                            @enderror
                        </div>
                        <div class="form-group @error('nama_user_eksternal') has-error @enderror col-md-7">
                            <label for="nama_user_eksternal">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nama_user_eksternal" name="nama_user_eksternal"
                                placeholder="Nama Lengkap"
                                value="{{ old('nama_user_eksternal', $data->nama_user_eksternal) }}">
                            @error('nama_user_eksternal')
                            {{ $message }}
                            @enderror
                        </div>
                        <div class="form-group @error('jenis_identitas_user_eksternal') has-error @enderror col-md-4">
                            <label for="jenis_identitas_user_eksternal">Jenis Identitas <span
                                    class="text-danger">*</span></label>
                            <select class="form-control" required id="jenis_identitas_user_eksternal"
                                name="jenis_identitas_user_eksternal" required>
                                <option class='form-control' value=''>- Pilih Data -</option>
                                <option class='form-control' value='KTP' {{ old('jenis_identitas_user_eksternal',
                                    $data->jenis_identitas_user_eksternal)=='KTP'
                                    ? 'selected' : '' }}>KTP</option>
                                <option class='form-control' value='SIM' {{ old('jenis_identitas_user_eksternal',
                                    $data->jenis_identitas_user_eksternal)=='SIM'
                                    ? 'selected' : '' }}>SIM</option>
                                <option class='form-control' value='Kartu Mahasiswa' {{
                                    old('jenis_identitas_user_eksternal', $data->jenis_identitas_user_eksternal)=='Kartu
                                    Mahasiswa' ? 'selected' : '' }}>Kartu
                                    Mahasiswa</option>
                            </select>
                            @error('jenis_identitas_user_eksternal')
                            {{ $message }}
                            @enderror
                        </div>
                        <div class="form-group @error('nomor_identitas_user_eksternal') has-error @enderror col-md-4">
                            <label for="nomor_identitas_user_eksternal">Nomor Identitas <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nomor_identitas_user_eksternal"
                                name="nomor_identitas_user_eksternal" placeholder="Nomor Identitas"
                                value="{{ old('nomor_identitas_user_eksternal', $data->nomor_identitas_user_eksternal) }}">
                            @error('nomor_identitas_user_eksternal')
                            {{ $message }}
                            @enderror
                        </div>
                        <div class=" form-group @error('nomor_hp_user_eksternal') has-error @enderror col-md-4">
                            <label for="nomor_hp_user_eksternal">Nomor HP <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nomor_hp_user_eksternal"
                                name="nomor_hp_user_eksternal" placeholder="Contoh: 08123234345"
                                value="{{ old('nomor_hp_user_eksternal', $data->nomor_hp_user_eksternal) }}">
                            @error('nomor_hp_user_eksternal')
                            {{ $message }}
                            @enderror
                        </div>
                        <div class=" form-group @error('email_user_eksternal') has-error @enderror col-md-4">
                            <label for="email_user_eksternal">Alamat E-Mail <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="email_user_eksternal"
                                name="email_user_eksternal" placeholder="Alamat E-Mail"
                                value="{{ old('email_user_eksternal', $data->email_user_eksternal) }}">
                            @error('email_user_eksternal')
                            {{ $message }}
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary waves-effect waves-light">Simpan</button>
                            <a href="{{ route('pic-kelompok-masyarakat.index') }}"
                                class="btn btn-inverse waves-effect waves-light">Kembali</a>
                        </div>
                    </div>
                </form>
            </div><!-- panel-body -->
        </div> <!-- panel -->
    </div> <!-- col-->

</div> <!-- End row -->
@endsection