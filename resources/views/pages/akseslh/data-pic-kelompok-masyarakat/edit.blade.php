@extends('layouts.app')

@section('title', 'Edit Data PIC Kelompok Masyarakat')

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
    <div class="row">
        <div class="col-sm-12">
            <div class="bg-picture text-center" style="background-image: url('{{ asset('template/images/big/bg.jpg') }}')">
                <div class="bg-picture-overlay"></div>
                <div class="profile-info-name">
                    @if ($data->foto->where('group', 'foto_selfie')->count() > 0)
                        <img src="{{ env('APP_URL') . '/storage/' . $data->foto->where('group', 'foto_selfie')->first()->file_path }}"
                            class="thumb-lg img-circle img-thumbnail" alt="profile-image" />
                    @else
                        <img src="{{ asset('template/images/avatar-1.jpg') }}" class="thumb-lg img-circle img-thumbnail"
                            alt="profile-image" />
                    @endif
                    <h3 class="text-white">{{ old('nama_pic', $data->nama_pic) }}</h3>
                </div>
            </div>
            <!--/ meta -->
        </div>
    </div>
    <div class="row user-tabs">
        <div class="col-lg-6 col-md-9 col-sm-9">
            <ul class="nav nav-tabs tabs">
                <li class="active tab">
                    <a href="#home-2" data-toggle="tab" aria-expanded="false" class="active">
                        <span class="visible-xs"><i class="fa fa-home"></i></span>
                        <span class="hidden-xs">Data PIC</span>
                    </a>
                </li>
                <li class="tab">
                    <a href="#history-document-2" data-toggle="tab" aria-expanded="false">
                        <span class="visible-xs"><i class="fa fa-cog"></i></span>
                        <span class="hidden-xs">Dokumen Pendukung</span>
                    </a>
                </li>
                <li class="tab">
                    <a href="#history-change-2" data-toggle="tab" aria-expanded="false">
                        <span class="visible-xs"><i class="fa fa-cog"></i></span>
                        <span class="hidden-xs">Riwayat Perubahan</span>
                    </a>
                </li>
                <div class="indicator"></div>
            </ul>
        </div>
        <div class="col-lg-6 col-md-3 col-sm-3 hidden-xs">
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="tab-content profile-tab-content">
                <div class="tab-pane active" id="home-2">
                    <div class="panel panel-default panel-fill">
                        <div class="panel-heading">
                            <h3 class="panel-title">Ubah Data</h3>
                            <input type="hidden" name="app_url" id="app_url" value="{{ env('APP_URL') }}">
                        </div>
                        <div class="panel-body">
                            <form role="form" action="{{ route('pic-kelompok-masyarakat.update', $data->id) }}"
                                method="POST" enctype="multipart/form-data">
                                @method('PUT')
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
                                                    @if (old('kelompok_masyarakat_id', $data->kelompok_masyarakat_id) == $item['id'])
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
                                            placeholder="Nama Lengkap Tanpa Gelar"
                                            value="{{ old('nama_pic', $data->nama_pic) }}">
                                        @error('nama_pic')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                    <div class=" form-group @error('email_pic') has-error @enderror col-md-4">
                                        <label for="email_pic">Alamat E-Mail PIC </label>
                                        <input type="email" class="form-control" id="email_pic" name="email_pic"
                                            placeholder="Alamat E-Mail" value="{{ old('email_pic', $data->email_pic) }}">
                                        @error('email_pic')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                    <div class="form-group @error('jenis_identitas_pic') has-error @enderror col-md-6">
                                        <label for="jenis_identitas_pic">Jenis Identitas PIC<span
                                                class="text-danger">*</span></label>
                                        <select class="form-control" required id="jenis_identitas_pic"
                                            name="jenis_identitas_pic" required>
                                            <option class='form-control' value=''>- Pilih Data -
                                            </option>
                                            <option class='form-control' value='KTP'
                                                {{ old('jenis_identitas_pic', $data->jenis_identitas_pic) == 'KTP' ? 'selected' : '' }}>
                                                KTP
                                            </option>
                                            <option class='form-control' value='SIM'
                                                {{ old('jenis_identitas_pic', $data->jenis_identitas_pic) == 'SIM' ? 'selected' : '' }}>
                                                SIM
                                            </option>
                                            <option class='form-control' value='Kartu Mahasiswa'
                                                {{ old('jenis_identitas_pic', $data->jenis_identitas_pic) == 'Kartu Mahasiswa' ? 'selected' : '' }}>
                                                Kartu
                                                Mahasiswa</option>
                                        </select>
                                        @error('jenis_identitas_pic')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                    <div class="form-group @error('nomor_identitas_pic') has-error @enderror col-md-6">
                                        <label for="nomor_identitas_pic">Nomor Identitas PIC<span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="nomor_identitas_pic"
                                            name="nomor_identitas_pic" placeholder="Nomor Identitas"
                                            value="{{ old('nomor_identitas_pic', $data->nomor_identitas_pic) }}">
                                        @error('nomor_identitas_pic')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                    <div class=" form-group @error('nohp_pic') has-error @enderror col-md-4">
                                        <label for="nohp_pic">Nomor HP PIC<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="nohp_pic" name="nohp_pic"
                                            placeholder="Contoh: 08123234345"
                                            value="{{ old('nohp_pic', $data->nohp_pic) }}">
                                        @error('nohp_pic')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                    <div class=" form-group @error('nama_kontak_darurat') has-error @enderror col-md-4">
                                        <label for="nohp_pic">Nama Kontak Darurat<span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="nama_kontak_darurat"
                                            name="nama_kontak_darurat" placeholder="Nama Kontak Darurat"
                                            value="{{ old('nama_kontak_darurat', $data->nama_kontak_darurat) }}">
                                        @error('nama_kontak_darurat')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                    <div class=" form-group @error('nomor_kontak_darurat') has-error @enderror col-md-4">
                                        <label for="nomor_kontak_darurat">Nomor Kontak Darurat<span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="nomor_kontak_darurat"
                                            name="nomor_kontak_darurat" placeholder="Contoh: 08123234345"
                                            value="{{ old('nomor_kontak_darurat', $data->nomor_kontak_darurat) }}">
                                        @error('nomor_kontak_darurat')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                    <div class="form-group @error('tempat_lahir') has-error @enderror col-md-4">
                                        <label for="tempat_lahir">Tempat Lahir PIC<span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="tempat_lahir" name="tempat_lahir"
                                            placeholder="Nomor Identitas"
                                            value="{{ old('tempat_lahir', $data->tempat_lahir) }}">
                                        @error('tempat_lahir')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                    <div class=" form-group @error('tanggal_lahir') has-error @enderror col-md-4">
                                        <label for="tanggal_lahir">Tanggal Lahir PIC<span
                                                class="text-danger">*</span></label>
                                        <input type="date" class="form-control" id="tanggal_lahir"
                                            name="tanggal_lahir"
                                            value="{{ old('tanggal_lahir', $data->tanggal_lahir) }}">
                                        @error('tanggal_lahir')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                    <div class="form-group @error('nama_gadis_ibu_kandung') has-error @enderror col-md-4">
                                        <label for="nama_gadis_ibu_kandung">Nama Gadis Ibu Kandung PIC</label>
                                        <input type="text" class="form-control" id="nama_gadis_ibu_kandung"
                                            name="nama_gadis_ibu_kandung" placeholder="Nama Gadis Ibu Kandung"
                                            value="{{ old('nama_gadis_ibu_kandung', $data->nama_gadis_ibu_kandung) }}">
                                        @error('nama_gadis_ibu_kandung')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                    <div class="form-group @error('agama_id') has-error @enderror col-md-3">
                                        <label for="agama_id">Agama PIC<span class="text-danger">*</span></label>
                                        <select name="agama_id" id="agama_id" class="form-control">
                                            <option value="">-- Pilih Data --</option>
                                            @foreach ($agama as $item)
                                                <option value="{{ $item['id'] }}"
                                                    @if (old('agama_id', $data->agama_id) == $item['id']) selected @endif>
                                                    {{ $item['agama'] }}</option>
                                            @endforeach
                                        </select>
                                        @error('agama_id')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                    <div class=" form-group @error('status_perkawinan_id') has-error @enderror col-md-3">
                                        <label for="status_perkawinan_id">Status Perkawinan PIC<span
                                                class="text-danger">*</span></label>
                                        <select name="status_perkawinan_id" id="status_perkawinan_id"
                                            class="form-control">
                                            <option value="">-- Pilih Data --</option>
                                            @foreach ($statusPerkawinan as $item)
                                                <option value="{{ $item['id'] }}"
                                                    @if (old('status_perkawinan_id', $data->status_perkawinan_id) == $item['id']) selected @endif>
                                                    {{ $item['status_pernikahan'] }}</option>
                                            @endforeach
                                        </select>
                                        @error('status_perkawinan_id')
                                            {{ $message }}
                                        @enderror
                                    </div>

                                    <div class=" form-group @error('jenis_pekerjaan_id') has-error @enderror col-md-3">
                                        <label for="jenis_pekerjaan_id">Jenis Pekerjaan PIC<span
                                                class="text-danger">*</span></label>
                                        <select name="jenis_pekerjaan_id" id="jenis_pekerjaan_id" class="form-control">
                                            <option value="">-- Pilih Data --</option>
                                            @foreach ($jenisPekerjaan as $item)
                                                <option value="{{ $item['id'] }}"
                                                    @if (old('jenis_pekerjaan_id', $data->jenis_pekerjaan_id) == $item['id']) selected @endif>
                                                    {{ $item['jenis_pekerjaan'] }}</option>
                                            @endforeach
                                        </select>
                                        @error('jenis_pekerjaan_id')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                    <div class=" form-group @error('pendidikan_id') has-error @enderror col-md-3">
                                        <label for="pendidikan_id">Pendidikan PIC<span
                                                class="text-danger">*</span></label>
                                        <select name="pendidikan_id" id="pendidikan_id" class="form-control">
                                            <option value="">-- Pilih Data --</option>
                                            @foreach ($pendidikan as $item)
                                                <option value="{{ $item['id'] }}"
                                                    @if (old('pendidikan_id', $data->pendidikan_id) == $item['id']) selected @endif>
                                                    {{ $item['pendidikan'] }}</option>
                                            @endforeach
                                        </select>
                                        @error('pendidikan_id')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                    <div class=" form-group @error('alamat_pic') has-error @enderror col-md-12">
                                        <label for="alamat_pic">Alamat PIC <span class="text-danger">*</span></label>
                                        <textarea name="alamat_pic" id="alamat_pic" cols="30" rows="10" class="form-control">
                                            {{ old('alamat_pic', $data->alamat_pic) }}
                                        </textarea>
                                        @error('alamat_pic')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                    <div class=" form-group @error('provinsi_pic') has-error @enderror col-md-3">
                                        <label for="provinsi_pic">Provinsi PIC <span class="text-danger">*</span></label>
                                        <select class="" id="provinsi_pic" name="provinsi_pic" required
                                            onchange="getKotaKabupaten()">
                                            <option class='form-control' value=''>- Pilih Data -
                                            </option>
                                            @isset($provinsi)
                                                @foreach ($provinsi as $item)
                                                    @if (old('provinsi_pic', $data->provinsi_pic) == $item['id'])
                                                        <option class='form-control' value="{{ $item['id'] }}" selected>
                                                            {{ $item['name'] }}</option>
                                                    @else
                                                        <option class='form-control' value="{{ $item['id'] }}">
                                                            {{ $item['name'] }}
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
                                        <label for="kabupaten_pic">Kabupaten PIC <span
                                                class="text-danger">*</span></label>
                                        <select class="" id="kabupaten_pic" name="kabupaten_pic" required
                                            onchange="getKecamatan()">
                                            <option class='form-control' value=''>- Pilih Data -
                                            </option>
                                            @isset($kota)
                                                @foreach ($kota as $item)
                                                    @if (old('kabupaten_pic', $data->kabupaten_pic) == $item['id'])
                                                        <option class='form-control' value="{{ $item['id'] }}" selected>
                                                            {{ $item['name'] }}</option>
                                                    @else
                                                        <option class='form-control' value="{{ $item['id'] }}">
                                                            {{ $item['name'] }}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            @endisset
                                        </select>
                                        @error('kabupaten_pic')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                    <div class=" form-group @error('kecamatan_pic') has-error @enderror col-md-3">
                                        <label for="kecamatan_pic">Kecamatan PIC <span
                                                class="text-danger">*</span></label>
                                        <select class="" id="kecamatan_pic" name="kecamatan_pic" required
                                            onchange="getKelurahan()">
                                            <option class='form-control' value=''>- Pilih Data -
                                            </option>
                                            @isset($kecamatan)
                                                @foreach ($kecamatan as $item)
                                                    @if (old('kecamatan_pic', $data->kecamatan_pic) == $item['id'])
                                                        <option class='form-control' value="{{ $item['id'] }}" selected>
                                                            {{ $item['name'] }}</option>
                                                    @else
                                                        <option class='form-control' value="{{ $item['id'] }}">
                                                            {{ $item['name'] }}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            @endisset
                                        </select>
                                        @error('kecamatan_pic')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                    <div class=" form-group @error('kelurahan_pic') has-error @enderror col-md-3">
                                        <label for="kelurahan_pic">Kelurahan PIC <span
                                                class="text-danger">*</span></label>
                                        <select class="" id="kelurahan_pic" name="kelurahan_pic" required>
                                            <option class='form-control' value=''>- Pilih Data -
                                            </option>
                                            @isset($kelurahan)
                                                @foreach ($kelurahan as $item)
                                                    @if (old('kelurahan_pic', $data->kelurahan_pic) == $item['id'])
                                                        <option class='form-control' value="{{ $item['id'] }}" selected>
                                                            {{ $item['name'] }}</option>
                                                    @else
                                                        <option class='form-control' value="{{ $item['id'] }}">
                                                            {{ $item['name'] }}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            @endisset
                                        </select>
                                        @error('kelurahan_pic')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                    <div class="form-group @error('status_user') has-error @enderror col-md-3">
                                        <label for="status_user">Status User <span class="text-danger">*</span></label>
                                        <select class="form-control" required id="status_user" name="status_user"
                                            required>
                                            <option class='form-control' value=''>- Pilih Data -
                                            </option>
                                            <option class='form-control' value='ACTIVE'
                                                {{ old('status_user', $data->user_akseslh->status_user) == 'ACTIVE' ? 'selected' : '' }}>
                                                ACTIVE
                                            </option>
                                            <option class='form-control' value='NON ACTIVE'
                                                {{ old('status_user', $data->user_akseslh->status_user) == 'NON ACTIVE' ? 'selected' : '' }}>
                                                NON ACTIVE
                                            </option>
                                        </select>

                                        @error('status_user')
                                            {{ $message }}
                                        @enderror
                                    </div>

                                    <div class="form-group @error('foto_ktp') has-error @enderror col-md-3">
                                        <label for="foto_ktp">Foto KTP </label>
                                        <input type="file" name="foto_ktp" id="foto_ktp" class="form-control">
                                        @error('foto_ktp')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                    <div class="form-group @error('profil_kelompok') has-error @enderror col-md-3">
                                        <label for="profil_kelompok">Profil Kelompok </label>
                                        <input type="file" name="profil_kelompok" id="profil_kelompok"
                                            class="form-control">
                                        @error('profil_kelompok')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                    <div class="form-group @error('dokumen_pendukung') has-error @enderror col-md-3">
                                        <label for="dokumen_pendukung">Dokumen Pendukung <span
                                                class="text-danger">*</span></label>
                                        <input type="file" name="dokumen_pendukung" id="dokumen_pendukung"
                                            class="form-control">
                                        @error('dokumen_pendukung')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    @forelse ($data->foto()->whereIn('group', ['profil_kelompok', 'foto_ktp'])->get() as $item)
                                        <a href="{{ env('APP_URL') . '/storage/' . $item->file_path }}" target="_BLANK"
                                            class="col-sm-3 col-lg-3 col-md-3 webdesign illustrator"
                                            style="cursor: pointer" id="lihat-profil-kelompok">
                                            <div class="gal-detail thumb">
                                                <img src="{{ asset('template/images/gallery/1.jpg') }}" class="thumb-img"
                                                    alt="work-thumbnail" />
                                                <h4>{{ ucwords(str_replace('_', ' ', $item->group)) }}</h4>
                                            </div>
                                        </a>
                                    @empty
                                    @endforelse
                                </div>

                                <div class="row m-t-5">
                                    <div class="col-lg-12">
                                        <button type="submit"
                                            class="btn btn-primary waves-effect waves-light">Simpan</button>
                                        <a href="{{ route('pic-kelompok-masyarakat.index') }}"
                                            class="btn btn-inverse waves-effect waves-light">Kembali</a>
                                    </div>
                                </div>
                            </form>
                        </div><!-- panel-body -->
                    </div>
                </div>
                <div class="tab-pane" id="history-document-2">
                    <!-- Personal-Information -->
                    <div class="panel panel-default panel-fill">
                        <div class="panel-heading">
                            <h3 class="panel-title">Dokumen Pendukung Perubahan Profile</h3>
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Nama Dokumen</th>
                                            <th>Tanggal Diunggah</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($data->foto()->where('group', 'dokumen_pendukung')->count() > 0)
                                            @foreach ($data->foto()->where('group', 'dokumen_pendukung')->get() as $item)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $item->real_name }}</td>
                                                    <td>{{ $item->created_at }}</td>
                                                    <td>
                                                        <a href="{{ env('APP_URL') . '/storage/' . $item->file_path }}"
                                                            target="__BLANK"
                                                            class="btn btn-sm btn-clean btn-icon btn-icon-md"
                                                            title="Lihat Dokumen">
                                                            <i class="fa fa-eye"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- Personal-Information -->
                </div>
                <div class="tab-pane" id="history-change-2">
                    <!-- Personal-Information -->
                    <div class="panel panel-default panel-fill">
                        <div class="panel-heading">
                            <h3 class="panel-title">Dokumen Pendukung Perubahan Profile</h3>
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Nama PIC</th>
                                            <th>Email PIC</th>
                                            <th>Jenis Identitas PIC</th>
                                            <th>Nomor Identitas PIC</th>
                                            <th>Nomor NPWP PIC</th>
                                            <th>Nomor HP PIC</th>
                                            <th>Nama Kontak Darurat</th>
                                            <th>Nomor Kontak Darurat</th>
                                            <th>Alamat PIC</th>
                                            <th>Kelurahan/Desa PIC</th>
                                            <th>Kecamatan PIC</th>
                                            <th>Kabupaten/Kota PIC</th>
                                            <th>Provinsi PIC</th>
                                            <th>Tempat Lahir PIC</th>
                                            <th>Tanggal Lahir PIC</th>
                                            <th>Agama PIC</th>
                                            <th>Status Perkawinan PIC</th>
                                            <th>Nama Gadis Ibu Kandung PIC</th>
                                            <th>Jenis Pekerjaan PIC</th>
                                            <th>Pendidikan PIC</th>
                                            <th>Jenis Kelamin PIC</th>
                                            <th>Created At</th>
                                            <th>Updated At</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($data->profile_pic as $item)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $item->nama_pic }}</td>
                                                <td>{{ $item->email_pic }}</td>
                                                <td>{{ $item->jenis_identitas_pic }}</td>
                                                <td>{{ $item->nomor_identitas_pic }}</td>
                                                <td>{{ $item->nomor_npwp_pic }}</td>
                                                <td>{{ $item->nohp_pic }}</td>
                                                <td>{{ $item->nama_kontak_darurat }}</td>
                                                <td>{{ $item->nomor_kontak_darurat }}</td>
                                                <td>{{ $item->alamat_pic }}</td>
                                                <td>{{ $item->kelurahan->name }}</td>
                                                <td>{{ $item->kecamatan->name }}</td>
                                                <td>{{ $item->kabupaten->name }}</td>
                                                <td>{{ $item->provinsi->name }}</td>
                                                <td>{{ $item->tempat_lahir }}</td>
                                                <td>{{ $item->tanggal_lahir }}</td>
                                                <td>{{ $item->agama->agama }}</td>
                                                <td>{{ $item->status_perkawinan->status_pernikahan }}</td>
                                                <td>{{ $item->nama_gadis_ibu_kandung }}</td>
                                                <td>{{ $item->jenis_pekerjaan->jenis_pekerjaan }}</td>
                                                <td>{{ $item->pendidikan->pendidikan }}</td>
                                                <td>{{ $item->jenis_kelamin }}</td>
                                                <td>
                                                    {{ $item->created_at->diffForHumans() }}
                                                </td>
                                                <td>
                                                    {{ $item->updated_at->diffForHumans() }}
                                                </td>
                                            </tr>
                                        @empty
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- Personal-Information -->
                </div>
            </div>
        </div>
    </div>
    <!-- container -->

@endsection
