@extends('layouts.app')

@section('title', 'Buat Data Paket Kegiatan')

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
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">Pengelolaan Data Paket Kegiatan</h3>
            </div>
            <div class="panel-body">
                <form role="form" action="#" method="POST">
                    @csrf
                    <div class="form-group @error('paket_kegiatan') has-error @enderror row">
                        <div class="col-md-4">
                            <label for="jenis_kegiatan">Jenis Kegiatan <span class="text-danger">*</span></label>
                            <select class="form-control" required id="jenis_kegiatan" name="jenis_kegiatan" required>
                                <option class='form-control' value=''>- Pilih Data -</option>
                                <option class='form-control' value='jns_klp1'>Jenis Kelompok 1</option>
                                <option class='form-control' value='jns_klp1'>Jenis Kelompok 2</option>
                            </select>
                        </div>
                        <div class="col-md-8">
                            <label for="nama_paket_kegiatan">Nama Paket Kegiatan <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nama_paket_kegiatan" name="nama_paket_kegiatan"
                                placeholder="Nama Paket Kegiatan">
                        </div>
                        <div class="col-md-12 mt-5">
                            <label for="deskripsi_paket_kegiatan">Deskripsi Paket Kegiatan <span
                                    class="text-danger">*</span></label>
                            <textarea class="form-control" id="deskripsi_paket_kegiatan" name="deskripsi_paket_kegiatan"
                                rows="3" placeholder="Deskripsi Paket Kegiatan"></textarea>
                        </div>
                        <div class="col-md-4">
                            <label for="quota_paket_kegiatan">Quota Paket Kegiatan <span
                                    class="text-danger">*</span></label>
                            <input type="number" min=0 class="form-control" id="quota_paket_kegiatan"
                                name="quota_paket_kegiatan">
                        </div>
                        <div class="col-md-4">
                            <label for="pagu_paket_kegiatan">Pagu Paket Kegiatan (Rp) <span
                                    class="text-danger">*</span></label>
                            <input type="number" min=0 step="0.00" class="form-control" id="pagu_paket_kegiatan"
                                name="pagu_paket_kegiatan">
                        </div>
                        <div class="col-md-4">
                            <label for="tahap_pencairan_paket_kegiatan">Jml Tahap Pencairan <span
                                    class="text-danger">*</span></label>
                            <input type="number" min=0 class="form-control" id="tahap_pencairan_paket_kegiatan"
                                name="tahap_pencairan_paket_kegiatan">
                        </div>

                        @error('paket_kegiatan')
                        {{ $message }}
                        @enderror
                    </div>
                    <div class="row">
                        <button type="submit" class="btn btn-primary waves-effect waves-light">Submit</button>
                    </div>
                </form>
            </div><!-- panel-body -->
        </div> <!-- panel -->
    </div> <!-- col-->

</div> <!-- End row -->
@endsection