@extends('layouts.app')

@section('title', 'Verifikasi Profil PIC')

@section('script')
    <script src="{{ asset('app/build/profile_pic.js') }}" type="text/javascript"></script>
@endsection

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

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Verifikasi Profile Pic</h3>
                    <input type="hidden" name="data-table-profile-pic" id="data-table-profile-pic"
                        value="{{ route('data-profile-pic') }}">
                    <input type="hidden" name="profile-pic-route" id="profile-pic-route"
                        value="{{ route('profile-pic.index') }}">
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <table id="dt_profile_pic" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Jenis Kelompok Masyarakat</th>
                                        <th>Kelompok Masyarakat</th>
                                        <th>Nama PIC</th>
                                        <th>Email PIC</th>
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
