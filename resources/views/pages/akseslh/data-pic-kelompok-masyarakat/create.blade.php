@extends('layouts.app')

@section('title', 'Rekam Data PIC Kelompok Masyarakat')

@section('script')
    <script>
        jQuery("#provinsi_pic").select2({
            width: "100%",
        });
        jQuery("#kabupaten_pic").select2({
            width: "100%",
        });
        jQuery("#kecamatan_pic").select2({
            width: "100%",
        });
        jQuery("#kelurahan_pic").select2({
            width: "100%",
        });
    </script>
    <script src="{{ asset('app/build/pic_kelompok_masyarakat.js') }}" type="text/javascript"></script>
@endsection

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
                    <input type="hidden" name="app_url" id="app_url" value="{{ env('APP_URL') }}">
                </div>
                <div class="panel-body">
                    <form role="form" action="{{ route('pic-kelompok-masyarakat.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="form-group @error('kelompok_masyarakat_id') has-error @enderror col-md-4">
                                <label for="kelompok_masyarakat_id">Kelompok Masyarakat <span
                                        class="text-danger">*</span></label>
                                <select class="form-control" required id="kelompok_masyarakat_id"
                                    name="kelompok_masyarakat_id" required>
                                    <option class='form-control' value=''>- Pilih Data -</option>
                                    @isset($kelompokMasyarakat)
                                        @foreach ($kelompokMasyarakat as $item)
                                            @if (old('kelompok_masyarakat_id') == $item['id'])
                                                <option class='form-control' value="{{ $item['id'] }}" selected>
                                                    {{ $item['kelompok_masyarakat'] }}</option>
                                            @else
                                                <option class='form-control' value="{{ $item['id'] }}">
                                                    {{ $item['kelompok_masyarakat'] }}</option>
                                            @endif
                                        @endforeach
                                    @endisset
                                </select>
                                @error('kelompok_masyarakat_id')
                                    {{ $message }}
                                @enderror
                            </div>
                            <div class="form-group @error('nama_pic') has-error @enderror col-md-4">
                                <label for="nama_pic">Nama Lengkap PIC<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nama_pic" name="nama_pic"
                                    placeholder="Nama Lengkap Tanpa Gelar" value="{{ old('nama_pic') }}">
                                @error('nama_pic')
                                    {{ $message }}
                                @enderror
                            </div>
                            <div class=" form-group @error('email_pic') has-error @enderror col-md-4">
                                <label for="email_pic">Alamat E-Mail PIC </label>
                                <input type="email" class="form-control" id="email_pic" name="email_pic"
                                    placeholder="Alamat E-Mail" value="{{ old('email_pic') }}">
                                @error('email_pic')
                                    {{ $message }}
                                @enderror
                            </div>
                            <div class="form-group @error('jenis_identitas_pic') has-error @enderror col-md-4">
                                <label for="jenis_identitas_pic">Jenis Identitas PIC<span
                                        class="text-danger">*</span></label>
                                <select class="form-control" required id="jenis_identitas_pic" name="jenis_identitas_pic"
                                    required>
                                    <option class='form-control' value=''>- Pilih Data -</option>
                                    <option class='form-control' value='KTP'
                                        {{ old('jenis_identitas_pic') == 'KTP' ? 'selected' : '' }}>
                                        KTP</option>
                                    <option class='form-control' value='SIM'
                                        {{ old('jenis_identitas_pic') == 'SIM' ? 'selected' : '' }}>
                                        SIM</option>
                                    <option class='form-control' value='Kartu Mahasiswa'
                                        {{ old('jenis_identitas_pic') == 'Kartu Mahasiswa' ? 'selected' : '' }}>Kartu
                                        Mahasiswa</option>
                                </select>
                                @error('jenis_identitas_pic')
                                    {{ $message }}
                                @enderror
                            </div>
                            <div class="form-group @error('nomor_identitas_pic') has-error @enderror col-md-4">
                                <label for="nomor_identitas_pic">Nomor Identitas PIC<span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nomor_identitas_pic"
                                    name="nomor_identitas_pic" placeholder="Nomor Identitas"
                                    value="{{ old('nomor_identitas_pic') }}">
                                @error('nomor_identitas_pic')
                                    {{ $message }}
                                @enderror
                            </div>
                            <div class=" form-group @error('nohp_pic') has-error @enderror col-md-4">
                                <label for="nohp_pic">Nomor HP PIC<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nohp_pic" name="nohp_pic"
                                    placeholder="Contoh: 08123234345" value="{{ old('nohp_pic') }}">
                                @error('nohp_pic')
                                    {{ $message }}
                                @enderror
                            </div>
                            <div class=" form-group @error('alamat_pic') has-error @enderror col-md-12">
                                <label for="alamat_pic">Alamat PIC <span class="text-danger">*</span></label>
                                <textarea name="alamat_pic" id="alamat_pic" cols="30" rows="10" class="form-control">
                                {{ old('alamat_pic') }}
                            </textarea>
                                @error('alamat_pic')
                                    {{ $message }}
                                @enderror
                            </div>
                            <div class=" form-group @error('provinsi_pic') has-error @enderror col-md-3">
                                <label for="provinsi_pic">Provinsi PIC <span class="text-danger">*</span></label>
                                <select class="" id="provinsi_pic" name="provinsi_pic" required
                                    onchange="getKotaKabupaten()">
                                    <option class='form-control' value=''>- Pilih Data -</option>
                                    @isset($provinsi)
                                        @foreach ($provinsi as $item)
                                            @if (old('provinsi_pic') == $item['id'])
                                                <option class='form-control' value="{{ $item['id'] }}" selected>
                                                    {{ $item['name'] }}</option>
                                            @else
                                                <option class='form-control' value="{{ $item['id'] }}">{{ $item['name'] }}
                                                </option>
                                            @endif
                                        @endforeach
                                    @endisset
                                </select>
                                @error('provinsi_pic')
                                    {{ $message }}
                                @enderror
                            </div>
                            <div class=" form-group @error('kabupaten_pic') has-error @enderror col-md-3">
                                <label for="kabupaten_pic">Kabupaten PIC <span class="text-danger">*</span></label>
                                <input type="hidden" name="kabupaten_pic_old" id="kabupaten_pic_old"
                                    value="{{ old('kabupaten_pic') }}">
                                <select class="" id="kabupaten_pic" name="kabupaten_pic" required
                                    onchange="getKecamatan()">
                                    <option class='form-control' value=''>- Pilih Data -</option>
                                </select>
                                @error('kabupaten_pic')
                                    {{ $message }}
                                @enderror
                            </div>
                            <div class=" form-group @error('kecamatan_pic') has-error @enderror col-md-3">
                                <label for="kecamatan_pic">Kecamatan PIC <span class="text-danger">*</span></label>
                                <select class="" id="kecamatan_pic" name="kecamatan_pic" required
                                    onchange="getKelurahan()">
                                    <option class='form-control' value=''>- Pilih Data -</option>
                                </select>
                                @error('kecamatan_pic')
                                    {{ $message }}
                                @enderror
                            </div>
                            <div class=" form-group @error('kelurahan_pic') has-error @enderror col-md-3">
                                <label for="kelurahan_pic">Kelurahan PIC <span class="text-danger">*</span></label>
                                <select class="" id="kelurahan_pic" name="kelurahan_pic" required>
                                    <option class='form-control' value=''>- Pilih Data -</option>
                                </select>
                                @error('kelurahan_pic')
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
