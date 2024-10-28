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
        <!-- Basic example -->
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Pengelolaan Data Paket Kegiatan</h3>
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
                            @foreach ($data->data['log_tahapan_pengajuan']->sortBy('tahapan_pengajuan_kegiatan.sort') as $item)
                                <tr>
                                    <td>{{ $item->tahapan_pengajuan_kegiatan->deskripsi_kegiatan }}</td>
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

    </div> <!-- End row -->
@endsection
