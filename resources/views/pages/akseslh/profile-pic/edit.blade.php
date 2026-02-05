@extends('layouts.app')

@section('title', 'Verifikasi Profil PIC')

@section('script')
    <script src="{{ asset('app/build/edit_profile_pic.js') }}" type="text/javascript"></script>
@endsection

@section('content')
    <!-- Page-Title -->
    <div class="row">
        <div class="col-sm-12">
            <h4 class="pull-left page-title">VERIFIKASI PERUBAHAN PROFILE PIC</h4>
            <ol class="breadcrumb pull-right">
                <li><a href="#">Data User</a></li>
                <li class="active">Verifikasi Perubahan Profile Pic</li>
            </ol>
        </div>
    </div>

    <!-- Inline Form -->
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                </div>
                <div class="panel-body">
                    <form role="form" class="form-horizontal" method="GET" action="#">
                        @csrf
                        <div class="input-group">
                            <div class="row">
                                <div class="col-md-12">
                                    <input type="text" id="catatan" name="catatan" class="form-control" value=""
                                        placeholder="Catatan" />
                                </div>
                            </div>
                            <span class="input-group-btn m-l-5">
                                <button type="button" class="btn waves-effect waves-light btn-danger" id="btn-tolak">
                                    Tolak Pengajuan Perubahan
                                </button>
                                <button type="button" class="btn waves-effect waves-light btn-success" id="btn-terima">
                                    Terima Pengajuan Perubahan
                                </button>
                            </span>
                        </div>
                    </form>
                </div>
                <!-- panel-body -->
            </div>
            <!-- panel -->
        </div>
        <!-- col -->
    </div>
    <!-- End row -->

    <div class="row">
        <!-- Basic example -->
        <div class="col-md-6">
            <div class="portlet">
                <div class="portlet-heading bg-primary">
                    <h3 class="portlet-title">
                        Profil PIC
                    </h3>
                    <div class="clearfix"></div>
                </div>
                <div id="bg-primary" class="panel-collapse collapse in">
                    <div class="portlet-body">
                        <form role="form" method="POST">
                            <input type="hidden" id="profile_pic_route" name="profile_pic_route"
                                value="{{ route('profile-pic.index') }}">
                            <input type="hidden" id="data_pic_kelompok_masyarakat_id"
                                name="data_pic_kelompok_masyarakat_id"
                                value="{{ $data->data->data_pic_kelompok_masyarakat_id }}">
                            @if ($data->data->jenis_kelompok_masyarakat)
                                <div class="form-group">
                                    <label for="jenis_kelompok_masyarakat">Jenis Kelompok Masyarakat <span
                                            class="text-danger">*</span></label>

                                    <input type="text" class="form-control" id="jenis_kelompok_masyarakat"
                                        name="jenis_kelompok_masyarakat" placeholder="Nama PIC"
                                        value="{{ $data->data->data_pic_kelompok_masyarakat->kelompok_masyarakat->jenis->jenis_kelompok_masyarakat }}"
                                        readonly>
                                </div>
                            @endif
                            <div class="form-group">
                                <label for="kelompok_masyarakat">Kelompok Masyarakat <span
                                        class="text-danger">*</span></label>

                                <input type="text" class="form-control" id="kelompok_masyarakat"
                                    name="kelompok_masyarakat" placeholder="Nama PIC"
                                    value="{{ $data->data->data_pic_kelompok_masyarakat->kelompok_masyarakat->kelompok_masyarakat }}"
                                    readonly>
                            </div>
                            <div class="form-group">
                                <label for="nama_pic">Nama PIC <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nama_pic" name="nama_pic"
                                    placeholder="Nama PIC" value="{{ $data->data->data_pic_kelompok_masyarakat->nama_pic }}"
                                    readonly>
                            </div>
                            <div class="form-group">
                                <label for="nama_pic">Email PIC <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nama_pic" name="nama_pic"
                                    placeholder="Nama PIC"
                                    value="{{ $data->data->data_pic_kelompok_masyarakat->email_pic }}" readonly>
                            </div>
                            @if ($data->data->jenis_identitas_pic && $data->data->jenis_identitas_pic != 'KTP')
                                <div class="form-group">
                                    <label for="jenis_identitas_pic">Jenis Identitas PIC <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="jenis_identitas_pic"
                                        name="jenis_identitas_pic" placeholder="Nama PIC"
                                        value="{{ $data->data->data_pic_kelompok_masyarakat->jenis_identitas_pic }}"
                                        readonly>
                                </div>
                            @endif
                            @if ($data->data->nomor_identitas_pic)
                                <div class="form-group">
                                    <label for="nomor_identitas_pic">Nomor Identitas PIC <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="nomor_identitas_pic"
                                        name="nomor_identitas_pic" placeholder="Nama PIC"
                                        value="{{ $data->data->data_pic_kelompok_masyarakat->nomor_identitas_pic }}"
                                        readonly>
                                </div>
                            @endif
                            @if ($data->data->nomor_npwp_pic)
                                <div class="form-group">
                                    <label for="nomor_npwp_pic">Nomor NPWP PIC <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="nomor_npwp_pic" name="nomor_npwp_pic"
                                        placeholder="Nama PIC"
                                        value="{{ $data->data->data_pic_kelompok_masyarakat->nomor_npwp_pic }}" readonly>
                                </div>
                            @endif
                            @if ($data->data->nohp_pic)
                                <div class="form-group">
                                    <label for="nohp_pic">No. HP PIC <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="nohp_pic" name="nohp_pic"
                                        placeholder="Nama PIC"
                                        value="{{ $data->data->data_pic_kelompok_masyarakat->nohp_pic }}" readonly>
                                </div>
                            @endif
                            @if ($data->data->alamat_pic)
                                <div class="form-group">
                                    <label for="alamat_pic">Alamat PIC <span class="text-danger">*</span></label>
                                    <textarea class="form-control" id="alamat_pic" name="alamat_pic" readonly rows="5">{{ $data->data->data_pic_kelompok_masyarakat->alamat_pic }}</textarea>

                                </div>
                            @endif
                            @if ($data->data->provinsi_pic)
                                <div class="form-group">
                                    <label for="provinsi_pic">Provinsi PIC <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="provinsi_pic" name="provinsi_pic"
                                        placeholder="Nama PIC"
                                        value="{{ $data->data->data_pic_kelompok_masyarakat->provinsi->name }}" readonly>
                                </div>
                            @endif
                            @if ($data->data->kabupaten_pic)
                                <div class="form-group">
                                    <label for="kabupaten_pic">Kota/Kabupaten PIC <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="kabupaten_pic" name="kabupaten_pic"
                                        placeholder="Nama PIC"
                                        value="{{ $data->data->data_pic_kelompok_masyarakat->kabupaten->name }}" readonly>
                                </div>
                            @endif
                            @if ($data->data->kecamatan_pic)
                                <div class="form-group">
                                    <label for="kecamatan_pic">Kecamatan PIC <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="kecamatan_pic" name="kecamatan_pic"
                                        placeholder="Nama PIC"
                                        value="{{ $data->data->data_pic_kelompok_masyarakat->kecamatan->name }}" readonly>
                                </div>
                            @endif
                            @if ($data->data->kelurahan_pic)
                                <div class="form-group">
                                    <label for="kelurahan_pic">Kelurahan PIC <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="kelurahan_pic" name="kelurahan_pic"
                                        placeholder="Nama PIC"
                                        value="{{ $data->data->data_pic_kelompok_masyarakat->kelurahan->name }}" readonly>
                                </div>
                            @endif
                            @if ($data->data->tempat_lahir)
                                <div class="form-group">
                                    <label for="tempat_lahir">Tempat Lahir <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="tempat_lahir" name="tempat_lahir"
                                        value="{{ $data->data->data_pic_kelompok_masyarakat->tempat_lahir }}" readonly>
                                </div>
                            @endif
                            @if ($data->data->tanggal_lahir)
                                <div class="form-group">
                                    <label for="tanggal_lahir">Tanggal Lahir <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="tanggal_lahir" name="tanggal_lahir"
                                        value="{{ $data->data->data_pic_kelompok_masyarakat->tanggal_lahir }}" readonly>
                                </div>
                            @endif
                            @if ($data->data->agama_id)
                                <div class="form-group">
                                    <label for="agama_id">Agama <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="agama_id" name="agama_id"
                                        value="{{ $data->data->data_pic_kelompok_masyarakat->agama->agama ?? null }}"
                                        readonly>
                                </div>
                            @endif
                            @if ($data->data->status_perkawinan_id)
                                <div class="form-group">
                                    <label for="status_perkawinan">Status Pernikahan <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="status_perkawinan"
                                        name="status_perkawinan"
                                        value="{{ $data->data->data_pic_kelompok_masyarakat->status_perkawinan->status_pernikahan ?? null }}"
                                        readonly>
                                </div>
                            @endif
                            @if ($data->data->jenis_pekerjaan_id)
                                <div class="form-group">
                                    <label for="jenis_pekerjaan">Jenis Pekerjaan <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="jenis_pekerjaan"
                                        name="jenis_pekerjaan"
                                        value="{{ $data->data->data_pic_kelompok_masyarakat->jenis_pekerjaan->jenis_pekerjaan ?? null }}"
                                        readonly>
                                </div>
                            @endif
                            @if ($data->data->pendidikan_id)
                                <div class="form-group">
                                    <label for="pendidikan">Pendidikan <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="pendidikan" name="pendidikan"
                                        value="{{ $data->data->data_pic_kelompok_masyarakat->pendidikan->pendidikan ?? null }}"
                                        readonly>
                                </div>
                            @endif
                            @if ($data->data->jenis_kelamin)
                                <div class="form-group">
                                    <label for="jenis_kelamin">Jenis Kelamin <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="jenis_kelamin" name="jenis_kelamin"
                                        value="{{ $data->data->data_pic_kelompok_masyarakat->jenis_kelamin ?? null }}"
                                        readonly>
                                </div>
                            @endif
                            @if ($data->data->nama_kontak_darurat)
                                <div class="form-group">
                                    <label for="nama_kontak_darurat">Nama Kontak Darurat <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="nama_kontak_darurat"
                                        name="nama_kontak_darurat"
                                        value="{{ $data->data->data_pic_kelompok_masyarakat->nama_kontak_darurat ?? null }}"
                                        readonly>
                                </div>
                            @endif

                            @if ($data->data->nomor_kontak_darurat)
                                <div class="form-group">
                                    <label for="nomor_kontak_darurat">Nomor Kontak Darurat <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="nomor_kontak_darurat"
                                        name="nomor_kontak_darurat"
                                        value="{{ $data->data->data_pic_kelompok_masyarakat->nomor_kontak_darurat ?? null }}"
                                        readonly>
                                </div>
                            @endif

                            @if ($data->data->alamat_kontak_darurat)
                                <div class="form-group">
                                    <label for="alamat_kontak_darurat">Alamat Kontak Darurat <span
                                            class="text-danger">*</span></label>
                                    <textarea class="form-control" id="alamat_kontak_darurat" name="alamat_kontak_darurat" readonly rows="5">{{ $data->data->data_pic_kelompok_masyarakat->alamat_kontak_darurat }}</textarea>

                                </div>
                            @endif

                            <div class="row">
                                @forelse ($data->data->data_pic_kelompok_masyarakat->foto as $item)
                                    @if ($item->group == 'foto_ktp' || $item->group == 'profil_kelompok')
                                        <div class="col-md-6">
                                            <a href="{{ config('app.url') . '/storage/' . $item->file_path }}"
                                                target="_blank">
                                                <div class="gal-detail thumb">
                                                    <img src="{{ asset('template/images/gallery/1.jpg') }}"
                                                        class="thumb-img" alt="work-thumbnail" />
                                                    <h4>{{ ucwords(str_replace('_', ' ', $item->group)) }}</h4>
                                                </div>
                                            </a>
                                        </div>
                                    @endif
                                @empty
                                    <div class="col-md-12">
                                        <p><b>Tidak Ada File Upload</b></p>
                                    </div>
                                @endforelse
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div> <!-- col-->
        <!-- Basic example -->
        <div class="col-md-6">
            <div class="portlet">
                <div class="portlet-heading bg-primary">
                    <h3 class="portlet-title">
                        Pengajuan Perubahan Profil PIC
                    </h3>
                    <div class="portlet-widgets">
                        <label for="">Ceklis Semua</label>
                        <span class="divider"></span>
                        <input type="checkbox" name="ceklis_semua" id="ceklis_semua">
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div id="bg-primary" class="panel-collapse collapse in">
                    <div class="portlet-body">
                        <form role="form" method="POST">
                            <input type="hidden" id="profile_pic_id" name="profile_pic_id"
                                value="{{ $data->data->id }}">
                            @if ($data->data->jenis_kelompok_masyarakat)
                                <div
                                    class="form-group {{ $data->data->data_pic_kelompok_masyarakat->kelompok_masyarakat->jenis->jenis_kelompok_masyarakat == $data->data->jenis_kelompok_masyarakat ? 'has-success' : 'has-error' }}">
                                    <label for="jenis_kelompok_masyarakat">Jenis Kelompok Masyarakat <span
                                            class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="text" id="jenis_kelompok_masyarakat"
                                            name="jenis_kelompok_masyarakat" class="form-control"
                                            value="{{ $data->data->jenis_kelompok_masyarakat }}" readonly />
                                        <span class="input-group-addon">
                                            @if (
                                                ($data->data->data_pic_kelompok_masyarakat->kelompok_masyarakat->jenis->id !=
                                                    $data->data->jenis_kelompok_masyarakat_id &&
                                                    $data->data->jenis_kelompok_masyarakat_id) ||
                                                    $data->data->data_pic_kelompok_masyarakat->kelompok_masyarakat->jenis->jenis_kelompok_masyarakat !=
                                                        $data->data->jenis_kelompok_masyarakat)
                                                <input type="checkbox" class="profile-field"
                                                    name="jenis_kelompok_masyarakat" id="">
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            @endif
                            <div
                                class="form-group {{ $data->data->data_pic_kelompok_masyarakat->kelompok_masyarakat->kelompok_masyarakat == $data->data->kelompok_masyarakat ? 'has-success' : 'has-error' }}">
                                <label for="kelompok_masyarakat">Kelompok Masyarakat <span
                                        class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="text" id="kelompok_masyarakat" name="kelompok_masyarakat"
                                        class="form-control" value="{{ $data->data->kelompok_masyarakat }}" readonly />
                                    <span class="input-group-addon">
                                        @if (
                                            ($data->data->data_pic_kelompok_masyarakat->kelompok_masyarakat->id != $data->data->kelompok_masyarakat_id &&
                                                $data->data->kelompok_masyarakat_id) ||
                                                $data->data->data_pic_kelompok_masyarakat->kelompok_masyarakat->kelompok_masyarakat !=
                                                    $data->data->kelompok_masyarakat)
                                            <input type="checkbox" class="profile-field" name="kelompok_masyarakat"
                                                id="">
                                        @endif
                                    </span>
                                </div>
                            </div>

                            <div
                                class="form-group {{ $data->data->data_pic_kelompok_masyarakat->nama_pic == $data->data->nama_pic ? 'has-success' : 'has-error' }}">
                                <label for="nama_pic">Nama PIC <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="text" id="nama_pic" name="nama_pic" class="form-control"
                                        placeholder="Email" value="{{ $data->data->nama_pic }}" readonly />
                                    <span class="input-group-addon">
                                        @if ($data->data->data_pic_kelompok_masyarakat->nama_pic != $data->data->nama_pic && $data->data->nama_pic)
                                            <input type="checkbox" class="profile-field" name="nama_pic" id="">
                                        @endif
                                    </span>
                                </div>
                            </div>

                            <div
                                class="form-group {{ $data->data->data_pic_kelompok_masyarakat->email_pic == $data->data->email_pic ? 'has-success' : 'has-error' }}">
                                <label for="email_pic">Email PIC <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="text" id="email_pic" name="email_pic" class="form-control"
                                        placeholder="Email" value="{{ $data->data->email_pic }}" readonly />
                                    <span class="input-group-addon">
                                        @if ($data->data->data_pic_kelompok_masyarakat->email_pic != $data->data->email_pic && $data->data->email_pic)
                                            <input type="checkbox" class="profile-field" name="email_pic"
                                                id="">
                                        @endif
                                    </span>
                                </div>
                            </div>

                            @if ($data->data->jenis_identitas_pic && $data->data->jenis_identitas_pic != 'KTP')
                                <div
                                    class="form-group {{ $data->data->data_pic_kelompok_masyarakat->jenis_identitas_pic == $data->data->jenis_identitas_pic ? 'has-success' : 'has-error' }}">
                                    <label for="jenis_identitas_pic">Jenis Identitas PIC <span
                                            class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="text" id="jenis_identitas_pic" name="jenis_identitas_pic"
                                            class="form-control" placeholder="Email"
                                            value="{{ $data->data->jenis_identitas_pic }}" readonly />
                                        <span class="input-group-addon">
                                            @if (
                                                $data->data->data_pic_kelompok_masyarakat->jenis_identitas_pic != $data->data->jenis_identitas_pic &&
                                                    $data->data->jenis_identitas_pic)
                                                <input type="checkbox" class="profile-field" name="jenis_identitas_pic"
                                                    id="">
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            @endif

                            @if ($data->data->nomor_identitas_pic)
                                <div
                                    class="form-group {{ $data->data->data_pic_kelompok_masyarakat->nomor_identitas_pic == $data->data->nomor_identitas_pic ? 'has-success' : 'has-error' }}">
                                    <label for="nomor_identitas_pic">Nomor Identitas PIC <span
                                            class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="text" id="nomor_identitas_pic" name="nomor_identitas_pic"
                                            class="form-control" placeholder="Email"
                                            value="{{ $data->data->nomor_identitas_pic }}" readonly />
                                        <span class="input-group-addon">
                                            @if (
                                                $data->data->data_pic_kelompok_masyarakat->nomor_identitas_pic != $data->data->nomor_identitas_pic &&
                                                    $data->data->nomor_identitas_pic)
                                                <input type="checkbox" class="profile-field" name="nomor_identitas_pic"
                                                    id="">
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            @endif

                            @if ($data->data->nomor_npwp_pic)
                                <div
                                    class="form-group {{ $data->data->data_pic_kelompok_masyarakat->nomor_npwp_pic == $data->data->nomor_npwp_pic ? 'has-success' : 'has-error' }}">
                                    <label for="nomor_npwp_pic">Nomor NPWP PIC<span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="text" id="nomor_npwp_pic" name="nomor_npwp_pic"
                                            class="form-control" value="{{ $data->data->nomor_npwp_pic }}" readonly />
                                        <span class="input-group-addon">
                                            @if (
                                                $data->data->data_pic_kelompok_masyarakat->nomor_npwp_pic != $data->data->nomor_npwp_pic &&
                                                    $data->data->nomor_npwp_pic)
                                                <input type="checkbox" class="profile-field" name="nomor_npwp_pic"
                                                    id="">
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            @endif

                            @if ($data->data->nohp_pic)
                                <div
                                    class="form-group {{ $data->data->data_pic_kelompok_masyarakat->nohp_pic == $data->data->nohp_pic ? 'has-success' : 'has-error' }}">
                                    <label for="nohp_pic">No. HP PIC<span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="text" id="nohp_pic" name="nohp_pic" class="form-control"
                                            value="{{ $data->data->nohp_pic }}" readonly />
                                        <span class="input-group-addon">
                                            @if ($data->data->data_pic_kelompok_masyarakat->nohp_pic != $data->data->nohp_pic && $data->data->nohp_pic)
                                                <input type="checkbox" class="profile-field" name="nohp_pic"
                                                    id="">
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            @endif

                            @if ($data->data->alamat_pic)
                                <div
                                    class="form-group {{ $data->data->data_pic_kelompok_masyarakat->alamat_pic == $data->data->alamat_pic ? 'has-success' : 'has-error' }}">
                                    <label for="alamat_pic">Alamat PIC<span class="text-danger">*</span></label>
                                    <div class="input-group">

                                        <textarea id="alamat_pic" name="alamat_pic" class="form-control" rows="5" readonly>{{ $data->data->alamat_pic }}</textarea>
                                        <span class="input-group-addon">
                                            @if ($data->data->data_pic_kelompok_masyarakat->alamat_pic != $data->data->alamat_pic && $data->data->alamat_pic)
                                                <input type="checkbox" class="profile-field" name="alamat_pic"
                                                    id="">
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            @endif

                            @if ($data->data->provinsi_pic)
                                <div
                                    class="form-group {{ $data->data->data_pic_kelompok_masyarakat->provinsi_pic == $data->data->provinsi_pic ? 'has-success' : 'has-error' }}">
                                    <label for="provinsi_pic">Provinsi PIC<span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="text" id="provinsi_pic" name="provinsi_pic" class="form-control"
                                            value="{{ $data->data->provinsi->name ?? null }}" readonly />
                                        <span class="input-group-addon">
                                            @if ($data->data->data_pic_kelompok_masyarakat->provinsi_pic != $data->data->provinsi_pic && $data->data->provinsi_pic)
                                                <input type="checkbox" class="profile-field" name="provinsi_pic"
                                                    id="">
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            @endif

                            @if ($data->data->kabupaten_pic)
                                <div
                                    class="form-group {{ $data->data->data_pic_kelompok_masyarakat->kabupaten_pic == $data->data->kabupaten_pic ? 'has-success' : 'has-error' }}">
                                    <label for="kabupaten_pic">Kota/Kabupaten PIC<span
                                            class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="text" id="kabupaten_pic" name="kabupaten_pic"
                                            class="form-control" value="{{ $data->data->kabupaten->name ?? null }}"
                                            readonly />
                                        <span class="input-group-addon">
                                            @if (
                                                $data->data->data_pic_kelompok_masyarakat->kabupaten_pic != $data->data->kabupaten_pic &&
                                                    $data->data->kabupaten_pic)
                                                <input type="checkbox" class="profile-field" name="kabupaten_pic"
                                                    id="">
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            @endif

                            @if ($data->data->kecamatan_pic)
                                <div
                                    class="form-group {{ $data->data->data_pic_kelompok_masyarakat->kecamatan_pic == $data->data->kecamatan_pic ? 'has-success' : 'has-error' }}">
                                    <label for="kecamatan_pic">Kecamatan PIC<span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="text" id="kecamatan_pic" name="kecamatan_pic"
                                            class="form-control" value="{{ $data->data->kecamatan->name ?? null }}"
                                            readonly />
                                        <span class="input-group-addon">
                                            @if (
                                                $data->data->data_pic_kelompok_masyarakat->kecamatan_pic != $data->data->kecamatan_pic &&
                                                    $data->data->kecamatan_pic)
                                                <input type="checkbox" class="profile-field" name="kecamatan_pic"
                                                    id="">
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            @endif

                            @if ($data->data->kelurahan_pic)
                                <div
                                    class="form-group {{ $data->data->data_pic_kelompok_masyarakat->kelurahan_pic == $data->data->kelurahan_pic ? 'has-success' : 'has-error' }}">
                                    <label for="kelurahan_pic">Kelurahan PIC<span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="text" id="kelurahan_pic" name="kelurahan_pic"
                                            class="form-control" value="{{ $data->data->kelurahan->name ?? null }}"
                                            readonly />
                                        <span class="input-group-addon">
                                            @if (
                                                $data->data->data_pic_kelompok_masyarakat->kelurahan_pic != $data->data->kelurahan_pic &&
                                                    $data->data->kelurahan_pic)
                                                <input type="checkbox" class="profile-field" name="kelurahan_pic"
                                                    id="">
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            @endif

                            @if ($data->data->tempat_lahir)
                                <div
                                    class="form-group {{ $data->data->data_pic_kelompok_masyarakat->tempat_lahir == $data->data->tempat_lahir ? 'has-success' : 'has-error' }}">
                                    <label for="tempat_lahir">Tempat Lahir<span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="text" id="tempat_lahir" name="tempat_lahir" class="form-control"
                                            value="{{ $data->data->tempat_lahir }}" readonly />
                                        <span class="input-group-addon">
                                            @if ($data->data->data_pic_kelompok_masyarakat->tempat_lahir != $data->data->tempat_lahir && $data->data->tempat_lahir)
                                                <input type="checkbox" class="profile-field" name="tempat_lahir"
                                                    id="">
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            @endif

                            @if ($data->data->tanggal_lahir)
                                <div
                                    class="form-group {{ $data->data->data_pic_kelompok_masyarakat->tanggal_lahir == $data->data->tanggal_lahir ? 'has-success' : 'has-error' }}">
                                    <label for="tanggal_lahir">Tanggal Lahir<span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="text" id="tanggal_lahir" name="tanggal_lahir"
                                            class="form-control" value="{{ $data->data->tanggal_lahir }}" readonly />
                                        <span class="input-group-addon">
                                            @if (
                                                $data->data->data_pic_kelompok_masyarakat->tanggal_lahir != $data->data->tanggal_lahir &&
                                                    $data->data->tanggal_lahir)
                                                <input type="checkbox" class="profile-field" name="tanggal_lahir"
                                                    id="">
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            @endif

                            @if ($data->data->agama_id)
                                <div
                                    class="form-group {{ $data->data->data_pic_kelompok_masyarakat->agama_id == $data->data->agama_id ? 'has-success' : 'has-error' }}">
                                    <label for="agama_id">Agama<span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="text" id="agama_id" name="agama_id" class="form-control"
                                            value="{{ $data->data->agama->agama ?? null }}" readonly />
                                        <span class="input-group-addon">
                                            @if ($data->data->data_pic_kelompok_masyarakat->agama_id != $data->data->agama_id && $data->data->agama_id)
                                                <input type="checkbox" class="profile-field" name="agama_id"
                                                    id="">
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            @endif

                            @if ($data->data->status_perkawinan_id)
                                <div
                                    class="form-group {{ $data->data->data_pic_kelompok_masyarakat->status_perkawinan_id == $data->data->status_perkawinan_id ? 'has-success' : 'has-error' }}">
                                    <label for="status_perkawinan_id">Status Pernikahan<span
                                            class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="text" id="status_perkawinan_id" name="status_perkawinan_id"
                                            class="form-control"
                                            value="{{ $data->data->status_perkawinan->status_pernikahan ?? null }}"
                                            readonly />
                                        <span class="input-group-addon">
                                            @if (
                                                $data->data->data_pic_kelompok_masyarakat->status_perkawinan_id != $data->data->status_perkawinan_id &&
                                                    $data->data->status_perkawinan_id)
                                                <input type="checkbox" class="profile-field" name="status_perkawinan_id"
                                                    id="">
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            @endif

                            @if ($data->data->jenis_pekerjaan_id)
                                <div
                                    class="form-group {{ $data->data->data_pic_kelompok_masyarakat->jenis_pekerjaan_id == $data->data->jenis_pekerjaan_id ? 'has-success' : 'has-error' }}">
                                    <label for="jenis_pekerjaan_id">Jenis Pekerjaan<span
                                            class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="text" id="jenis_pekerjaan_id" name="jenis_pekerjaan_id"
                                            class="form-control"
                                            value="{{ $data->data->jenis_pekerjaan->jenis_pekerjaan ?? null }}"
                                            readonly />
                                        <span class="input-group-addon">
                                            @if (
                                                $data->data->data_pic_kelompok_masyarakat->jenis_pekerjaan_id != $data->data->jenis_pekerjaan_id &&
                                                    $data->data->jenis_pekerjaan_id)
                                                <input type="checkbox" class="profile-field" name="jenis_pekerjaan_id"
                                                    id="">
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            @endif

                            @if ($data->data->pendidikan_id)
                                <div
                                    class="form-group {{ $data->data->data_pic_kelompok_masyarakat->pendidikan_id == $data->data->pendidikan_id ? 'has-success' : 'has-error' }}">
                                    <label for="pendidikan_id">Pendidikan<span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="text" id="pendidikan_id" name="pendidikan_id"
                                            class="form-control"
                                            value="{{ $data->data->pendidikan->pendidikan ?? null }}" readonly />
                                        <span class="input-group-addon">
                                            @if (
                                                $data->data->data_pic_kelompok_masyarakat->pendidikan_id != $data->data->pendidikan_id &&
                                                    $data->data->pendidikan_id)
                                                <input type="checkbox" class="profile-field" name="pendidikan_id"
                                                    id="">
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            @endif

                            @if ($data->data->jenis_kelamin)
                                <div
                                    class="form-group {{ $data->data->data_pic_kelompok_masyarakat->jenis_kelamin == $data->data->jenis_kelamin ? 'has-success' : 'has-error' }}">
                                    <label for="jenis_kelamin">Jenis Kelamin<span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="text" id="jenis_kelamin" name="jenis_kelamin"
                                            class="form-control" value="{{ $data->data->jenis_kelamin }}" readonly />
                                        <span class="input-group-addon">
                                            @if (
                                                $data->data->data_pic_kelompok_masyarakat->jenis_kelamin != $data->data->jenis_kelamin &&
                                                    $data->data->jenis_kelamin)
                                                <input type="checkbox" class="profile-field" name="jenis_kelamin"
                                                    id="">
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            @endif

                            @if ($data->data->nama_kontak_darurat)
                                <div
                                    class="form-group {{ $data->data->data_pic_kelompok_masyarakat->nama_kontak_darurat == $data->data->nama_kontak_darurat ? 'has-success' : 'has-error' }}">
                                    <label for="nama_kontak_darurat">Nama Kontak Darurat<span
                                            class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="text" id="nama_kontak_darurat" name="nama_kontak_darurat"
                                            class="form-control" value="{{ $data->data->nama_kontak_darurat }}"
                                            readonly />
                                        <span class="input-group-addon">
                                            @if (
                                                $data->data->data_pic_kelompok_masyarakat->nama_kontak_darurat != $data->data->nama_kontak_darurat &&
                                                    $data->data->nama_kontak_darurat)
                                                <input type="checkbox" class="profile-field" name="nama_kontak_darurat"
                                                    id="">
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            @endif

                            @if ($data->data->nomor_kontak_darurat)
                                <div
                                    class="form-group {{ $data->data->data_pic_kelompok_masyarakat->nomor_kontak_darurat == $data->data->nomor_kontak_darurat ? 'has-success' : 'has-error' }}">
                                    <label for="nomor_kontak_darurat">Nomor Kontak Darurat<span
                                            class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="text" id="nomor_kontak_darurat" name="nomor_kontak_darurat"
                                            class="form-control" value="{{ $data->data->nomor_kontak_darurat }}"
                                            readonly />
                                        <span class="input-group-addon">
                                            @if (
                                                $data->data->data_pic_kelompok_masyarakat->nomor_kontak_darurat != $data->data->nomor_kontak_darurat &&
                                                    $data->data->nomor_kontak_darurat)
                                                <input type="checkbox" class="profile-field" name="nomor_kontak_darurat"
                                                    id="">
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            @endif

                            @if ($data->data->alamat_kontak_darurat)
                                <div
                                    class="form-group {{ $data->data->data_pic_kelompok_masyarakat->alamat_kontak_darurat == $data->data->alamat_kontak_darurat ? 'has-success' : 'has-error' }}">
                                    <label for="alamat_kontak_darurat">Alamat Kontak Darurat<span
                                            class="text-danger">*</span></label>
                                    <div class="input-group">

                                        <textarea id="alamat_kontak_darurat" name="alamat_kontak_darurat" class="form-control" rows="5" readonly>{{ $data->data->alamat_kontak_darurat }}</textarea>
                                        <span class="input-group-addon">
                                            @if (
                                                $data->data->data_pic_kelompok_masyarakat->alamat_kontak_darurat != $data->data->alamat_kontak_darurat &&
                                                    $data->data->alamat_kontak_darurat)
                                                <input type="checkbox" class="profile-field" name="alamat_kontak_darurat"
                                                    id="">
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            @endif


                            <div class="row">
                                @forelse ($data->data->document as $item)
                                    <div class="col-md-6">
                                        <a href="{{ config('app.url') . '/storage/' . $item->file_path }}"
                                            target="_BLANK">
                                            <div class="gal-detail thumb">
                                                <img src="{{ asset('template/images/gallery/1.jpg') }}" class="thumb-img"
                                                    alt="work-thumbnail" />
                                                <h4>{{ ucwords(str_replace('_', ' ', $item->group)) }}
                                                    <input type="checkbox" name="{{ $item->group }}"
                                                        id="{{ $item->group }}" class="pull-right profile-field">
                                                </h4>
                                            </div>
                                        </a>
                                    </div>
                                @empty
                                    <div class="col-md-12">
                                        <p><b>Tidak Ada File Upload</b>
                                        </p>
                                    </div>
                                @endforelse
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div> <!-- col-->
    </div> <!-- End row -->
@endsection
