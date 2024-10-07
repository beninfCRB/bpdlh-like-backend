@extends('layouts.app')

@section('title', 'Daftar Transaksi Penyaluran')

@section('script')
    <script src="{{ asset('app/build/transaksi_penyaluran.js') }}" type="text/javascript"></script>
@endsection

@section('content')
    <!-- Page-Title -->
    <div class="row">
        <div class="col-sm-12">
            <h4 class="pull-left page-title">Transaksi Penyaluran</h4>
            <ol class="breadcrumb pull-right">
                <li><a href="#">Data Master</a></li>
                <li class="active">Daftar Transaksi Penyaluran</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Daftar Transaksi Penyaluran</h3>
                    <input type="hidden" name="data-table-transaksi-penyaluran" id="data-table-transaksi-penyaluran"
                        value="{{ route('data-transaksi-penyaluran') }}">
                    <input type="hidden" name="transaksi-penyaluran-route" id="transaksi-penyaluran-route"
                        value="{{ route('transaksi-penyaluran.index') }}">
                </div>
                <div class="panel-body">
                    <div class="row m-b-10 pull-right">
                        <form class="form-inline col-md-12" role="form"
                            action="{{ route('export-excel-transaksi-penyaluran') }}" method="POST">
                            @csrf
                            <div class="form-group m-l-10">
                                <label for="tanggal_awal">Tanggal Awal Transaksi</label>
                                <input type="date" class="form-control" id="tanggal_awal" name="tanggal_awal"
                                    value="{{ old('tanggal_awal') }}" />
                                @error('tanggal_awal')
                                    <span class="error">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group m-l-10">
                                <label for="tanggal_akhir">Tanggal Akhir Transaksi</label>
                                <input type="date" class="form-control" id="tanggal_akhir" name="tanggal_akhir"
                                    value="{{ old('tanggal_akhir') }}" />
                                @error('tanggal_akhir')
                                    <span class="error">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-success waves-effect waves-light m-l-10">
                                Export Excel
                            </button>
                        </form>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <table id="dt_transaksi_penyaluran" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nomor Pengajuan</th>
                                        <th>Tanggal Pengajuan</th>
                                        <th>Nama Kelompok</th>
                                        <th>Jenis Kelompok</th>
                                        <th>Tanggal Transaksi Pencairan</th>
                                        <th>Nilai Transaksi Pencairan</th>
                                        <th>Bank Penerima</th>
                                        <th>Nomor Rekening Penerima</th>
                                        <th>Nama Pemilik Rekening</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th colspan="6">Total Transaksi Pencairan</th>
                                        <th id="total-transaksi-pencairan"></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                </tfoot>

                            </table>

                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!-- End Row -->
@endsection
