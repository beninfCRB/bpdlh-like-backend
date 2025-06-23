@extends('layouts.app')

@section('title', 'Lihat Data Pengajuan Kegiatan')

@section('content')
    <!-- Page-Title -->
    <div class="row">
        <div class="col-sm-12">
            <h4 class="pull-left page-title">KELOLA DATA PENGAJUAN KEGIATAN</h4>
            <ol class="breadcrumb pull-right">
                <li><a href="#">Data Master</a></li>
                <li><a href="{{ route('pengajuan-kegiatan.index') }}">Pengajuan Kegiatan</a></li>
                <li class="active">Lihat Data Pengajuan Kegiatan</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h1 class="panel-title">Pengajuan Kegiatan</h1>
                </div>
                <div class="panel-body">
                    <table class="table table-striped table-bordered">

                        <tbody>
                            <tr>
                                <td>Nomor Pengajuan</td>
                                <td>{{ $data->data->nomor_pengajuan }}</td>
                            </tr>
                            <tr>
                                <td>Kelompok Masyarakat</td>
                                <td>{{ $data->data->user_akseslh->data_pic_kelompok_masyarakat->kelompok_masyarakat->kelompok_masyarakat }}
                                </td>
                            </tr>
                            <tr>
                                <td>Kelompok Masyarakat</td>
                                <td>{{ $data->data->user_akseslh->data_pic_kelompok_masyarakat->nama_pic }}
                                </td>
                            </tr>
                            <tr>
                                <td>Judul Pengajuan Kegiatan</td>
                                <td>{{ $data->data->judul_pengajuan_kegiatan }}</td>
                            </tr>
                            <tr>
                                <td>Tematik Kegiatan</td>
                                <td>{{ $data->data->paket_kegiatan->master_sub_tematik_kegiatan_id->tematik_kegiatan->tematik_kegiatan ?? null }}
                                </td>
                            </tr>
                            <tr>
                                <td>Sub Tematik Kegiatan</td>
                                <td>{{ $data->data->paket_kegiatan->master_sub_tematik_kegiatan_id->sub_tematik_kegiatan->sub_tematik_kegiatan ?? null }}
                                </td>
                            </tr>
                            <tr>
                                <td>Tanggal Mulai</td>
                                <td>{{ $data->data->tanggal_mulai_kegiatan }}</td>
                            </tr>
                            <tr>
                                <td>Tanggal Selesai</td>
                                <td>{{ $data->data->tanggal_akhir_kegiatan }}</td>
                            </tr>
                            <tr>
                                <td>Status Pengajuan</td>
                                <td>{{ $data->data->status_pengajuan }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Indikator Laporan --}}
    @if (isset($data->data->indikator_laporan_kegiatan) && count($data->data->indikator_laporan_kegiatan) > 0)
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h1 class="panel-title">Indikator Laporan</h1>
                    </div>
                    <div class="panel-body">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <td>Nama Indikator</td>
                                    <td>Satuan</td>
                                    <td>Nilai</td>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data->data->indikator_laporan_kegiatan as $item)
                                    <tr>
                                        <td>{{ $item->master_data_indikator_laporan->nama_indikator }}</td>
                                        <td>{{ $item->master_data_indikator_laporan->satuan }}</td>
                                        <td>{{ $item->nilai_laporan }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- RAB Pengajuan Kegiatan --}}
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h1 class="panel-title">RAB Pengajuan Kegiatan</h1>
                </div>
                <div class="panel-body">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <td>Komponen RAB</td>
                                <td>QTY</td>
                                <td>Harga Unit</td>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $total = 0;
                            @endphp
                            @foreach ($data->data->rab_pengajuan_paket_kegiatans as $item)
                                @php
                                    $total += $item->qty * $item->harga_unit;
                                @endphp
                                <tr>
                                    <td>{{ $item->master_komponen_rab->komponen_rab }}</td>
                                    <td>{{ $item->qty }}</td>
                                    <td>Rp. {{ number_format($item->harga_unit) }}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="2">Total</td>
                                <td>Rp. {{ number_format($total) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h1 class="panel-title">Log RAB Pengajuan Kegiatan</h1>
                </div>
                <div class="panel-body">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <td>Komponen RAB</td>
                                <td>QTY</td>
                                <td>Harga Unit</td>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data->data->log_rab_pengajuan_paket_kegiatan as $item)
                                <tr>
                                    <td>{{ $item->master_komponen_rab->komponen_rab }}</td>
                                    <td>{{ $item->qty }}</td>
                                    <td>{{ $item->harga_unit }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Basic example -->
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Log Tahapan Pengajuan</h3>
                </div>
                <div class="panel-body">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Tahapan Pengajuan</th>
                                <th>Tanggal Masuk</th>
                                <th>Tanggal Selesai</th>
                                <th>User</th>
                                <th>Catatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data->data->log_tahapan_pengajuan->sortBy('tahapan_pengajuan_kegiatan.sort') as $item)
                                <tr>
                                    <td>{{ $item->tahapan_pengajuan_kegiatan->deskripsi_kegiatan }}</td>
                                    <td>{{ $item->tanggal_masuk }}</td>
                                    <td>{{ $item->tanggal_selesai }}</td>
                                    <td>{{ $item->user_akseslh_admin->email ?? null }}</td>
                                    <td>{{ $item->catatan_log_tahapan_pengajuan_kegiatan->first()->catatan_log ?? null }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div><!-- panel-body -->
            </div> <!-- panel -->
        </div> <!-- col-->

    </div>
    <!-- End row -->

    {{-- Detail Log Tahapan --}}
    <div class="row">
        <!-- Basic example -->
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Detail Log Tahapan Pengajuan</h3>
                </div>
                <div class="panel-body">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Tahapan Pengajuan</th>
                                <th>Tanggal Masuk</th>
                                <th>Tanggal Selesai</th>
                                <th>User</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data->data->detail_log_tahapan_pengajuan->sortBy('tahapan_pengajuan_kegiatan.sort') as $item)
                                <tr>
                                    <td>{{ $item->tahapan_pengajuan_kegiatan->deskripsi_kegiatan ?? null }}</td>
                                    <td>{{ $item->tanggal_masuk }}</td>
                                    <td>{{ $item->tanggal_selesai }}</td>
                                    <td>{{ $item->user_akseslh_admin->email ?? null }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div><!-- panel-body -->
            </div> <!-- panel -->
        </div> <!-- col-->

    </div>
    <!-- End row -->
@endsection
