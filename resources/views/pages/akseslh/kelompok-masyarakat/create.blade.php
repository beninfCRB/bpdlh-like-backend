@extends('layouts.app')

@section('title', 'Buat Data Kelompok Masyarakat')

@section('content')
<!-- Page-Title -->
<div class="row">
    <div class="col-sm-12">
        <h4 class="pull-left page-title">Pengelolaan Data Kelompok Masyarakat</h4>
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
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Pengelolaan Data Kelompok Masyarakat</h3>
            </div>
            <div class="panel-body">
                <form role="form" action="{{ route('kelompok-masyarakat.store') }}" method="POST">
                    @csrf
                    <div class="form-group @error('kelompok_masyarakat') has-error @enderror">
                        <div class="cols-md-6">
                            <label for="jenis_kelompok_masyarakat">Jenis Kelompok Masyarakat <span class="text-danger">*</span></label>
                            <select class="select2" required id="jenis_kelamin" name="jenis_kelamin" required>
                                <option class='form-control' value=''>- Pilih Data -</option>
                                <option class='form-control' value='jns_klp1'>Jenis Kelompok 1</option>
                                <option class='form-control' value='jns_klp1'>Jenis Kelompok 2</option>
                            </select>
                        </div>
                        <div class="cols-md-6">
                            <label for="kelompok_masyarakat">Kelompok Masyarakat <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="kelompok_masyarakat" name="kelompok_masyarakat" placeholder="Nama Kelompok Masyarakat" value="{{ $data->data->kelompok_masyarakat }}">
                        </div>
                        
                        @error('jenis_kelompok_masyarakat')
                        {{ $message }}
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-purple waves-effect waves-light">Submit</button>
                </form>
            </div><!-- panel-body -->
        </div> <!-- panel -->
    </div> <!-- col-->

</div> <!-- End row -->
@endsection