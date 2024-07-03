@extends('layouts.app')

@section('title', 'Jenis Kelompok Masyarakat')

@section('script')
<script src="{{asset('app/build/akseslh_jenis_kelompok_masyarakat.js')}}" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function() {
    $('#dt_jenis_kelompok_masyarakat').dataTable();
} );
</script>
@endsection

@section('content')
<!-- Page-Title -->
<div class="row">
    <div class="col-sm-12">
        <h4 class="pull-left page-title">JENIS KELOMPOK MASYARAKAT</h4>
        <ol class="breadcrumb pull-right">
            <li><a href="#">Data Master</a></li>
            <li class="active">Daftar Jenis Kelompok Masyarakat</li>
        </ol>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">Daftar Jenis Kelompok Masyarakat</h3>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <table id="dt_jenis_kelompok_masyarakat" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Jenis Kelompok Masyarakat</th>
                                    <th>Short ID</th>
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