@extends('layouts.app')

@section('title', 'Data Pengajuan Kegiatan')

@section('script')
    <script src="{{ asset('app/build/pengajuan_kegiatan.js') }}" type="text/javascript"></script>
@endsection

@section('content')
    <!-- Page-Title -->
    <div class="row">
        <div class="col-sm-12">
            <h4 class="pull-left page-title">PENGAJUAN KEGIATAN</h4>
            <ol class="breadcrumb pull-right">
                <li><a href="#">Data Master</a></li>
                <li class="active">Daftar Pengajuan Kegiatan</li>
            </ol>
        </div>
    </div>

    {{-- Export dan Download --}}
    <div class="row">
        <div class="col-lg-12">
            <ul class="nav nav-tabs navtab-bg">
                <li class="active">
                    <a href="#export" data-toggle="tab" aria-expanded="false">
                        <span class="visible-xs"><i class="fa fa-home"></i></span>
                        <span class="hidden-xs">Export</span>
                    </a>
                </li>
                <li class="">
                    <a href="#download" data-toggle="tab" aria-expanded="true">
                        <span class="visible-xs"><i class="fa fa-user"></i></span>
                        <span class="hidden-xs">Download</span>
                    </a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="export">
                    <form class="row" role="form" action="{{ route('export-excel-pengajuan') }}" method="POST">
                        {{-- <form class="row" role="form" onsubmit="exportPengajuanKegiatan(this,event)"> --}}
                        @csrf
                        <div class="form-group col-md-3">
                            <input type="text" onfocus="(this.type='date')" onblur="(this.type='text')"
                                placeholder="Tanggal Awal" class="form-control" id="tanggal_awal" name="tanggal_awal"
                                value="{{ old('tanggal_awal') }}" required />
                            @error('tanggal_awal')
                                <span class="error">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                        <div class="form-group col-md-3">
                            <input type="text" placeholder="Tanggal Akhir" onfocus="(this.type='date')"
                                onblur="(this.type='text')" class="form-control" id="tanggal_akhir" name="tanggal_akhir"
                                value="{{ old('tanggal_akhir') }}"required />
                            @error('tanggal_akhir')
                                <span class="error">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                        <div class="form-group col-md-3">
                            <select name="flag" class="form-control" id="flag">
                                <option value="">-- Pilih Tahapan --</option>
                                @isset($flag)
                                    @foreach ($flag as $item)
                                        <option value="{{ $item->code_id }}">
                                            {{ $item->deskripsi_kegiatan }}
                                        </option>
                                    @endforeach
                                    <option value="20">Ditolak</option>
                                @endisset
                            </select>
                            @error('flag')
                                <span class="error">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-success waves-effect waves-light">
                                Export Excel
                            </button>
                        </div>
                    </form>
                </div>
                <div class="tab-pane" id="download">
                    <form class="row" role="form" action="{{ route('download-zip') }}" method="post">
                        @csrf
                        <div class="form-group col-md-3">
                            <label class="sr-only" for="tanggal_awal_download">Tanggal Awal</label>
                            <input type="text" onfocus="(this.type='date')" onblur="(this.type='text')"
                                class="form-control" id="tanggal_awal_download" name="tanggal_awal_download"
                                placeholder="Tanggal Awal" />
                            @error('tanggal_awal_download')
                                <span class="error">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>

                        <div class="form-group col-md-3">
                            <label class="sr-only" for="tanggal_akhir_download">Tanggal Akhir</label>
                            <input type="text"onfocus="(this.type='date')" onblur="(this.type='text')"
                                class="form-control" id="tanggal_akhir_download" name="tanggal_akhir_download"
                                placeholder="Tanggal Akhir" />
                            @error('tanggal_akhir_download')
                                <span class="error">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>

                        <div class="form-group col-md-3">
                            <label for="group" class="sr-only">Group</label>
                            <select name="group" id="group" class="form-control">
                                <option value="">-- Pilih --</option>
                                <option value="proposal">Proposal</option>
                                <option value="rab">RAB</option>
                                @foreach ($group as $item)
                                    <option value="{{ $item->group }}">
                                        {{ toPascalCase($item->group) == 'Document' ? 'Lampiran Proposal' : toPascalCase($item->group) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('group')
                                <span class="error">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                        <div class="form-group col-md-3">
                            <button class="btn btn-primary" type="submit">Download</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- end row -->

    {{-- Tabel --}}
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Daftar Pengajuan Kegiatan</h3>
                </div>
                <div class="panel-body table-rep-plugin">
                    <div class="row">
                        <div class="col-md-12">
                            <form role="form" class="form-horizontal" method="GET"
                                action="{{ route('pengajuan-kegiatan.index') }}">
                                @csrf
                                <div class="input-group m-t-10">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <input type="text" id="search" name="search" class="form-control"
                                                value="{{ old('search') }}" placeholder="Search" />
                                        </div>
                                        <div class="col-md-6">
                                            <select name="tahapan" id="tahapan" class="form-control">
                                                <option value="">-- Pilih Tahapan --</option>
                                                @isset($flag)
                                                    @foreach ($flag as $item)
                                                        <option value="{{ $item->code_id }}"
                                                            {{ old('tahapan') == $item->code_id ? 'selected' : '' }}>
                                                            {{ $item->deskripsi_kegiatan }}
                                                        </option>
                                                    @endforeach
                                                    <option value="20" {{ old('tahapan') == 20 ? 'selected' : '' }}>
                                                        Ditolak</option>
                                                @endisset
                                            </select>
                                        </div>
                                    </div>
                                    <span class="input-group-btn m-l-5">
                                        <button type="submit" class="btn waves-effect waves-light btn-info">
                                            <i class="fa fa-search"></i>
                                        </button>
                                    </span>
                                    <span class="input-group-btn">
                                        <a href="{{ route('pengajuan-kegiatan.index') }}"
                                            class="btn waves-effect waves-light btn-warning">
                                            reset
                                        </a>
                                    </span>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="table-responsive m-t-10" data-pattern="priority-columns">
                        <table id="" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Jenis Kelompok Masyarakat</th>
                                    <th>Kelompok Masyarakat</th>
                                    <th>Nama PIC</th>
                                    <th>Jenis Identitas PIC</th>
                                    <th>Nomor Identitas PIC</th>
                                    <th>Kelurahan PIC</th>
                                    <th>Kecamatan PIC</th>
                                    <th>Kabupaten PIC</th>
                                    <th>Provinsi PIC</th>
                                    <th>Email</th>
                                    <th>No. HP</th>
                                    <th>Jenis Kelamin</th>
                                    <th>Nama Gadis Ibu Kandung</th>
                                    <th>Status User</th>
                                    <th>Role User</th>
                                    <th>Nomor Kontak Darurat</th>
                                    <th>Nomor Pengajuan</th>
                                    <th>Tematik Kegiatan</th>
                                    <th>Sub Tematik Kegiatan</th>
                                    <th>Jenis Kegiatan</th>
                                    <th>Nama Kelurahan Kegiatan</th>
                                    <th>Nama Kecamatan Kegiatan</th>
                                    <th>Nama Kabupaten Kegiatan</th>
                                    <th>Nama Provinsi Kegiatan</th>
                                    <th>Jumlah Peserta</th>
                                    <th>Judul Pengajuan Kegiatan</th>
                                    <th>Alamat Kegiatan</th>
                                    <th>Tanggal Kegiatan</th>
                                    <th>Waktu Kegiatan</th>
                                    <th>Proposal Kegiatan</th>
                                    <th>Ruang Lingkup Kegiatan</th>
                                    <th>Total RAB</th>
                                    <th>Total Dana Dicairkan</th>
                                    <th>Flag</th>
                                    <th>Created at</th>
                                    <th>Updated at</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pengajuan_kegiatan as $item)
                                    <tr>
                                        <td>{{ $loop->iteration + ($pengajuan_kegiatan->currentPage() - 1) * $pengajuan_kegiatan->perPage() }}
                                        </td>
                                        <td>{{ $item->user_akseslh->data_pic_kelompok_masyarakat->kelompok_masyarakat->jenis->jenis_kelompok_masyarakat ?? '-' }}
                                        </td>
                                        <td>{{ $item->user_akseslh->data_pic_kelompok_masyarakat->kelompok_masyarakat->kelompok_masyarakat ?? '-' }}
                                        </td>
                                        <td>{{ $item->user_akseslh->data_pic_kelompok_masyarakat->nama_pic ?? '-' }}
                                        </td>
                                        <td>{{ $item->user_akseslh->data_pic_kelompok_masyarakat->jenis_identitas_pic ?? '-' }}
                                        </td>
                                        <td>{{ $item->user_akseslh->data_pic_kelompok_masyarakat->nomor_identitas_pic ?? '-' }}
                                        </td>
                                        <td>{{ $item->user_akseslh->data_pic_kelompok_masyarakat->kelurahan->name ?? '-' }}
                                        </td>
                                        <td>{{ $item->user_akseslh->data_pic_kelompok_masyarakat->kecamatan->name ?? '-' }}
                                        </td>
                                        <td>{{ $item->user_akseslh->data_pic_kelompok_masyarakat->kabupaten->name ?? '-' }}
                                        </td>
                                        <td>{{ $item->user_akseslh->data_pic_kelompok_masyarakat->provinsi->name ?? '-' }}
                                        </td>
                                        <td>{{ $item->user_akseslh->data_pic_kelompok_masyarakat->email_pic ?? '-' }}
                                        </td>
                                        <td>{{ $item->user_akseslh->data_pic_kelompok_masyarakat->nohp_pic ?? '-' }}
                                        </td>
                                        <td>{{ $item->user_akseslh->data_pic_kelompok_masyarakat->jenis_kelamin ?? '-' }}
                                        </td>
                                        <td>{{ $item->user_akseslh->data_pic_kelompok_masyarakat->nama_gadis_ibu_kandung ?? '-' }}
                                        </td>
                                        <td>{{ $item->user_akseslh->status_user ?? '-' }}
                                        </td>
                                        <td>{{ $item->user_akseslh->role_user ?? '-' }}
                                        </td>
                                        <td>{{ $item->user_akseslh->data_pic_kelompok_masyarakat->nomor_kontak_darurat ?? '-' }}
                                        </td>
                                        <td>{{ $item->nomor_pengajuan ?? '-' }}
                                        </td>
                                        <td>{{ $item->paket_kegiatan->master_sub_tematik_kegiatan->tematik_kegiatan->tematik_kegiatan ?? '-' }}
                                        </td>
                                        <td>{{ $item->paket_kegiatan->master_sub_tematik_kegiatan->sub_tematik_kegiatan->sub_tematik_kegiatan ?? '-' }}
                                        </td>
                                        <td>{{ $item->paket_kegiatan->jenis_kegiatan->jenis_kegiatan ?? '-' }}
                                        </td>
                                        <td>{{ $item->kelurahan->name ?? '-' }}
                                        </td>
                                        <td>{{ $item->kecamatan->name ?? '-' }}
                                        </td>
                                        <td>{{ $item->kabupaten->name ?? '-' }}
                                        </td>
                                        <td>{{ $item->provinsi->name ?? '-' }}
                                        </td>
                                        <td>{{ $item->paket_kegiatan->jumlah_peserta < 50 ? $item->paket_kegiatan->jumlah_peserta . ' Hectare' : $item->paket_kegiatan->jumlah_peserta . ' Orang' }}
                                        </td>
                                        <td>{{ $item->judul_pengajuan_kegiatan ?? '-' }}
                                        </td>
                                        <td>{{ $item->alamat_kegiatan ?? '-' }}
                                        </td>
                                        <td>{{ $item->tanggal_mulai_kegiatan . ' - ' . $item->tanggal_akhir_kegiatan }}
                                        </td>
                                        <td>{{ $item->time_mulai_kegiatan . ' - ' . $item->time_akhir_kegiatan }}
                                        </td>
                                        <td>{{ \Illuminate\Support\Str::words($item->proposal_kegiatan, 5) ?? '-' }}
                                        </td>
                                        <td>{{ \Illuminate\Support\Str::words($item->ruang_lingkup_kegiatan, 5) ?? '-' }}
                                        </td>
                                        <td>Rp.
                                            {{ number_format($item->rab_pengajuan_paket_kegiatans->sum(function ($rab) {return $rab->harga_unit * $rab->qty;})) }}
                                        </td>
                                        <td>Rp.
                                            {{ number_format($item->transaksi_penyaluran->sum(function ($rab) {return $rab->nilai_penyaluran;})) }}
                                        </td>
                                        <td>{{ $item->flag == '20' ? 'Ditolak' : $item->tahapan->deskripsi_kegiatan ?? 'Draft' }}
                                        </td>
                                        <td>{{ $item->created_at->format('d-m-Y H:i:s') ?? '-' }}</td>
                                        <td>{{ $item->updated_at->format('d-m-Y H:i:s') ?? '-' }}</td>
                                        <td>
                                            <a href="{{ route('pengajuan-kegiatan.show', $item->id) }}" target="_BLANK"
                                                class="btn btn-icon waves-effect btn-default m-b-5" data-toggle="tooltip"
                                                data-placement="left" title="Detail Pengajuan"><i
                                                    class="fa fa-eye"></i></a>
                                            <a href="{{ route('pengajuan-kegiatan.document', $item->id) }}"
                                                target="_BLANK" class="btn btn-icon waves-effect btn-default m-b-5"
                                                data-toggle="tooltip" data-placement="left"
                                                title="Dokumen
                                                Pengajuan"><i
                                                    class="fa fa-file"></i></a>

                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{ $pengajuan_kegiatan->links() }}
                </div>
            </div>
        </div>
    </div>
    <!-- End Row -->
@endsection
