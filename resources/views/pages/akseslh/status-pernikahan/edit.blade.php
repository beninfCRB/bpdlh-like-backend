@extends('layouts.app')

@section('title', 'Edit Status Pernikahan')

@section('content')
    <!-- Page-Title -->
    <div class="row">
        <div class="col-sm-12">
            <h4 class="pull-left page-title">KELOLA DATA STATUS PERNIKAHAN</h4>
            <ol class="breadcrumb pull-right">
                <li><a href="#">Data Master</a></li>
                <li><a href="#">Forms</a></li>
                <li class="active">Pengelolaan Data Status Pernikahan</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <!-- Basic example -->
        <div class="col-md-6">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Pengelolaan Data Status Pernikahan</h3>
                </div>
                <div class="panel-body">
                    <form role="form" action="{{ route('status-pernikahan.update', $data->data->id) }}" method="POST">
                        @method('PUT')
                        @csrf
                        <div class="form-group @error('status_pernikahan') has-error @enderror">
                            <label for="status_pernikahan">Status Pernikahan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="status_pernikahan" name="status_pernikahan"
                                placeholder="Status Pernikahan"
                                value="{{ old('status_pernikahan', $data->data->status_pernikahan) }}">
                            @error('status_pernikahan')
                                <span class="error">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                        <div class="row">
                            <button type="submit" class="btn btn-primary waves-effect waves-light">Simpan</button>
                            <a href="{{ route('status-pernikahan.index') }}"
                                class="btn btn-inverse waves-effect waves-light">Kembali</a>
                        </div>
                    </form>
                </div><!-- panel-body -->
            </div> <!-- panel -->
        </div> <!-- col-->
    </div> <!-- End row -->
@endsection
