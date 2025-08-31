@extends('layouts.app')

@section('title', 'Verifikasi Pengajuan Kegiatan')

@section('style')
    <link href="{{ asset('template/assets/modal-effect/css/component.css') }}" rel="stylesheet">
@endsection

@section('script')
    <!-- Modal-Effect -->
    <script src="{{ asset('template/assets/modal-effect/js/classie.js') }}"></script>
    <script src="{{ asset('template/assets/modal-effect/js/classie.js') }}"></script>
    <script src="{{ asset('app/build/verifikasi_pengajuan_kegiatan.js') }}" type="text/javascript"></script>
@endsection

@section('content')
    <!-- Page-Title -->
    <div class="row">
        <div class="col-sm-12">
            <h4 class="pull-left page-title">VERIFIKASI ADMINISTRASI PENGAJUAN KEGIATAN</h4>
            <ol class="breadcrumb pull-right">
                <li><a href="#">Kelola Verifikasi</a></li>
                <li class="active">Daftar Verifikasi Pengajuan Kegiatan</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Daftar Verifikasi Pengajuan Kegiatan</h3>
                    <input type="hidden" name="verifikasi-pengajuan-kegiatan-route"
                        id="verifikasi-pengajuan-kegiatan-route" value="{{ route('verifikasi-pengajuan-kegiatan.index') }}">
                    <input type="hidden" name="app-route" id="app-route" value="{{ env('APP_URL') }}">
                    <input type="hidden" name="pengajuan-kegiatan-route" id="pengajuan-kegiatan-route"
                        value="{{ route('pengajuan-kegiatan.index') }}">
                    <input type="hidden" name="profile-pic-route" id="profile-pic-route"
                        value="{{ route('profile-pic.index') }}">
                </div>
                <div class="panel-body">
                    <div class="row m-b-10">
                        <div class="col-md-12">
                            <form role="form" class="form-horizontal" method="GET"
                                action="{{ route('verifikasi-pengajuan-kegiatan.index') }}">
                                @csrf
                                <div class="input-group m-t-10">
                                    <input type="text" id="search" name="search" class="form-control"
                                        value="{{ old('search') }}" placeholder="Search" />
                                    <span class="input-group-btn">
                                        <button type="submit" class="btn waves-effect waves-light btn-info">
                                            <i class="fa fa-search"></i>
                                        </button>
                                    </span>
                                    <span class="input-group-btn">
                                        <a href="{{ route('verifikasi-pengajuan-kegiatan.index') }}"
                                            class="btn waves-effect waves-light btn-warning">
                                            Reset
                                        </a>
                                    </span>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <table id="dt_jenis_kelompok_masyarakat" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Kelompok</th>
                                        <th>Nama PIC</th>
                                        <th>Tematik</th>
                                        <th>Jenis Kegiatan</th>
                                        <th>Nomor Pengajuan</th>
                                        <th>Tanggal Submit</th>
                                        <th>Jumlah</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($pengajuan_kegiatan as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $item->user_akseslh->data_pic_kelompok_masyarakat->kelompok_masyarakat->kelompok_masyarakat ?? '-' }}
                                            </td>
                                            <td>{{ $item->user_akseslh->data_pic_kelompok_masyarakat->nama_pic }}</td>
                                            <td>{{ $item->paket_kegiatan->master_sub_tematik_kegiatan->tematik_kegiatan->tematik_kegiatan ?? '-' }}
                                            </td>
                                            <td>{{ $item->paket_kegiatan->jenis_kegiatan->jenis_kegiatan ?? '-' }}</td>
                                            <td>{{ $item->nomor_pengajuan }}</td>
                                            <td>{{ $item->created_at ? $item->created_at->format('d-m-Y H:i') : '-' }}</td>
                                            <td>{{ $item->paket_kegiatan->jumlah_peserta < 50 ? $item->paket_kegiatan->jumlah_peserta . ' Hectar' : $item->paket_kegiatan->jumlah_peserta . ' Orang' }}
                                            </td>
                                            <td>
                                                <button type="button"
                                                    onclick="verifikasiPengajuanKegiatan({{ $item }}, this)"
                                                    class="btn btn-primary btn-sm">Verifikasi</button>
                                                <button type="button"
                                                    onclick="verifikasiProfile({{ $item }}, this)"
                                                    class="btn btn-primary btn-sm">Lihat Profil</button>
                                            </td>
                                        </tr>
                                    @empty
                                        <p>No users</p>
                                    @endforelse
                                </tbody>
                            </table>
                            {{ $pengajuan_kegiatan->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!-- End Row -->

    <!--  Modal content for the above example -->
    <div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" style="display: none;" id="myModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" onclick="tutupModal()">×</button>
                    <h4 class="modal-title" id="myLargeModalLabel">Verifikasi Pengajuan Kegiatan</h4>
                </div>
                <div class="modal-body">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Diajukan oleh:</h3>
                        </div>
                        <div class="panel-body">
                            <span class="mini-stat-icon"><img src="{{ asset('template/images/users/avatar-1.jpg') }}"
                                    alt="" class="img-circle img-responsive" /></span>
                            <div class="mini-stat-info text-left text-muted">
                                <span class="name" id="kelompok-masyarakat">Kelompok Masyarakat</span>
                                <p id="nama-pic">Nama Pic</p>
                            </div>
                        </div>
                    </div>

                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Paket yang diajukan:</h3>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-4 m-t-10">
                                    <span class="name"><b>Tema</b></span>
                                    <p id="tematik">Name</p>
                                </div>
                                <div class="col-md-4 m-t-10">
                                    <span class="name"><b>Tanggal submit proposal</b></span>
                                    <p id="created-at">Created At</p>
                                </div>
                                <div class="col-md-4 m-t-10">
                                    <span class="name"><b>Rencana Kegiatan</b></span>
                                    <p id="tanggal-kegiatan">Name</p>
                                </div>
                                <div class="col-md-4 m-t-10">
                                    <span class="name"><b id="jenis-kegiatan">Sosialisasi</b></span>
                                    <p id="jumlah-peserta">50 Orang</p>
                                    <p id="nomor-pengajuan"><b>#01211-2508-00028</b></p>
                                </div>
                                <div class="col-md-4 m-t-10">
                                    <span class="name"><b>Lokasi</b></span>
                                    <p id="alamat-kegiatan">Name</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mini-stat clearfix bx-shadow">
                        <div class="mini-stat-info text-left text-muted">
                            <span class="name" id='lampiran'>
                                <i class="md md-insert-drive-file"></i>
                                Lampiran.pdf
                            </span>
                        </div>
                        <br />
                        <hr class="m-t-10" />
                        <ul class="text-center social-links list-inline m-0">
                            <li>
                                <button id="btn-buka-lampiran" type="button"
                                    class="btn btn-success btn-custom waves-effect waves-light m-b-5">
                                    Buka
                                </button>
                            </li>
                        </ul>
                    </div>
                    <div class="mini-stat clearfix bx-shadow">
                        <div class="mini-stat-info text-left text-muted">
                            <span class="name" id="rab">
                                <i class="md md-insert-drive-file"></i>
                                RAB_
                            </span>
                        </div>
                        <br />
                        <hr class="m-t-10" />
                        <ul class="text-center social-links list-inline m-0">
                            <li>
                                <button id="btn-buka-rab" type="button"
                                    class="btn btn-success btn-custom waves-effect waves-light m-b-5">
                                    Buka
                                </button>
                            </li>
                        </ul>
                    </div>

                    <div class="form-group">
                        <label for="commentPengajuan">Comment</label>
                        <textarea class="form-control" rows="5" id="commentPengajuan" name="commentPengajuan"></textarea>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" data-status="0"
                        class="btn btn-danger waves-effect btn-status-pengajuan">Tolak</button>
                    <button type="button" data-status="1"
                        class="btn btn-success waves-effect waves-light btn-status-pengajuan">Setujui</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <!--  Modal content for the above example -->
    <div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" id="profileModal" style="display: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" onclick="tutupModal()">×</button>
                    <h4 class="modal-title" id="profileModalLabel">Lihat Profil</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="nama_pic">Nama lengkap Penanggung Jawab sesuai KTP</label>
                            <input type="text" class="form-control" name="nama_pic" id="nama_pic" readonly />
                        </div>
                        <div class="form-group col-md-12">
                            <label for="email_pic">Email PIC</label>
                            <input type="text" class="form-control" name="email_pic" id="email_pic" readonly />
                        </div>
                        <div class="form-group col-md-6">
                            <label for="nomor_identitas_pic">Nomor KTP</label>
                            <input type="text" class="form-control" name="nomor_identitas_pic"
                                id="nomor_identitas_pic" readonly />
                        </div>
                        <div class="form-group col-md-6">
                            <label for="nomor_npwp_pic">Nomor NPWP</label>
                            <input type="text" class="form-control" name="nomor_npwp_pic" id="nomor_npwp_pic"
                                readonly />
                        </div>
                        <div class="form-group col-md-12">
                            <label for="alamat_pic">Alamat</label>
                            <textarea class="form-control" rows="5" id="alamat_pic" name="alamat_pic" readonly></textarea>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="provinsi_pic">Provinsi</label>
                            <input type="text" class="form-control" name="provinsi_pic" id="provinsi_pic" readonly />
                        </div>
                        <div class="form-group col-md-6">
                            <label for="kabupaten_pic">Kota/Kabupaten</label>
                            <input type="text" class="form-control" name="kabupaten_pic" id="kabupaten_pic"
                                readonly />
                        </div>
                        <div class="form-group col-md-6">
                            <label for="kecamatan_pic">Kecamatan</label>
                            <input type="text" class="form-control" name="kecamatan_pic" id="kecamatan_pic"
                                readonly />
                        </div>
                        <div class="form-group col-md-6">
                            <label for="kelurahan_pic">Kelurahan</label>
                            <input type="text" class="form-control" name="kelurahan_pic" id="kelurahan_pic"
                                readonly />
                        </div>
                        <div class="form-group col-md-6">
                            <label for="tempat_lahir">Tempat Lahir</label>
                            <input type="text" class="form-control" name="tempat_lahir" id="tempat_lahir" readonly />
                        </div>
                        <div class="form-group col-md-6">
                            <label for="tanggal_lahir">Tanggal Lahir</label>
                            <input type="text" class="form-control" name="tanggal_lahir" id="tanggal_lahir"
                                readonly />
                        </div>
                        <div class="form-group col-md-6">
                            <label for="agama_id">Agama</label>
                            <input type="text" class="form-control" name="agama_id" id="agama_id" readonly />
                        </div>
                        <div class="form-group col-md-6">
                            <label for="status_perkawinan_id">Status Perkawinan</label>
                            <input type="text" class="form-control" name="status_perkawinan_id"
                                id="status_perkawinan_id" readonly />
                        </div>
                        <div class="form-group col-md-4">
                            <label for="jenis_pekerjaan_id">Jenis Pekerjaan</label>
                            <input type="text" class="form-control" name="jenis_pekerjaan_id" id="jenis_pekerjaan_id"
                                readonly />
                        </div>
                        <div class="form-group col-md-4">
                            <label for="pendidikan_id">Pendidikan Terakhir</label>
                            <input type="text" class="form-control" name="pendidikan_id" id="pendidikan_id"
                                readonly />
                        </div>
                        <div class="form-group col-md-4">
                            <label for="nohp_pic">Nomor HP</label>
                            <div class="input-group">
                                <span class="input-group-addon">+62</span>
                                <input type="text" id="nohp_pic" name="nohp_pic" class="form-control"
                                    placeholder="Nomor HP" readonly />
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="nama_kontak_darurat">Nama Kontak Darurat</label>
                            <input type="text" class="form-control" name="nama_kontak_darurat"
                                id="nama_kontak_darurat" readonly />
                        </div>
                        <div class="form-group col-md-6">
                            <label for="nomor_kontak_darurat">Nomor Kontak Darurat</label>
                            <div class="input-group">
                                <span class="input-group-addon">+62</span>
                                <input type="text" id="nomor_kontak_darurat" name="nomor_kontak_darurat"
                                    class="form-control" placeholder="Nomor HP" readonly />
                            </div>
                        </div>

                        <div class="col-sm-6 col-lg-6 col-md-6 webdesign illustrator" style="cursor: pointer"
                            id="lihat-profil-kelompok">
                            <div class="gal-detail thumb">
                                <img src="{{ asset('template/images/gallery/1.jpg') }}" class="thumb-img"
                                    alt="work-thumbnail" />
                                <h4>Profil Kelompok</h4>
                            </div>
                        </div>

                        <div class="col-sm-6 col-lg-6 col-md-6 graphicdesign illustrator photography"
                            style="cursor: pointer" id="lihat-foto-ktp">
                            <div class="gal-detail thumb">
                                <img src="{{ asset('template/images/gallery/1.jpg') }}" class="thumb-img"
                                    alt="work-thumbnail" />
                                <h4>KTP PIC</h4>
                            </div>
                        </div>
                        <br />
                        <div class="form-group col-md-12 m-t-10">
                            <label for="commentProfile">Comment</label>
                            <textarea class="form-control" rows="5" required id="commentProfile" name="commentProfile"></textarea>
                        </div>
                    </div>
                </div><!-- /.modal-content -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger waves-effect" id="btn-tolak-profil">Tolak
                        Profil</button>
                </div>
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
    @endsection
