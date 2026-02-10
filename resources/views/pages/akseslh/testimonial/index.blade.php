@extends('layouts.app')

@section('title', 'Testimonial')

@section('content')
    <!-- Page-Title -->
    <div class="row">
        <div class="col-sm-12">
            <h4 class="pull-left page-title">TESTIMONIAL</h4>
            <ol class="breadcrumb pull-right">
                <li><a href="#">Testimonial</a></li>
                <li class="active">Daftar Testimonial</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Daftar Testimonial</h3>
                </div>
                <div class="panel-body">
                    <div class="row m-b-10">
                        <div class="col-md-12">
                            <form role="form" class="form-horizontal" method="POST"
                                action="{{ route('testimonial.import') }}" enctype="multipart/form-data">
                                @csrf
                                <div class="input-group m-t-10">
                                    <input type="file" id="fileImport" name="fileImport" class="form-control"
                                        placeholder="file" accept=".xls,.xlsx" />
                                    @error('fileImport')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                    <span class="input-group-btn">
                                        <button type="submit" class="btn waves-effect waves-light btn-primary">
                                            Import Testimonial
                                        </button>
                                    </span>
                                    <span class="input-group-btn">
                                        <a href="{{ route('testimonial.export') }}"
                                            class="btn waves-effect waves-light btn-success">
                                            Export Excel
                                        </a>
                                    </span>

                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="table-responsive m-t-10" data-pattern="priority-columns">
                        <table id="" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Jenis Kelompok</th>
                                    <th>Nama Kelompok</th>
                                    <th>Nama PIC</th>
                                    <th>Nomor Pengajuan</th>
                                    <th>Nilai Pengajuan</th>
                                    <th>Nilai Pencairan</th>
                                    <th>Testimonial</th>
                                    <th>Is Published</th>
                                    <th>Published Date</th>
                                    <th>Deleted at</th>
                                    <th>Created at</th>
                                    <th>Updated at</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($testimonials as $key => $item)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $item->data_pic_kelompok_masyarakat->kelompok_masyarakat->jenis->jenis_kelompok_masyarakat ?? '-' }}
                                        </td>
                                        <td>{{ $item->data_pic_kelompok_masyarakat->kelompok_masyarakat->kelompok_masyarakat ?? '-' }}
                                        </td>
                                        <td>{{ $item->data_pic_kelompok_masyarakat->nama_pic ?? '-' }}</td>
                                        <td>{{ $item->pengajuan_kegiatan->nomor_pengajuan }}</td>
                                        <td>{{ number_format(
                                            $item->pengajuan_kegiatan->rab_pengajuan_paket_kegiatans->reduce(function ($carry, $rab) {
                                                return $carry + $rab->harga_unit * $rab->qty;
                                            }, 0),
                                        ) }}
                                        </td>
                                        <td>{{ number_format($item->pengajuan_kegiatan->transaksi_penyaluran()->sum('nilai_penyaluran')) }}
                                        </td>
                                        <td>{{ $item->testimonial }}</td>
                                        <td>{{ $item->is_published ? 'Yes' : 'No' }}</td>
                                        <td>{{ $item->published_date ?? '-' }}</td>
                                        <td>{{ $item->deleted_at ?? '-' }}</td>
                                        <td>{{ $item->created_at ?? '-' }}</td>
                                        <td>{{ $item->updated_at ?? '-' }}</td>
                                        <td>
                                            {{-- <a href="{{ route('pic-kelompok-masyarakat.edit', $item->id) }}"
                                                class="btn btn-warning btn-xs">Edit</a>

                                            <button class="btn btn-danger btn-xs" data-id="'{{ $item->id }}'"
                                                onclick="deletePICKelompokMasyarakat('{{ $item->id }}')">Hapus</button> --}}
                                        </td>
                                    </tr>
                                @empty
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{ $testimonials->links() }}
                </div>
            </div>
        </div>
    </div>
    <!-- End Row -->
@endsection
