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

    <div class="row port">
        <div class="portfolioContainer">
            @if ($data->data->flag > 0)
                <div class="col-sm-6 col-lg-3 col-md-4 webdesign illustrator">
                    <div class="gal-detail thumb">
                        <a href="{{ route('export-proposal', $data->data->id) }}" class="image-popup" title="Screenshot-1"
                            target="_BLANK">
                            <img src="{{ asset('template/images/gallery/1.jpg') }}" class="thumb-img"
                                alt="work-thumbnail" />
                            <h4>Proposal Kegiatan</h4>
                        </a>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3 col-md-4 webdesign illustrator">
                    <div class="gal-detail thumb">
                        <a href="{{ route('export-proposal', $data->data->id) }}" class="image-popup" title="Screenshot-1"
                            target="_BLANK">
                            <img src="{{ asset('template/images/gallery/1.jpg') }}" class="thumb-img"
                                alt="work-thumbnail" />
                            <h4>RAB</h4>
                        </a>
                    </div>
                </div>
            @endif
            @forelse ($data->data->document as $item)
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

            @forelse ($data->data->log_tahapan_pengajuan()->whereHas('tahapan_pengajuan_kegiatan', function ($q) {
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
