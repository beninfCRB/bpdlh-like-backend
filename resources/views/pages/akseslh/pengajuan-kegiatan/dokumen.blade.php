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

    <h1>Hello World</h1>
@endsection
