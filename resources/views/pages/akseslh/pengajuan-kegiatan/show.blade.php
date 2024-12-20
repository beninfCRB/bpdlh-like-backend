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
                            @foreach ($data->data->rab_pengajuan_paket_kegiatans as $item)
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
