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
                    <div class="row">
                        @forelse ($testimonials as $item)
                            <div class="col-md-6 col-sm-6 col-lg-4">
                                <div class="mini-stat clearfix bx-shadow">
                                    <span class="mini-stat-icon"><img
                                            src="{{ asset('template/images/users/avatar-1.jpg') }}" alt=""
                                            class="img-circle img-responsive" /></span>
                                    <div class="mini-stat-info text-right text-muted">
                                        <span class="name">{{ $item->data_pic_kelompok_masyarakat->nama_pic }}</span>
                                        {{ $item->data_pic_kelompok_masyarakat->kelompok_masyarakat->kelompok_masyarakat }}
                                    </div>
                                    <br />
                                    <hr class="m-t-10" />
                                    <ul class="text-center social-links list-inline m-0">
                                        <p class="text-justify">
                                            {{ Str::words($item->testimonial, '20', '...') }}
                                        </p>
                                    </ul>
                                </div>
                            </div>
                        @empty
                        @endforelse

                    </div>
                    {{ $testimonials->links() }}
                </div>
            </div>
        </div>
    </div>
    <!-- End Row -->
@endsection
