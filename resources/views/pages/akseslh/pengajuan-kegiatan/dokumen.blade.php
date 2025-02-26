@extends('layouts.app')

@section('title', 'Dokumen Kegiatan')

@section('content')
    <!-- Page-Title -->
    <div class="row">
        <div class="col-sm-12">
            <h4 class="pull-left page-title">DOKUMEN KEGIATAN</h4>
            <ol class="breadcrumb pull-right">
                <li><a href="{{ route('pengajuan-kegiatan.index') }}">Pengajuan Kegiatan</a></li>
                <li class="active">Dokumen Kegiatan</li>
            </ol>
        </div>
    </div>

    <!-- Inline Form -->
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Tambah Dokumen</h3>
                </div>
                <div class="panel-body">
                    <form class="form-horizontal row" role="form"
                        action="{{ route('pengajuan-kegiatan.document.update', $data->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="form-group col-md-5">
                            <label class="col-sm-6 control-label">Lampiran Proposal</label>
                            <div class="col-sm-6">
                                <input type="file" placeholder="Lampiran Proposal" class="form-control" id="document"
                                    name="document" value="{{ old('document') }}" accept="application/pdf" />
                                @error('document')
                                    <span class="error">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group col-md-5">
                            <label class="col-sm-6 control-label">Dokumen Pendukung</label>
                            <div class="col-sm-6">
                                <input type="file" class="form-control" id="dokumen_pendukung" name="dokumen_pendukung"
                                    value="{{ old('dokumen_pendukung') }}" accept="application/pdf" />
                                @error('dokumen_pendukung')
                                    <span class="error">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-success waves-effect waves-light">
                                Simpan
                            </button>
                        </div>
                    </form>
                </div>
                <!-- panel-body -->
            </div>
            <!-- panel -->
        </div>
        <!-- col -->
    </div>
    <!-- End row -->
    <div class="row port">
        <div class="portfolioContainer">
            @forelse ($data->user_akseslh->data_pic_kelompok_masyarakat->foto as $item)
                @if (in_array($item->group, ['profil_kelompok', 'foto_ktp']))
                    <div class="col-sm-6 col-lg-3 col-md-4 webdesign illustrator">
                        <div class="gal-detail thumb">
                            <a href="{{ url('') . '/storage/' . $item->file_path }}" class="image-popup"
                                title="Screenshot-1" target="_BLANK">
                                <img src="{{ asset('template/images/gallery/1.jpg') }}" class="thumb-img"
                                    alt="work-thumbnail" />
                                <h4>{{ $item->group == 'document' ? 'Lampiran Proposal' : toPascalCase($item->group) }}
                                </h4>
                            </a>
                        </div>
                    </div>
                @endif
            @empty
            @endforelse
            @if ($data->flag > 0)
                <div class="col-sm-6 col-lg-3 col-md-4 webdesign illustrator">
                    <div class="gal-detail thumb">
                        <a href="{{ route('export-proposal', $data->id) }}" class="image-popup" title="Screenshot-1"
                            target="_BLANK">
                            <img src="{{ asset('template/images/gallery/1.jpg') }}" class="thumb-img"
                                alt="work-thumbnail" />
                            <h4>Proposal Kegiatan</h4>
                        </a>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3 col-md-4 webdesign illustrator">
                    <div class="gal-detail thumb">
                        <a href="{{ route('export-rab', $data->id) }}" class="image-popup" title="Screenshot-1"
                            target="_BLANK">
                            <img src="{{ asset('template/images/gallery/1.jpg') }}" class="thumb-img"
                                alt="work-thumbnail" />
                            <h4>RAB</h4>
                        </a>
                    </div>
                </div>
            @endif
            @forelse ($data->document as $item)
                <div class="col-sm-6 col-lg-3 col-md-4 webdesign illustrator">
                    <div class="gal-detail thumb">
                        <a href="{{ url('') . '/storage/' . $item->file_path }}" class="image-popup" title="Screenshot-1"
                            target="_BLANK">
                            <img src="{{ asset('template/images/gallery/1.jpg') }}" class="thumb-img"
                                alt="work-thumbnail" />
                            <h4>{{ $item->group == 'document' ? 'Lampiran Proposal' : toPascalCase($item->group) }}</h4>
                        </a>
                    </div>
                </div>
            @empty
                <div class="ex-page-content text-center">
                    <h2>Data Kosong</h2>
                </div>
            @endforelse

            @forelse ($data->log_tahapan_pengajuan()->whereHas('tahapan_pengajuan_kegiatan', function ($q) {
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    $q->where('deskripsi_kegiatan', 'Laporan Kegiatan Termin 1');
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                })->first()->document_file as $item)
                <div class="col-sm-6 col-lg-3 col-md-4 webdesign illustrator">
                    <div class="gal-detail thumb">
                        <a href="{{ url('') . '/storage/' . $item->file_path }}" class="image-popup" title="Screenshot-1"
                            target="_BLANK">
                            <img src="{{ asset('template/images/gallery/1.jpg') }}" class="thumb-img"
                                alt="work-thumbnail" />
                            <h4>{{ $item->group == 'document' ? 'Lampiran Proposal' : toPascalCase($item->group) }}</h4>
                        </a>
                    </div>
                </div>
            @empty
            @endforelse
        </div>
    </div>
    <!-- End row -->
@endsection
