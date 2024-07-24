@extends('layouts.app')

@section('title', 'Buat Data Pengajuan Kegiatan')

@section('script')
  <script src="{{ asset('app/build/paket_kegiatan.js') }}" type="text/javascript"></script>
@endsection

@section('content')
  <!-- Page-Title -->
  <div class="row">
    <div class="col-sm-12">
      <h4 class="pull-left page-title">KELOLA DATA PENGAJUAN KEGIATAN</h4>
      <ol class="breadcrumb pull-right">
        <li><a href="#">Data Master</a></li>
        <li><a href="#">Forms</a></li>
        <li class="active">Pengelolaan Data Pengajuan Kegiatan</li>
      </ol>
    </div>
  </div>


  <div class="row">
    <!-- Basic example -->
    <div class="col-md-12">
      <div class="panel panel-primary">
        <div class="panel-heading">
          <h3 class="panel-title">Pengelolaan Data Pengajuan Kegiatan</h3>
        </div>
        <div class="panel-body">
          <form role="form" action="{{ route('paket-kegiatan.store') }}" method="POST">
            @csrf
            <div class="row">
              <div class="form-group @error('paket_kegiatan_id') has-error @enderror col-md-6">
                <label for="paket_kegiatan_id">Paket Kegiatan <span class="text-danger">*</span></label>
                <select class="form-control" required id="paket_kegiatan_id" name="paket_kegiatan_id" required>
                  <option class='form-control' value=''>- Pilih Data -</option>
                  @isset($PaketKegiatan)
                    @foreach ($PaketKegiatan as $item)
                      @if (old('paket_kegiatan_id') == $item['id'])
                        <option class='form-control' value="{{ $item['id'] }}" selected>{{ $item['nama_paket_kegiatan'] }}
                        </option>
                      @else
                        <option class='form-control' value="{{ $item['id'] }}">{{ $item['nama_paket_kegiatan'] }}
                        </option>
                      @endif
                    @endforeach
                  @endisset
                </select>
                @error('paket_kegiatan_id')
                  <span class="error">
                    {{ $message }}
                  </span>
                @enderror
              </div>
              <div class="form-group @error('user_eksternal_id') has-error @enderror col-md-6">
                <label for="user_eksternal_id">User Eksternal <span class="text-danger">*</span></label>
                <select class="form-control" required id="user_eksternal_id" name="user_eksternal_id" required>
                  <option class='form-control' value=''>- Pilih Data -</option>
                  @isset($tematikKegiatan)
                    @foreach ($tematikKegiatan as $item)
                      @if (old('user_eksternal_id') == $item['id'])
                        <option class='form-control' value="{{ $item['id'] }}" selected>{{ $item['nama_user_eksternal'] }}
                        </option>
                      @else
                        <option class='form-control' value="{{ $item['id'] }}">{{ $item['nama_user_eksternal'] }}
                        </option>
                      @endif
                    @endforeach
                  @endisset
                </select>
                @error('user_eksternal_id')
                  <span class="error">
                    {{ $message }}
                  </span>
                @enderror
              </div>
              <div class="form-group @error('judul_pengajuan_kegiatan') has-error @enderror col-md-12">
                <label for="judul_pengajuan_kegiatan">Judul Pengajuan Kegiatan <span class="text-danger">*</span></label>
                <textarea class="form-control" id="judul_pengajuan_kegiatan" name="judul_pengajuan_kegiatan" rows="3"
                  placeholder="Judul Pengajuan Kegiatan Maks 500 Huruf">{{ old('judul_pengajuan_kegiatan') }}</textarea>
                @error('judul_pengajuan_kegiatan')
                  <span class="error">
                    {{ $message }}
                  </span>
                @enderror
              </div>
              <div class="form-group @error('alamat_kegiatan') has-error @enderror col-md-12">
                <label for="alamat_kegiatan">Judul Pengajuan Kegiatan <span class="text-danger">*</span></label>
                <textarea class="form-control" id="alamat_kegiatan" name="alamat_kegiatan" rows="3" placeholder="Alamat Kegiatan">{{ old('alamat_kegiatan') }}</textarea>
                @error('alamat_kegiatan')
                  <span class="error">
                    {{ $message }}
                  </span>
                @enderror
              </div>
              <div class="form-group @error('alamat_kegiatan') has-error @enderror col-md-6">
                <label for="alamat_kegiatan">Proposal Pengajuan Kegiatan <span class="text-danger">*</span></label>
                <textarea class="form-control" id="alamat_kegiatan" name="alamat_kegiatan" rows="3" placeholder="Alamat Kegiatan">{{ old('alamat_kegiatan') }}</textarea>
                @error('alamat_kegiatan')
                  <span class="error">
                    {{ $message }}
                  </span>
                @enderror
              </div>
              <div class="form-group @error('alamat_kegiatan') has-error @enderror col-md-6">
                <label for="alamat_kegiatan">Ruang Lingkup Pengajuan Kegiatan <span class="text-danger">*</span></label>
                <textarea class="form-control" id="alamat_kegiatan" name="alamat_kegiatan" rows="3" placeholder="Alamat Kegiatan">{{ old('alamat_kegiatan') }}</textarea>
                @error('alamat_kegiatan')
                  <span class="error">
                    {{ $message }}
                  </span>
                @enderror
              </div>
              <div class="form-group @error('deskripsi_paket_kegiatan') has-error @enderror col-md-12">
                <label for="deskripsi_paket_kegiatan">Deskripsi Pengajuan Kegiatan <span
                    class="text-danger">*</span></label>
                <textarea class="form-control" id="deskripsi_paket_kegiatan" name="deskripsi_paket_kegiatan" rows="3"
                  placeholder="Deskripsi Pengajuan Kegiatan">{{ old('deskripsi_paket_kegiatan') }}</textarea>
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
                <label for="quota_paket_kegiatan">Quota Pengajuan Kegiatan <span class="text-danger">*</span></label>
                <input type="number" min=0 class="form-control" id="quota_paket_kegiatan" name="quota_paket_kegiatan"
                  value="{{ old('quota_paket_kegiatan') }}">
                @error('quota_paket_kegiatan')
                  <span class="error">
                    {{ $message }}
                  </span>
                @enderror
              </div>
              <div class="form-group @error('pagu_paket_kegiatan') has-error @enderror col-md-3">
                <label for="pagu_paket_kegiatan">Pagu Pengajuan Kegiatan (Rp) <span class="text-danger">*</span></label>
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
                <input type="number" min=0 class="form-control" id="tahap_pencairan_paket_kegiatan"
                  name="tahap_pencairan_paket_kegiatan" value="{{ old('tahap_pencairan_paket_kegiatan') }}"
                  onkeyup="generateFormTahapSalur()">
                @error('tahap_pencairan_paket_kegiatan')
                  <span class="error">
                    {{ $message }}
                  </span>
                @enderror
              </div>
            </div>
            <div class="row hidden" id="dynamicForm">
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

                    </tbody>
                  </table>
                </div>
              </div>

            </div>
            <div class="row">
              <button type="submit" class="btn btn-primary waves-effect waves-light">Simpan</button>
              <a href="{{ route('paket-kegiatan.index') }}"
                class="btn btn-inverse waves-effect waves-light">Kembali</a>
            </div>
          </form>
        </div><!-- panel-body -->
      </div> <!-- panel -->
    </div> <!-- col-->

  </div> <!-- End row -->
@endsection
