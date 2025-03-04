@extends('layouts.app')

@section('title', 'Data PIC Kelompok Masyarakat')

@section('script')
    <script src="{{ asset('app/build/pic_kelompok_masyarakat.js') }}" type="text/javascript"></script>
@endsection

@section('content')
    <!-- Page-Title -->
    <div class="row">
        <div class="col-sm-12">
            <h4 class="pull-left page-title">DATA PIC KELOMPOK MASYARAKAT</h4>
            <ol class="breadcrumb pull-right">
                <li><a href="#">Data Master</a></li>
                <li class="active">Daftar PIC Kelompok Masyarakat</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Daftar PIC Kelompok Masyarakat</h3>
                    <input type="hidden" name="data-table-pic-kelompok-masyarakat" id="data-table-pic-kelompok-masyarakat"
                        value="{{ route('data-pic-kelompok-masyarakat') }}">
                    <input type="hidden" name="pic-kelompok-masyarakat-route" id="pic-kelompok-masyarakat-route"
                        value="{{ route('pic-kelompok-masyarakat.index') }}">
                </div>
                <div class="panel-body">
                    <div class="row m-b-10">
                        <div class="col-md-12">
                            <form role="form" class="form-horizontal" method="GET"
                                action="{{ route('pic-kelompok-masyarakat.index') }}">
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
                                        <a href="{{ route('pic-kelompok-masyarakat.index') }}"
                                            class="btn waves-effect waves-light btn-warning">
                                            Reset
                                        </a>
                                    </span>
                                    <span class="input-group-btn">
                                        <a href="{{ route('pic-kelompok-masyarakat.create') }}"
                                            class="btn waves-effect waves-light btn-primary">
                                            Tambah Data
                                        </a>
                                    </span>
                                    <span class="input-group-btn">
                                        <a href="{{ route('pic-kelompok-masyarakat.export') }}"
                                            class="btn waves-effect waves-light btn-success">
                                            Export Excel
                                        </a>
                                    </span>
                                    {{-- <span class="input-group-btn">
                                        <button class="btn waves-effect waves-light btn-success" data-toggle="modal"
                                            data-target=".bs-example-modal-sm">Import Excel</button>
                                    </span> --}}
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="table-responsive m-t-10" data-pattern="priority-columns">
                        <table id="" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Kelompok</th>
                                    <th>Jenis Kelompok</th>
                                    <th>Nama PIC</th>
                                    <th>Jenis Identitas PIC</th>
                                    <th>No Identitas PIC</th>
                                    <th>NPWP</th>
                                    <th>Alamat E-Mail</th>
                                    <th>Provinsi</th>
                                    <th>Kota/Kabupaten</th>
                                    <th>Kecamatan</th>
                                    <th>Kelurahan</th>
                                    <th>Alamat</th>
                                    <th>Tempat Lahir</th>
                                    <th>Tanggal Lahir</th>
                                    <th>Agama</th>
                                    <th>Status Perkawinan</th>
                                    <th>Jenis Pekerjaan</th>
                                    <th>Pendidikan Terakhir</th>
                                    <th>No HP</th>
                                    <th>Status Akun</th>
                                    <th>Created at</th>
                                    <th>Updated at</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($datas as $key => $item)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $item->kelompok_masyarakat->kelompok_masyarakat ?? '-' }}
                                        </td>
                                        <td>{{ $item->kelompok_masyarakat->jenis->jenis_kelompok_masyarakat ?? '-' }}
                                        </td>
                                        <td>{{ $item->nama_pic }}</td>
                                        <td>{{ $item->jenis_identitas_pic }}</td>
                                        <td>{{ $item->nomor_identitas_pic }}</td>
                                        <td>{{ $item->nomor_npwp_pic }}</td>
                                        <td>{{ $item->email_pic }}</td>
                                        <td>{{ $item->provinsi->name }}</td>
                                        <td>{{ $item->kabupaten->name }}</td>
                                        <td>{{ $item->kecamatan->name }}</td>
                                        <td>{{ $item->kelurahan->name }}</td>
                                        <td>{{ \Illuminate\Support\Str::words($item->alamat_pic, 5) ?? '-' }}</td>
                                        <td>{{ $item->tempat_lahir }}</td>
                                        <td>{{ $item->tanggal_lahir }}</td>
                                        <td>{{ $item->agama->agama ?? '-' }}</td>
                                        <td>{{ $item->status_perkawinan->status_pernikahan ?? '-' }}</td>
                                        <td>{{ $item->jenis_pekerjaan->jenis_pekerjaan ?? '-' }}</td>
                                        <td>{{ $item->pendidikan->pendidikan ?? '-' }}</td>
                                        <td>{{ $item->nohp_pic }}</td>
                                        <td>{{ $item->user_akseslh->status_user ?? '-' }}</td>
                                        <td>{{ $item->created_at }}</td>
                                        <td>{{ $item->updated_at }}</td>
                                        <td>
                                            <a href="{{ route('pic-kelompok-masyarakat.edit', $item->id) }}"
                                                class="btn btn-warning btn-xs">Edit</a>
                                            <button class="btn btn-danger btn-xs" data-id="{{ $item->id }}"
                                                onclick="deletePICKelompokMasyarakat({{ $item->id }})">Hapus</button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{ $datas->links() }}
                </div>
            </div>

            <div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel"
                aria-hidden="true" style="display: none;">
                <div class="modal-dialog modal-sm">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            <h4 class="modal-title" id="mySmallModalLabel">Import Data PIC</h4>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('pic-kelompok-masyarakat.import') }}" method="post"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <label for="fileExcel">File Excel</label>
                                    <input type="file" class="form-control" id="fileExcel" name="fileExcel">
                                </div>
                                <button type="submit" class="btn btn-primary waves-effect waves-light">Submit</button>
                            </form>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->
        </div>

    </div>
    <!-- End Row -->
@endsection
