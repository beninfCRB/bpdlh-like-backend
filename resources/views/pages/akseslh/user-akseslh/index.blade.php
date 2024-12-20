@extends('layouts.app')

@section('title', 'Data User Akseslh')

@section('script')
    <script src="{{ asset('app/build/user_akseslh.js') }}" type="text/javascript"></script>
@endsection

@section('content')
    <!-- Page-Title -->
    <div class="row">
        <div class="col-sm-12">
            <h4 class="pull-left page-title">DATA USER AKSESLH</h4>
            <ol class="breadcrumb pull-right">
                <li><a href="#">Data Master</a></li>
                <li class="active">Daftar User Akseslh</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Daftar User Akseslh</h3>
                    <input type="hidden" name="data-table-user-akseslh" id="data-table-user-akseslh"
                        value="{{ route('data-user-akseslh') }}">
                    <input type="hidden" name="user-akseslh-route" id="user-akseslh-route"
                        value="{{ route('user-akseslh.index') }}">
                    <input type="hidden" name="master-user-jenis-kelompok-route" id="master-user-jenis-kelompok-route"
                        value="{{ route('master-user-jenis-kelompok.index') }}">
                </div>
                <div class="panel-body">
                    <div class="row" style="margin-bottom: 10px;">
                        <div class="col-md-12">
                            <div class="pull-right">
                                <a href="{{ route('user-akseslh.create') }}"
                                    class="btn btn-inverse waves-effect waves-light">Tambah Data</a>
                                <button class="btn btn-success waves-effect waves-light" data-toggle="modal"
                                    data-target=".bs-example-modal-sm">Import Excel</button>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <table id="dt_user_akseslh" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Pic</th>
                                        <th>Email</th>
                                        <th>Role User</th>
                                        <th>Status User</th>
                                        <th>Created at</th>
                                        <th>Updated at</th>
                                        <th></th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
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
                            <form action="#" method="post">
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
