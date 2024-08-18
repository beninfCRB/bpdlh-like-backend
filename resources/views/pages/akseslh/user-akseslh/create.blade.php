@extends('layouts.app')

@section('title', 'Rekam Data User Akseslh')

@section('content')
<!-- Page-Title -->
<div class="row">
    <div class="col-sm-12">
        <h4 class="pull-left page-title">KELOLA DATA USER AKSESLH</h4>
        <ol class="breadcrumb pull-right">
            <li><a href="#">Data Master</a></li>
            <li><a href="#">Forms</a></li>
            <li class="active">Pengelolaan Data User Akseslh</li>
        </ol>
    </div>
</div>


<div class="row">
    <!-- Basic example -->
    <div class="col-md-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">Pengelolaan Data User Akseslh</h3>
            </div>
            <div class="panel-body">
                <form role="form" action="{{ route('user-akseslh.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="form-group @error('nama_pic') has-error @enderror col-md-4">
                            <label for="nama_pic">Nama Lengkap PIC<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nama_pic" name="nama_pic"
                                placeholder="Nama Lengkap Tanpa Gelar" value="{{ old('nama_pic') }}">
                            @error('nama_pic')
                            {{ $message }}
                            @enderror
                        </div>
                        <div class="form-group @error('email') has-error @enderror col-md-4">
                            <label for="email">Alamat E-Mail PIC </label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Alamat E-Mail"
                                value="{{ old('email') }}">
                            @error('email')
                            {{ $message }}
                            @enderror
                        </div>
                        <div class="form-group @error('role_user') has-error @enderror col-md-3">
                            <label for="role_user">Role User <span class="text-danger">*</span></label>
                            <select class="form-control" required id="role_user" name="role_user" required>
                                <option class='form-control' value=''>- Pilih Data -</option>
                                <option class='form-control' value='verifikator' {{ old('role_user')=='verifikator'
                                    ? 'selected' : '' }}>verifikator
                                </option>
                                <option class='form-control' value='approver' {{ old('role_user')=='approver'
                                    ? 'selected' : '' }}>approver
                                </option>
                            </select>
                            @error('status_user')
                            {{ $message }}
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary waves-effect waves-light">Simpan</button>
                            <a href="{{ route('user-akseslh.index') }}"
                                class="btn btn-inverse waves-effect waves-light">Kembali</a>
                        </div>
                    </div>
                </form>
            </div><!-- panel-body -->
        </div> <!-- panel -->
    </div> <!-- col-->

</div> <!-- End row -->
@endsection