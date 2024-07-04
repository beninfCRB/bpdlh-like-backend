@extends('layouts.app')

@section('title', 'Rekam Data PIC Kelompok Masyarakat')

@section('content')
<!-- Page-Title -->
<div class="row">
    <div class="col-sm-12">
        <h4 class="pull-left page-title">KELOLA DATA PIC KELOMPOK MASYARAKAT</h4>
        <ol class="breadcrumb pull-right">
            <li><a href="#">Data Master</a></li>
            <li><a href="#">Forms</a></li>
            <li class="active">Pengelolaan Data PIC Kelompok Masyarakat</li>
        </ol>
    </div>
</div>


<div class="row">
    <!-- Basic example -->
    <div class="col-md-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">Pengelolaan Data PIC Kelompok Masyaakat</h3>
            </div>
            <div class="panel-body">
                <form role="form" action="#" method="POST">
                    @csrf
                    <div class="form-group @error('pic-kelompok-masyarakat') has-error @enderror row">
                        <div class="col-md-5">
                            <label for="kelompok_masyarakat">Kelompok Masyarakat <span class="text-danger">*</span></label>
                            <select class="form-control" required id="kelompok_masyarakat" name="kelompok_masyarakat" required>
                                <option class='form-control' value=''>- Pilih Data -</option>
                                <option class='form-control' value='jns_klp1'>Jenis Kelompok 1</option>
                                <option class='form-control' value='jns_klp1'>Jenis Kelompok 2</option>
                            </select>
                        </div>
                        <div class="col-md-7">
                            <label for="nama_user_eksternal">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nama_user_eksternal" name="nama_user_eksternal" placeholder="Nama Lengkap">
                        </div>
                        <div class="col-md-4">
                            <label for="jenis_identitas">Jenis Identitas <span class="text-danger">*</span></label>
                            <select class="form-control" required id="jenis_identitas" name="jenis_identitas" required>
                                <option class='form-control' value=''>- Pilih Data -</option>
                                <option class='form-control' value='KTP'>KTP</option>
                                <option class='form-control' value='SIM'>SIM</option>
                                <option class='form-control' value='Kartu Mahasiswa'>Kartu Mahasiswa</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="nomor_identitas">Nomor Identitas <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nomor_identitas" name="nomor_identitas" placeholder="Nomor Identitas">
                        </div>
                        <div class="col-md-4">
                            <label for="nomor_hp">Nomor HP <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nomor_hp" name="nomor_hp" placeholder="Nomor HP">
                        </div>
                        <div class="col-md-4">
                            <label for="email_user">Alamat E-Mail <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="email_user" name="email_user" placeholder="Alamat E-Mail">
                        </div>
                        
                        @error('pic_kelompok_masyarakat')
                        {{ $message }}
                        @enderror
                    </div>
                    <div class="row">
                        <button type="submit" class="btn btn-primary waves-effect waves-light">Simpan</button>
                        <button type="button" class="btn btn-inverse waves-effect waves-light" onclick="window.location='/akseslh/pic-kelompok-masyarakat';">Kembali</button>
                    </div>
                </form>
            </div><!-- panel-body -->
        </div> <!-- panel -->
    </div> <!-- col-->

</div> <!-- End row -->
@endsection