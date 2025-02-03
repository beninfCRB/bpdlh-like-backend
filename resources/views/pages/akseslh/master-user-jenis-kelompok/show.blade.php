@extends('layouts.app')

@section('title', 'Daftar Master User Jenis Kelompok')

@section('script')
    <script src="{{ asset('app/build/master_user_jenis_kelompok.js') }}" type="text/javascript"></script>
@endsection

@section('content')
    <!-- Page-Title -->
    <div class="row">
        <div class="col-sm-12">
            <h4 class="pull-left page-title">MASTER USER JENIS KELOMPOK</h4>
            <ol class="breadcrumb pull-right">
                <li><a href="#">Data Master</a></li>
                <li class="active">Daftar Master User Jenis Kelompok</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Daftar Master User Jenis Kelompok</h3>
                    <input type="hidden" name="data-table-master-user-jenis-kelompok"
                        id="data-table-master-user-jenis-kelompok" value="{{ route('data-master-user-jenis-kelompok') }}">
                    <input type="hidden" name="master-user-jenis-kelompok-route" id="master-user-jenis-kelompok-route"
                        value="{{ route('master-user-jenis-kelompok.index') }}">
                </div>
                <div class="panel-body">
                    <div class="row m-b-15">
                        <div class="col-md-6">
                            <input type="email" class="form-control" id="email" value="{{ $data->data->email }}"
                                readonly />

                        </div>
                        <div class="col-md-6">
                            <input type="text" class="form-control" id="nama_pic" value="{{ $data->data->nama_pic }}"
                                readonly />
                        </div>
                    </div>
                    <div class="row m-b-15">
                        <form action="" method="post" onsubmit="createMasterUserJenisKelompok(this,event)">
                            <div class="col-md-6">
                            </div>
                            <div class="col-md-3">
                                <select name="jenis_kelompok_masyarakat_id" id="jenis_kelompok_masyarakat_id"
                                    class="form-control">
                                    <option value="">Pilih Jenis Kelompok</option>
                                    @foreach ($jenisKelompokMasyarakat as $item)
                                        <option value="{{ $item['id'] }}">{{ $item['jenis_kelompok_masyarakat'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <button class="btn btn-primary" type="submit">Tambah</button>
                                <a href="{{ route('user-akseslh.index') }}" class="btn btn-success">Kembali</a>
                            </div>
                        </form>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <input type="hidden" name="user_id" id="user_id" value="{{ $data->data->id }}">
                            <input type="hidden" name="data-table-master-user-jenis-kelompok"
                                id="data-table-master-user-jenis-kelompok"
                                value="{{ route('data-master-user-jenis-kelompok') }}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <table id="dt_master_user_jenis_kelompok" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Jenis Kelompok</th>
                                        <th></th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!-- End Row -->
@endsection
