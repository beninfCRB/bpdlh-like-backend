@extends('layouts.app')

@section('title', 'Buat Jenis Kelompok Masyarakat')

@section('script')
    <script src=""></script>
@endsection

@section('content')
    <!-- Page-Title -->
    <div class="row">
        <div class="col-sm-12">
            <h4 class="pull-left page-title">KELOLA DATA JENIS KELOMPOK MASYARAKAT</h4>
            <ol class="breadcrumb pull-right">
                <li><a href="#">Data Master</a></li>
                <li><a href="#">Forms</a></li>
                <li class="active">Pengelolaan Data Jenis Kelompok Masyarakat</li>
            </ol>
        </div>
    </div>


    <div class="row">
        <!-- Basic example -->
        <div class="col-md-6">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Pengelolaan Data Jenis Kelompok Masyarakat</h3>
                </div>
                <div class="panel-body">
                    <form role="form" action="{{ route('jenis-kelompok-masyarakat.store') }}" method="POST">
                        @csrf
                        <div class="form-group @error('jenis_kelompok_masyarakat') has-error @enderror">
                            <label for="jenis_kelompok_masyarakat">Jenis Kelompok Masyarakat <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="jenis_kelompok_masyarakat"
                                name="jenis_kelompok_masyarakat" placeholder="Jenis Kelompok Masyarakat"
                                value="{{ old('jenis_kelompok_masyarakat') }}">
                            @error('jenis_kelompok_masyarakat')
                                <span class="error">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>

                        <div class="row">
                            <button type="submit" class="btn btn-primary waves-effect waves-light">Simpan</button>
                            <a href="{{ route('banner-informasi.index') }}"
                                class="btn btn-inverse waves-effect waves-light">Kembali</a>
                        </div>
                    </form>
                </div><!-- panel-body -->
            </div> <!-- panel -->
        </div> <!-- col-->

    </div> <!-- End row -->
@endsection
