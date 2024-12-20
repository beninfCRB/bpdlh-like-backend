@extends('layouts.app')

@section('title', 'Daftar Pendidikan')

@section('script')
    <script src="{{ asset('app/build/pendidikan.js') }}" type="text/javascript"></script>
@endsection

@section('content')
    <!-- Page-Title -->
    <div class="row">
        <div class="col-sm-12">
            <h4 class="pull-left page-title">JENIS PENDIDIKAN</h4>
            <ol class="breadcrumb pull-right">
                <li><a href="#">Data Master</a></li>
                <li class="active">Daftar Pendidikan</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Daftar Pendidikan</h3>
                    <input type="hidden" name="data-table-pendidikan" id="data-table-pendidikan"
                        value="{{ route('data-pendidikan') }}">
                    <input type="hidden" name="pendidikan-route" id="pendidikan-route"
                        value="{{ route('pendidikan.index') }}">
                </div>
                <div class="panel-body">
                    <div class="row">
                        <a href="{{ route('pendidikan.create') }}"
                            class="btn btn-inverse waves-effect waves-light pull-right"
                            style="margin-right:10px;margin-bottom:10px;">Tambah Data</a>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <table id="dt_pendidikan" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Pendidikan</th>
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
