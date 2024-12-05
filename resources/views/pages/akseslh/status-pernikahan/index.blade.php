@extends('layouts.app')

@section('title', 'Status Pernikahan')

@section('script')
    <script src="{{ asset('app/build/status_pernikahan.js') }}" type="text/javascript"></script>
@endsection

@section('content')
    <!-- Page-Title -->
    <div class="row">
        <div class="col-sm-12">
            <h4 class="pull-left page-title">STATUS PERNIKAHAN</h4>
            <ol class="breadcrumb pull-right">
                <li><a href="#">Data Master</a></li>
                <li class="active">Daftar Status Pernikahan</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Daftar Status Pernikahan</h3>
                    <input type="hidden" name="data-table-status-pernikahan" id="data-table-status-pernikahan"
                        value="{{ route('data-status-pernikahan') }}">
                    <input type="hidden" name="status-pernikahan-route" id="status-pernikahan-route"
                        value="{{ route('status-pernikahan.index') }}">
                </div>
                <div class="panel-body">
                    <div class="row">
                        <a href="{{ route('status-pernikahan.create') }}"
                            class="btn btn-inverse waves-effect waves-light pull-right"
                            style="margin-right:10px;margin-bottom:10px;">Tambah
                            Data</a>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <table id="dt_status_pernikahan" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Status Pernikahan</th>
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
        </div>

    </div>
    <!-- End Row -->
@endsection
