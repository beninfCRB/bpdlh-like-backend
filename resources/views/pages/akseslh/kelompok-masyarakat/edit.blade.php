@extends('layouts.app')

@section('title', 'Edit Data Kelompok Masyarakat')

@section('script')
    <script>
        jQuery("#provinsi_kelompok_masyarakat_id").select2({
            width: "100%",
        });
        jQuery("#kabupaten_kelompok_masyarakat_id").select2({
            width: "100%",
        });
        jQuery("#kecamatan_kelompok_masyarakat_id").select2({
            width: "100%",
        });
        jQuery("#kelurahan_kelompok_masyarakat_id").select2({
            width: "100%",
        });
    </script>
    <script src="{{ asset('app/build/kelompok_masyarakat.js') }}" type="text/javascript"></script>
@endsection

@section('content')
    <!-- Page-Title -->
    <div class="row">
        <div class="col-sm-12">
            <h4 class="pull-left page-title">KELOLA DATA KELOMPOK MASYARAKAT</h4>
            <ol class="breadcrumb pull-right">
                <li><a href="#">Data Master</a></li>
                <li><a href="#">Forms</a></li>
                <li class="active">Pengelolaan Data Kelompok Masyarakat</li>
            </ol>
        </div>
    </div>


    <div class="row">
        <!-- Basic example -->
        <div class="col-md-6">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Pengelolaan Data Kelompok Masyarakat</h3>
                    <input type="hidden" name="app_url" id="app_url" value="{{ env('APP_URL') }}">
                </div>
                <div class="panel-body">
                    <form role="form" action="{{ route('kelompok-masyarakat.update', $data->data->id) }}" method="POST">
                        @method('PUT')
                        @csrf
                        <div class="form-group @error('kelompok_masyarakat_id') has-error @enderror">
                            <label for="jenis_kelompok_masyarakat_id">Jenis Kelompok Masyarakat <span
                                    class="text-danger">*</span></label>
                            <select class="form-control" required id="jenis_kelompok_masyarakat_id"
                                name="jenis_kelompok_masyarakat_id">
                                <option class='form-control' value=''>- Pilih Data -</option>
                                @isset($jenisKelompokMasyarakat)
                                    @foreach ($jenisKelompokMasyarakat as $item)
                                        @if (old('jenis_kelompok_masyarakat_id', $data->data->jenis_kelompok_masyarakat_id) == $item['id'])
                                            <option class='form-control' value="{{ $item['id'] }}" selected>
                                                {{ $item['jenis_kelompok_masyarakat'] }}
                                            </option>
                                        @else
                                            <option class='form-control' value="{{ $item['id'] }}">
                                                {{ $item['jenis_kelompok_masyarakat'] }}
                                            </option>
                                        @endif
                                    @endforeach
                                @endisset
                            </select>
                            @error('jenis_kelompok_masyarakat_id')
                                <span class="error">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                        <div class="form-group @error('kelompok_masyarakat') has-error @enderror">
                            <label for="kelompok_masyarakat">Kelompok Masyarakat <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="kelompok_masyarakat" name="kelompok_masyarakat"
                                placeholder="Nama Kelompok Masyarakat"
                                value="{{ old('kelompok_masyarakat', $data->data->kelompok_masyarakat) }}">
                            @error('kelompok_masyarakat')
                                <span class="error">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                        <div class="row">
                            <div class=" form-group @error('provinsi_kelompok_masyarakat_id') has-error @enderror col-md-6">
                                <label for="provinsi_kelompok_masyarakat_id">Provinsi Kelompok Masyarakat <span
                                        class="text-danger">*</span></label>
                                <select class="" id="provinsi_kelompok_masyarakat_id"
                                    name="provinsi_kelompok_masyarakat_id" required onchange="getKotaKabupaten()">
                                    <option class='form-control' value=''>- Pilih Data -</option>
                                    @isset($provinsi)
                                        @foreach ($provinsi as $item)
                                            @if (old('provinsi_kelompok_masyarakat_id', $data->data->provinsi_kelompok_masyarakat_id) == $item['id'])
                                                <option class='form-control' value="{{ $item['id'] }}" selected>
                                                    {{ $item['name'] }}</option>
                                            @else
                                                <option class='form-control' value="{{ $item['id'] }}">{{ $item['name'] }}
                                                </option>
                                            @endif
                                        @endforeach
                                    @endisset
                                </select>
                                @error('provinsi_kelompok_masyarakat_id')
                                    {{ $message }}
                                @enderror
                            </div>
                            <div
                                class=" form-group @error('kabupaten_kelompok_masyarakat_id') has-error @enderror col-md-6">
                                <label for="kabupaten_kelompok_masyarakat_id">Kabupaten Kelompok Masyarakat <span
                                        class="text-danger">*</span></label>
                                <select class="" id="kabupaten_kelompok_masyarakat_id"
                                    name="kabupaten_kelompok_masyarakat_id" required onchange="getKecamatan()">
                                    <option class='form-control' value=''>- Pilih Data -</option>
                                    @isset($kota)
                                        @foreach ($kota as $item)
                                            @if (old('kabupaten_kelompok_masyarakat_id', $data->data->kabupaten_kelompok_masyarakat_id) == $item['id'])
                                                <option class='form-control' value="{{ $item['id'] }}" selected>
                                                    {{ $item['name'] }}</option>
                                            @else
                                                <option class='form-control' value="{{ $item['id'] }}">{{ $item['name'] }}
                                                </option>
                                            @endif
                                        @endforeach
                                    @endisset
                                </select>
                                @error('kabupaten_kelompok_masyarakat')
                                    {{ $message }}
                                @enderror
                            </div>
                            <div
                                class=" form-group @error('kecamatan_kelompok_masyarakat_id') has-error @enderror col-md-6">
                                <label for="kecamatan_kelompok_masyarakat_id">Kecamatan PIC <span
                                        class="text-danger">*</span></label>
                                <select class="" id="kecamatan_kelompok_masyarakat_id"
                                    name="kecamatan_kelompok_masyarakat_id" required onchange="getKelurahan()">
                                    <option class='form-control' value=''>- Pilih Data -</option>
                                    @isset($kecamatan)
                                        @foreach ($kecamatan as $item)
                                            @if (old('kecamatan_kelompok_masyarakat_id', $data->data->kecamatan_kelompok_masyarakat_id) == $item['id'])
                                                <option class='form-control' value="{{ $item['id'] }}" selected>
                                                    {{ $item['name'] }}</option>
                                            @else
                                                <option class='form-control' value="{{ $item['id'] }}">{{ $item['name'] }}
                                                </option>
                                            @endif
                                        @endforeach
                                    @endisset
                                </select>
                                @error('kecamatan_kelompok_masyarakat_id')
                                    {{ $message }}
                                @enderror
                            </div>
                            <div
                                class=" form-group @error('kelurahan_kelompok_masyarakat_id') has-error @enderror col-md-6">
                                <label for="kelurahan_kelompok_masyarakat_id">Kelurahan PIC <span
                                        class="text-danger">*</span></label>
                                <select class="" id="kelurahan_kelompok_masyarakat_id"
                                    name="kelurahan_kelompok_masyarakat_id" required>
                                    <option class='form-control' value=''>- Pilih Data -</option>
                                    @isset($kelurahan)
                                        @foreach ($kelurahan as $item)
                                            @if (old('kelurahan_kelompok_masyarakat_id', $data->data->kelurahan_kelompok_masyarakat_id) == $item['id'])
                                                <option class='form-control' value="{{ $item['id'] }}" selected>
                                                    {{ $item['name'] }}</option>
                                            @else
                                                <option class='form-control' value="{{ $item['id'] }}">{{ $item['name'] }}
                                                </option>
                                            @endif
                                        @endforeach
                                    @endisset
                                </select>
                                @error('kelurahan_kelompok_masyarakat_id')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <button type="submit" class="btn btn-primary waves-effect waves-light">Simpan</button>
                            <a href="{{ route('kelompok-masyarakat.index') }}"
                                class="btn btn-inverse waves-effect waves-light">Kembali</a>
                        </div>
                    </form>
                </div><!-- panel-body -->
            </div> <!-- panel -->
        </div> <!-- col-->

    </div> <!-- End row -->
@endsection
