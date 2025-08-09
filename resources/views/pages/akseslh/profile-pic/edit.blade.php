@extends('layouts.app')

@section('title', 'Verifikasi Profil PIC')

@section('content')
    <!-- Page-Title -->
    <div class="row">
        <div class="col-sm-12">
            <h4 class="pull-left page-title">VERIFIKASI PROFILE PIC</h4>
            <ol class="breadcrumb pull-right">
                <li><a href="#">Data User</a></li>
                <li class="active">Verifikasi Profile Pic</li>
            </ol>
        </div>
    </div>

    <!-- Inline Form -->
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Inline Form</h3>
                </div>
                <div class="panel-body">
                    <form class="form-inline" role="form">
                        <div class="form-group">
                            <label class="sr-only" for="exampleInputEmail2">Email address</label>
                            <input type="email" class="form-control" id="exampleInputEmail2" placeholder="Enter email" />
                        </div>

                        <div class="form-group m-l-10">
                            <label class="sr-only" for="exampleInputPassword2">Password</label>
                            <input type="password" class="form-control" id="exampleInputPassword2" placeholder="Password" />
                        </div>
                        <div class="form-group m-l-10">
                            <div class="checkbox checkbox-primary">
                                <input id="checkbox3" type="checkbox" />
                                <label for="checkbox3"> Remember me </label>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success waves-effect waves-light m-l-10">
                            Sign in
                        </button>
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
        <!-- Basic example -->
        <div class="col-md-6">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Verifikasi Profile Pic</h3>
                </div>
                <div class="panel-body">
                    <form role="form" method="POST">
                        <div class="form-group">
                            <label for="nama_pic">Nama PIC <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nama_pic" name="nama_pic" placeholder="Nama PIC"
                                value="{{ $data->data->data_pic_kelompok_masyarakat->nama_pic }}" readonly>
                        </div>
                        <div class="form-group">
                            <label for="nama_pic">Email PIC <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nama_pic" name="nama_pic" placeholder="Nama PIC"
                                value="{{ $data->data->data_pic_kelompok_masyarakat->email_pic }}" readonly>
                        </div>
                    </form>
                </div><!-- panel-body -->
            </div> <!-- panel -->
        </div> <!-- col-->
        <!-- Basic example -->
        <div class="col-md-6">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Verifikasi Profile Pic</h3>
                </div>
                <div class="panel-body">
                    <form role="form" method="POST">
                        <div
                            class="form-group {{ $data->data->data_pic_kelompok_masyarakat->nama_pic == $data->data->nama_pic ? 'has-success' : 'has-error' }}">
                            <label for="nama_pic">Nama PIC <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="text" id="nama_pic" name="nama_pic" class="form-control"
                                    placeholder="Email" value="{{ $data->data->nama_pic }}" readonly />
                                <span class="input-group-addon">
                                    @if ($data->data->data_pic_kelompok_masyarakat->nama_pic != $data->data->nama_pic)
                                        <input type="checkbox" name="" id="">
                                    @endif
                                </span>
                            </div>
                        </div>
                        <div
                            class="form-group {{ $data->data->data_pic_kelompok_masyarakat->email_pic == $data->data->email_pic ? 'has-success' : 'has-error' }}">
                            <label for="nama_pic">Email PIC <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="text" id="example-input2-group1" name="example-input2-group1"
                                    class="form-control" placeholder="Email" value="{{ $data->data->email_pic }}"
                                    readonly />
                                <span class="input-group-addon">
                                    @if ($data->data->data_pic_kelompok_masyarakat->email_pic != $data->data->email_pic)
                                        <input type="checkbox" name="" id="">
                                    @endif
                                </span>
                            </div>
                        </div>
                    </form>
                </div><!-- panel-body -->
            </div> <!-- panel -->
        </div> <!-- col-->

    </div> <!-- End row -->
@endsection
