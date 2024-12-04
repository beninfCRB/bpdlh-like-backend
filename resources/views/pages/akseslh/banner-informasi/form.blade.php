@extends('layouts.app')

@section('title', 'Buat Jenis Kelompok Masyarakat')

@section('script')
    <script src="{{ asset('app/build/banner_informasi.js') }}" type="text/javascript"></script>
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
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Pengelolaan Data Banner Informasi</h3>
                </div>
                <div class="panel-body">
                    <form role="form" action="{{ route('banner-informasi.store') }}" method="POST">
                        @csrf
                        <div class="form-group @error('deskripsi') has-error @enderror">
                            <label for="deskripsi">Banner Informasi <span class="text-danger">*</span></label>
                            <textarea name="deskripsi" id="deskripsi" cols="30" rows="10">
                                {{ empty($data->data->deskripsi) ? null : $data->data->deskripsi }}
                            </textarea>
                            <input type="hidden" name="id"
                                value="{{ empty($data->data->id) ? null : $data->data->id }}">
                            @error('deskripsi')
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
