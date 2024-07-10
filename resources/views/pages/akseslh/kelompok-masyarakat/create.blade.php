@extends('layouts.app')

@section('title', 'Buat Data Kelompok Masyarakat')

@section('content')
<!-- Page-Title -->
<div class="row">
    <div class="col-sm-12">
        <h4 class="pull-left page-title">KELOLA DATA KELOMPOK MASYARAKAT</h4>
        <ol class="breadcrumb pull-right">
            <li><a href="#">Data Master</a></li>
            <li><a href="#">Forms</a></li>
            <li class="active">Pengelolaan Data Kelompok Masyarakat</li>
        </ol>
    </div>
</div>


<div class="row">
    <!-- Basic example -->
    <div class="col-md-6">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">Pengelolaan Data Kelompok Masyarakat</h3>
            </div>
            <div class="panel-body">
                <form role="form" action="{{ route('kelompok-masyarakat.store') }}" method="POST">
                    @csrf
                    <div class="form-group @error('kelompok_masyarakat_id') has-error @enderror">
                        <label for="jenis_kelompok_masyarakat_id">Jenis Kelompok Masyarakat <span
                                class="text-danger">*</span></label>
                        <select class="form-control" required id="jenis_kelompok_masyarakat_id"
                            name="jenis_kelompok_masyarakat_id">
                            <option class='form-control' value=''>- Pilih Data -</option>
                            @isset($jenisKelompokMasyarakat)
                            @foreach ($jenisKelompokMasyarakat as $item)
                            @if (old('jenis_kelompok_masyarakat_id'))

                            <option class='form-control' value="{{ $item['id'] }}" selected>{{
                                $item['jenis_kelompok_masyarakat']
                                }}
                            </option>
                            @else
                            <option class='form-control' value="{{ $item['id'] }}">{{
                                $item['jenis_kelompok_masyarakat']
                                }}
                            </option>
                            @endif
                            @endforeach
                            @endisset
                        </select>
                        @error('jenis_kelompok_masyarakat_id')
                        <span class="error">
                            {{ $message }}
                        </span>
                        @enderror
                    </div>
                    <div class="form-group @error('kelompok_masyarakat') has-error @enderror">
                        <label for="kelompok_masyarakat">Kelompok Masyarakat <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="kelompok_masyarakat" name="kelompok_masyarakat"
                            placeholder="Nama Kelompok Masyarakat" value="{{ old('kelompok_masyarakat') }}">
                        @error('kelompok_masyarakat')
                        <span class="error">
                            {{ $message }}
                        </span>
                        @enderror
                    </div>
                    <div class="row">
                        <button type="submit" class="btn btn-primary waves-effect waves-light">Simpan</button>
                        <a href="{{ route('kelompok-masyarakat.index') }}"
                            class="btn btn-inverse waves-effect waves-light">Kembali</a>
                    </div>
                </form>
            </div><!-- panel-body -->
        </div> <!-- panel -->
    </div> <!-- col-->

</div> <!-- End row -->
@endsection