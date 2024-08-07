@extends('layouts.app')

@section('title', 'Buat Data Paket Kegiatan')

@section('script')
<script src="{{asset('app/build/paket_kegiatan.js')}}" type="text/javascript"></script>
@endsection

@section('content')
<!-- Page-Title -->
<div class="row">
    <div class="col-sm-12">
        <h4 class="pull-left page-title">KELOLA DATA PAKET KEGIATAN</h4>
        <ol class="breadcrumb pull-right">
            <li><a href="#">Data Master</a></li>
            <li><a href="#">Forms</a></li>
            <li class="active">Pengelolaan Data Paket Kegiatan</li>
        </ol>
    </div>
</div>


<div class="row">
    <!-- Basic example -->
    <div class="col-md-12">
        @error('porsi_pencairan')
        <h1>{{ $message }}</h1>
        @enderror
        <form role="form" action="{{ route('paket-kegiatan.store') }}" method="POST">
            @csrf
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Pengelolaan Data Paket Kegiatan</h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="form-group @error('jenis_kegiatan_id') has-error @enderror col-md-6">
                            <label for="jenis_kegiatan_id">Jenis Kegiatan <span class="text-danger">*</span></label>
                            <select class="form-control" required id="jenis_kegiatan_id" name="jenis_kegiatan_id"
                                required>
                                <option class='form-control' value=''>- Pilih Data -</option>
                                @isset($jenisKegiatan)
                                @foreach ($jenisKegiatan as $item)
                                @if (old('jenis_kegiatan_id') == $item['id'])
                                <option class='form-control' value="{{ $item['id'] }}" selected>{{
                                    $item['jenis_kegiatan'] }}
                                </option>
                                @else
                                <option class='form-control' value="{{ $item['id'] }}">{{
                                    $item['jenis_kegiatan'] }}
                                </option>
                                @endif
                                @endforeach
                                @endisset
                            </select>
                            @error('jenis_kegiatan_id')
                            <span class="error">
                                {{ $message }}
                            </span>
                            @enderror
                        </div>
                        <div class="form-group @error('master_sub_tematik_kegiatan_id') has-error @enderror col-md-6">
                            <label for="master_sub_tematik_kegiatan_id">Tematik Kegiatan <span
                                    class="text-danger">*</span></label>
                            <select class="form-control" required id="master_sub_tematik_kegiatan_id"
                                name="master_sub_tematik_kegiatan_id" required>
                                <option class='form-control' value=''>- Pilih Data -</option>
                                @isset($masterSubTematikKegiatan)
                                @foreach ($masterSubTematikKegiatan as $item)
                                @if (old('master_sub_tematik_kegiatan_id') == $item['id'])
                                <option class='form-control' value="{{ $item['id'] }}" selected>{{
                                    $item['tematik'] }}
                                </option>
                                @else
                                <option class='form-control' value="{{ $item['id'] }}">{{
                                    $item['tematik'] }}
                                </option>
                                @endif
                                @endforeach
                                @endisset
                            </select>
                            @error('master_sub_tematik_kegiatan_id')
                            <span class="error">
                                {{ $message }}
                            </span>
                            @enderror
                        </div>
                        <div class="form-group @error('nama_paket_kegiatan') has-error @enderror col-md-12">
                            <label for="nama_paket_kegiatan">Nama Paket Kegiatan <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nama_paket_kegiatan" name="nama_paket_kegiatan"
                                placeholder="Nama Paket Kegiatan" value="{{ old('nama_paket_kegiatan') }}">
                            @error('nama_paket_kegiatan')
                            <span class="error">
                                {{ $message }}
                            </span>
                            @enderror
                        </div>
                        <div class="form-group @error('deskripsi_paket_kegiatan') has-error @enderror col-md-12">
                            <label for="deskripsi_paket_kegiatan">Deskripsi Paket Kegiatan <span
                                    class="text-danger">*</span></label>
                            <textarea class="form-control" id="deskripsi_paket_kegiatan" name="deskripsi_paket_kegiatan"
                                rows="3"
                                placeholder="Deskripsi Paket Kegiatan">{{ old('deskripsi_paket_kegiatan') }}</textarea>
                            @error('deskripsi_paket_kegiatan')
                            <span class="error">
                                {{ $message }}
                            </span>
                            @enderror
                        </div>
                        <div class="form-group @error('jumlah_peserta') has-error @enderror col-md-3">
                            <label for="jumlah_peserta">Jumlah Peserta <span class="text-danger">*</span></label>
                            <input type="number" min=0 class="form-control" id="jumlah_peserta" name="jumlah_peserta"
                                value="{{ old('jumlah_peserta') }}">
                            @error('jumlah_peserta')
                            <span class="error">
                                {{ $message }}
                            </span>
                            @enderror
                        </div>
                        <div class="form-group @error('quota_paket_kegiatan') has-error @enderror col-md-3">
                            <label for="quota_paket_kegiatan">Quota Paket Kegiatan <span
                                    class="text-danger">*</span></label>
                            <input type="number" min=0 class="form-control" id="quota_paket_kegiatan"
                                name="quota_paket_kegiatan" value="{{ old('quota_paket_kegiatan') }}">
                            @error('quota_paket_kegiatan')
                            <span class="error">
                                {{ $message }}
                            </span>
                            @enderror
                        </div>
                        <div class="form-group @error('pagu_paket_kegiatan') has-error @enderror col-md-3">
                            <label for="pagu_paket_kegiatan">Pagu Paket Kegiatan (Rp) <span
                                    class="text-danger">*</span></label>
                            <input type="number" min=0 step="0.00" class="form-control" id="pagu_paket_kegiatan"
                                name="pagu_paket_kegiatan" value="{{ old('pagu_paket_kegiatan') }}">
                            @error('pagu_paket_kegiatan')
                            <span class="error">
                                {{ $message }}
                            </span>
                            @enderror
                        </div>
                        <div class="form-group @error('tahap_pencairan_paket_kegiatan') has-error @enderror col-md-3">
                            <label for="tahap_pencairan_paket_kegiatan">Jml Tahap Pencairan <span
                                    class="text-danger">*</span></label>
                            <input type="number" min=1 max=5 class="form-control" id="tahap_pencairan_paket_kegiatan"
                                name="tahap_pencairan_paket_kegiatan"
                                value="{{ old('tahap_pencairan_paket_kegiatan', 1) }}"
                                onkeyup="generateFormTahapSalur()">
                            @error('tahap_pencairan_paket_kegiatan')
                            <span class="error">
                                {{ $message }}
                            </span>
                            @enderror
                        </div>
                    </div>
                </div><!-- panel-body -->
            </div> <!-- panel -->
            <div class="panel panel-primary">
                <div class="panel-body">
                    <div class="row" id="dynamicForm">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Tahapan Salur</th>
                                            <th>Porsi Pencairan</th>
                                        </tr>
                                    </thead>
                                    <tbody id="dynamicForm-tbody">
                                        <tr>
                                            <td width='50%'>Tahapan Salur ke-1</td>
                                            <td width='50%'>
                                                <div class="input-group">
                                                    <input type="number" id="example-input2-group1"
                                                        name="porsi_pencairan[1]" class="form-control" min="1" required>
                                                    <span class="input-group-addon">%</span>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel panel-primary">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Komponen Rab</th>
                                            <th>Standar Harga Unit</th>
                                            <th>Qty</th>
                                            <th>Harga Unit</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @isset($masterKomponenRab)
                                        @foreach ($masterKomponenRab as $item)
                                        <tr>
                                            <td>
                                                <input type="checkbox" name="komponen_rab[{{ $loop->iteration }}][id]"
                                                    id="" value="{{ $item['id'] }}">
                                            </td>
                                            <td>{{ $item['komponen_rab'] }}</td>
                                            <td><span id="standar_harga_unit_{{ $loop->iteration }}">
                                                    {{ $item['standar_harga_unit'] }}
                                                </span></td>
                                            <td>
                                                <input type="number" class="form-control"
                                                    name="komponen_rab[{{ $loop->iteration }}][qty]"
                                                    id="qty_{{ $loop->iteration }}" min="1">
                                            </td>
                                            <td>
                                                <input type="number" class="form-control"
                                                    name="komponen_rab[{{ $loop->iteration }}][harga_unit]"
                                                    id="harga_unit_{{ $loop->iteration }}">
                                            </td>
                                        </tr>
                                        @endforeach
                                        @endisset
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel panel-primary">
                <div class="panel-body">
                    <button type="submit" class="btn btn-primary waves-effect waves-light" id="saveBtn">Simpan</button>
                    <a href="{{ route('paket-kegiatan.index') }}"
                        class="btn btn-inverse waves-effect waves-light">Kembali</a>
                </div>
            </div>
        </form>
    </div> <!-- col-->

</div> <!-- End row -->
@endsection