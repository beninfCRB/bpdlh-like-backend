@extends('layouts.app')

@section('title', 'Edit Jenis Komponen RAB')

@section('content')
    <!-- Page-Title -->
    <div class="row">
        <div class="col-sm-12">
            <h4 class="pull-left page-title">KELOLA DATA JENIS KOMPONEN RAB</h4>
            <ol class="breadcrumb pull-right">
                <li><a href="#">Data Master</a></li>
                <li><a href="#">Forms</a></li>
                <li class="active">Pengelolaan Data Jenis Komponen RAB</li>
            </ol>
        </div>
    </div>


    <div class="row">
        <!-- Basic example -->
        <div class="col-md-6">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Pengelolaan Data Jenis Komponen RAB</h3>
                </div>
                <div class="panel-body">
                    <form role="form" action="{{ route('master-komponen-rab.update', $data->data->id) }}" method="POST">
                        @method('PUT')
                        @csrf
                        <div class="form-group @error('jenis-komponen-rab') has-error @enderror">
                            <label for="jenis-komponen-rab">Jenis Komponen RAB <span class="text-danger">*</span></label>
                            <select class="form-control" id="jenis-komponen-rab" name="jenis_komponen_rab">
                                <option class="form-control" value="">- Pililh Jenis Komponen -</option>
                                @isset($JenisKomponenRab)
                                    @foreach ($JenisKomponenRab as $item)
                                        @if (old('jenis_komponen_rab_id'))
                                            <option class='form-control' value="{{ $item['id'] }}" selected>
                                                {{ $item['jenis_komponen_rab'] }}
                                            </option>
                                        @else
                                            <option class='form-control'
                                                {{ $item['id'] == $data->data->jenis_komponen_rab_id ? 'selected' : '' }}
                                                value="{{ $item['id'] }}">{{ $item['jenis_komponen_rab'] }}
                                            </option>
                                        @endif
                                    @endforeach
                                @endisset
                            </select>
                            @error('jenis-komponen-rab')
                                {{ $message }}
                            @enderror
                        </div>
                        <div class="form-group @error('satuan') has-error @enderror">
                            <label for="satuan">Satuan <span class="text-danger">*</span></label>
                            <select class="form-control" id="jenis-komponen-rab" name="satuan">
                                <option class="form-control" value="">- Pililh Satuan -</option>
                                @isset($Satuan)
                                    @foreach ($Satuan as $item)
                                        @if (old('satuan'))
                                            <option class='form-control' value="{{ $item['id'] }}" selected>
                                                {{ $item['satuan'] }}
                                            </option>
                                        @else
                                            <option class='form-control'
                                                {{ $item['id'] == $data->data->satuan_id ? 'selected' : '' }}
                                                value="{{ $item['id'] }}">{{ $item['satuan'] }}
                                            </option>
                                        @endif
                                    @endforeach
                                @endisset
                            </select>
                            @error('satuan')
                                {{ $message }}
                            @enderror
                        </div>
                        <div class="form-group @error('master-komponen-rab') has-error @enderror">
                            <label for="master-komponen-rab">Komponen RAB <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="master-komponen-rab" name="master_komponen_rab"
                                value="{{ old('master-komponen-rab', $data->data->komponen_rab) }}">
                            @error('master-komponen-rab')
                                {{ $message }}
                            @enderror
                        </div>
                        <div class="form-group @error('standar-harga-unit') has-error @enderror">
                            <label for="standar-harga-unit">Standar Harga Unit <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="standar-harga-unit" name="standar_harga_unit"
                                value="{{ old('standar-harga-unit', $data->data->standar_harga_unit) }}">
                            @error('standar-harga-unit')
                                {{ $message }}
                            @enderror
                        </div>
                        <div class="row">
                            <button type="submit" class="btn btn-primary waves-effect waves-light">Simpan</button>
                            <a href="{{ route('master-komponen-rab.index') }}"
                                class="btn btn-inverse waves-effect waves-light">Kembali</a>
                        </div>
                    </form>
                </div><!-- panel-body -->
            </div> <!-- panel -->
        </div> <!-- col-->

    </div> <!-- End row -->
@endsection
