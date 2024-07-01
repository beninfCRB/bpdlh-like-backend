@extends('layouts.app')

@section('title', 'Jenis Kegiatan')

@section('content')
<!-- Page-Title -->
<div class="row">
    <div class="col-sm-12">
        <h4 class="pull-left page-title">General elements</h4>
        <ol class="breadcrumb pull-right">
            <li><a href="#">Moltran</a></li>
            <li><a href="#">Forms</a></li>
            <li class="active">General elements</li>
        </ol>
    </div>
</div>


<div class="row">
    <!-- Basic example -->
    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Basic example</h3>
            </div>
            <div class="panel-body">
                <form role="form" action="{{ route('jenis-kegiatan.store') }}" method="POST">
                    @csrf
                    <div class="form-group @error('jenis_kegiatan') has-error @enderror">
                        <label for="jenis_kegiatan">Jenis Kegiatan <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="jenis_kegiatan" name="jenis_kegiatan"
                            placeholder="Jenis Kegiatan">
                        @error('jenis_kegiatan')
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