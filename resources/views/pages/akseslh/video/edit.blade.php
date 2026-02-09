@extends('layouts.app')

@section('title', 'Edit Video')

@section('content')
    <!-- Page-Title -->
    <div class="row">
        <div class="col-sm-12">
            <h4 class="pull-left page-title">KELOLA DATA VIDEO</h4>
            <ol class="breadcrumb pull-right">
                <li><a href="#">Data Master</a></li>
                <li><a href="#">Forms</a></li>
                <li class="active">Pengelolaan Data Video</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <!-- Basic example -->
        <div class="col-md-6">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Pengelolaan Data Video</h3>
                </div>
                <div class="panel-body">
                    <form role="form" action="{{ route('video.update', $data->data->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="form-group @error('title') has-error @enderror">
                            <label for="title">Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="title" name="title" placeholder="Title"
                                value="{{ old('title', $data->data->title) }}">
                            @error('title')
                                {{ $message }}
                            @enderror
                        </div>
                        <div class="form-group @error('description') has-error @enderror">
                            <label for="description">Description <span class="text-danger">*</span></label>
                            <textarea name="description" id="description" cols="30" rows="10" class="form-control">{{ old('description', $data->data->description) }}</textarea>
                            @error('description')
                                {{ $message }}
                            @enderror
                        </div>
                        <div class="form-group">
                            @if (env('APP_ENV') == 'local')
                                <video width="320" height="240" controls>
                                    <source src="{{ env('APP_URL') . '/storage/' . $data->data->file->file_path }}"
                                        type="video/mp4">
                                    Your browser does not support the video tag.
                                </video>
                            @else
                                <img src="{{ env('APP_URL') . '/' . $data->image->file_path }}" alt=""
                                    srcset="" width="128">
                            @endif
                        </div>
                        <div class="form-group @error('fileVideo') has-error @enderror">
                            <label for="fileVideo">Video </label>
                            <input type="file" class="form-control" id="fileVideo" name="fileVideo" accept="video/*">
                            @error('fileVideo')
                                {{ $message }}
                            @enderror
                        </div>
                        <div class="row"></div>
                        <button type="submit" class="btn btn-primary waves-effect waves-light">Simpan</button>
                        <a href="{{ route('video.index') }}" class="btn btn-inverse waves-effect waves-light">Kembali</a>
                </div>
                </form>
            </div><!-- panel-body -->
        </div> <!-- panel -->
    </div> <!-- col-->

    </div> <!-- End row -->
@endsection
