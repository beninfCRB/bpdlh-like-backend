@extends('layouts.app')

@section('style')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
@endsection

@section('script')
    <script src="{{ asset('app/build/pengajuan_kegiatan.js') }}" type="text/javascript"></script>
@endsection

@section('title', 'Lihat Data Pengajuan Kegiatan')

@section('content')
    <!-- Page-Title -->
    <div class="row">
        <div class="col-sm-12">
            <h4 class="pull-left page-title">KELOLA DATA PENGAJUAN KEGIATAN
                <input type="hidden" name="pengajuan-kegiatan-route" id="pengajuan-kegiatan-route"
                    value="{{ route('pengajuan-kegiatan.index') }}">
            </h4>
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
                                <td>Nomor SPTJM</td>
                                <td>{{ $data->data->nomor_sptjm }}
                                    @if (
                                        (auth()->user()->role_user == 'administrator' || auth()->user()->role_user == 'approver') &&
                                            ($data->data->flag > 3 && $data->data->flag < 10))
                                        <button class="btn btn-sm btn-primary" onclick="showModal()">Update
                                            SPTJM</button>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td>Kelompok Masyarakat</td>
                                <td>{{ $data->data->user_akseslh->data_pic_kelompok_masyarakat->kelompok_masyarakat->kelompok_masyarakat }}
                                </td>
                            </tr>
                            <tr>
                                <td>PIC Kelompok Masyarakat</td>
                                <td>{{ $data->data->user_akseslh->data_pic_kelompok_masyarakat->nama_pic }}
                                </td>
                            </tr>
                            <tr>
                                <td>Judul Pengajuan Kegiatan</td>
                                <td>{{ $data->data->judul_pengajuan_kegiatan }}</td>
                            </tr>
                            <tr>
                                <td>Tematik Kegiatan</td>
                                <td>{{ $data->data->paket_kegiatan->master_sub_tematik_kegiatan->tematik_kegiatan->tematik_kegiatan ?? null }}
                                </td>
                            </tr>
                            <tr>
                                <td>Sub Tematik Kegiatan</td>
                                <td>{{ $data->data->paket_kegiatan->master_sub_tematik_kegiatan->sub_tematik_kegiatan->sub_tematik_kegiatan ?? null }}
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
                                <td>{{ $data->data->flag == 20 ? 'Ditolak' : ($data->data->flag == 0 ? 'Draft' : $data->data->tahapan->deskripsi_kegiatan) }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Update SPTJM --}}
    <!-- sample modal content -->
    <div id="modalUpdateSPTJM" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modalUpdateSPTJMLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="" method="post">
                    <div class="modal-header">
                        <button type="button" class="close" onclick="hideModal()" aria-hidden="true">×</button>
                        <h4 class="modal-title" id="modalUpdateSPTJMLabel">Modal Heading</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="nomor_sptjm">Nomor SPTJM</label>
                            <input type="text" class="form-control" id="nomor_sptjm" name="nomor_sptjm"
                                placeholder="Nomor SPTJM" required />
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default waves-effect" onclick="hideModal()"
                            id="close-button">Close</button>
                        <button type="submit" class="btn btn-primary waves-effect waves-light"
                            onclick="updateSPTJM(this, event)" id="save-button" data-id="{{ $data->data->id }}">Save
                            changes</button>
                    </div>
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

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
                                    <td>
                                        @foreach ($item->catatan_log_tahapan_pengajuan_kegiatan as $catatan)
                                            <p>
                                                {{ $catatan->catatan_log ?? null }}
                                            </p>
                                        @endforeach
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

    @if ($data->data->longitude && $data->data->latitude)
        <div class="row">
            <!-- Basic example -->
            <div class="col-md-12">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">Lokasi Kegiatan</h3>
                    </div>
                    <div class="panel-body">
                        <input type="hidden" name="longitude" id="longitude" value="{{ $data->data->longitude }}">
                        <input type="hidden" name="latitude" id="latitude" value="{{ $data->data->latitude }}">
                        <input type="hidden" name="alamat_kegiatan_realisasi" id="alamat_kegiatan_realisasi"
                            value="{{ $data->data->alamat_kegiatan_realisasi }}">
                        <div id="map" style="height: 500px;"></div>
                    </div><!-- panel-body -->
                </div> <!-- panel -->
            </div> <!-- col-->
        </div>
        <!-- End row -->
    @endif

@endsection
