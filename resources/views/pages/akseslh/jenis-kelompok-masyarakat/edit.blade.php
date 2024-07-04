@extends('layouts.app')

@section('title', 'Edit Jenis Kelompok Masyarakat')

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
                <form role="form" action="{{ route('jenis-kelompok-masyarakat.update', $data->data->id) }}"
                    method="POST">
                    @method('PUT')
                    @csrf
                    <div class="form-group @error('jenis_kelompok_masyarakat') has-error @enderror">
                        <label for="jenis_kelompok_masyarakat">Jenis Kelompok Masyarakat <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="jenis_kelompok_masyarakat"
                            name="jenis_kelompok_masyarakat" placeholder="Jenis Kelompok Masyarakat"
                            value="{{ old('jenis_kelompok_masyarakat', $data->data->jenis_kelompok_masyarakat) }}">
                        @error('jenis_kelompok_masyarakat')
                        <span class="error">
                            {{ $message }}
                        </span>
                        @enderror
                    </div>

                    <div class="form-group @error('short_id') has-error @enderror">
                        <label for="short_id">Nomor Urut <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" min=0 id="short_id" name="short_id"
                            value="{{ old('short_id', $data->data->short_id) }}">
                        @error('short_id')
                        <span class="error">
                            {{ $message }}
                        </span>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary waves-effect waves-light">Submit</button>
                </form>
            </div><!-- panel-body -->
        </div> <!-- panel -->
    </div> <!-- col-->

</div> <!-- End row -->
@endsection