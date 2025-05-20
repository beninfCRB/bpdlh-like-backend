@extends('layouts.app')

@section('title', 'Daftar Transaksi Penyaluran')

@section('script')
    {{-- <script src="{{ asset('app/build/transaksi_penyaluran.js') }}" type="text/javascript"></script> --}}
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

    <!-- Inline Form -->
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Form Upload</h3>
                </div>
                <div class="panel-body">
                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif
                    @if ($errors->any())
                        @foreach ($errors->all() as $error)
                            <div class="alert alert-danger">
                                {{ $error }}
                            </div>
                        @endforeach
                    @endif
                    <form class="form-inline" role="form" action="{{ route('transaksi-penyaluran.import') }}"
                        enctype="multipart/form-data" method="POST">
                        @csrf

                        <div class="form-group m-l-10">
                            <label class="sr-only" for="file">Upload File</label>
                            <input type="file" name="file" class="form-control" id="file" />
                            {{-- @error('file')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror --}}
                        </div>

                        <div class="form-group m-l-10">
                            <label for="termin" class="sr-only">Tahap Penyaluran</label>
                            <select name="termin" id="termin" class="form-control" required>
                                <option value="">-- Pilih Tahap Penyaluran --</option>
                                <option value="1">Penyaluran Tahap I</option>
                                <option value="2">Penyaluran Tahap II</option>
                            </select>
                            @error('termin')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-success waves-effect waves-light m-l-10">
                            Upload
                        </button>

                        <a href="{{ route('transaksi-penyaluran.export-template') }}"
                            class="btn btn-info waves-effect waves-light m-l-10">
                            Unduh Template
                        </a>
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
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Daftar Transaksi Penyaluran</h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <table id="t_transaksi_penyaluran" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nomor Pengajuan</th>
                                        <th>Nama Kelompok</th>
                                        <th>Jenis Kelompok</th>
                                        <th>Tanggal Transaksi Pencairan</th>
                                        <th>Nilai Transaksi Pencairan</th>
                                        <th>Bank Penerima</th>
                                        <th>Nomor Rekening Penerima</th>
                                        <th>Nama Pemilik Rekening</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @isset($datas)
                                        @foreach ($datas as $item)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $item->nomor_pengajuan }}</td>
                                                <td>{{ $item->user_akseslh->data_pic_kelompok_masyarakat->kelompok_masyarakat->kelompok_masyarakat }}
                                                </td>
                                                <td>{{ $item->user_akseslh->data_pic_kelompok_masyarakat->kelompok_masyarakat->jenis->jenis_kelompok_masyarakat }}
                                                </td>
                                                <td>{{ $item->transaksi_penyaluran()->first()->tanggal_penyaluran }}</td>
                                                <td>Rp.
                                                    {{ number_format($item->transaksi_penyaluran()->first()->nilai_penyaluran, 0, ',', '.') }}
                                                </td>
                                                <td>{{ $item->transaksi_penyaluran()->first()->master_data_bank->nama_bank }}
                                                </td>
                                                <td>{{ $item->transaksi_penyaluran()->first()->nomor_rekening }}</td>
                                                <td>{{ $item->transaksi_penyaluran()->first()->nama_pemilik_rekening }}</td>
                                                <td class="">
                                                    <a href="#" class="btn btn-sm btn-icon waves-effect btn-default">
                                                        <i class="fa fa-pencil"></i>
                                                    </a>

                                                </td>
                                            </tr>
                                        @endforeach
                                    @endisset
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!-- End Row -->
@endsection
