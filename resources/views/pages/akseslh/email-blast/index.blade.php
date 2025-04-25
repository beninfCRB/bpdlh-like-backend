@extends('layouts.app')

@section('title', 'Data Email Blast')

@section('script')
    <script src="{{ asset('app/build/email_blast.js') }}" type="text/javascript"></script>
@endsection

@section('content')
    <!-- Page-Title -->
    <div class="row">
        <div class="col-sm-12">
            <h4 class="pull-left page-title">EMAIL BLAST</h4>
            <ol class="breadcrumb pull-right">
                <li><a href="#">Data Master</a></li>
                <li class="active">Daftar Email Blast</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Daftar Email Blast</h3>
                    <input type="hidden" name="data-table-email-blast" id="data-table-email-blast"
                        value="{{ route('data-email-blast') }}">
                    <input type="hidden" name="email-blast-route" id="email-blast-route"
                        value="{{ route('email-blast.index') }}">
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <table id="dt_email_blast" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nomor Pengajuan Kegiatan</th>
                                        <th>Email Pic</th>
                                        <th>Email</th>
                                        <th>Status Pengajuan</th>
                                        <th>Sent at</th>
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
