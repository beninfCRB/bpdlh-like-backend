@extends('layouts.guest')

@section('content')
    <div class="panel panel-color panel-primary panel-pages">
        <div class="panel-heading bg-img">
            <div class="bg-overlay"></div>
            <h3 class="text-center m-t-10 text-white">Reset Password</h3>
        </div>

        <div class="panel-body">
            <form method="post" action="#" role="form" class="text-center">
                @csrf
                @if ($errors->any())
                    @foreach ($errors->all() as $error)
                        <div class="alert alert-danger alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
                                ×
                            </button>
                            <a href="#" class="alert-link">Error! </a>
                            {{ $error }}
                        </div>
                    @endforeach
                @endif
                @if (session('status'))
                    <div class="alert alert-success alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
                            ×
                        </button>
                        <a href="#" class="alert-link">Success</a>
                        {{ session('status') }}
                    </div>
                @endif
                <div class="form-group m-b-0">
                    <div class="input-group">
                        <input type="email" class="form-control input-lg" placeholder="Enter Email" required
                            name="email" />
                        <span class="input-group-btn">
                            <button type="submit" class="btn btn-lg btn-primary waves-effect waves-light">
                                Reset
                            </button>
                        </span>
                    </div>
                </div>
                <div class="form-group m-t-10">
                    <a href="{{ route('login') }}"><i class="fa fa-lock m-r-5"></i> Kembali ke halaman login</a>
                </div>
            </form>
        </div>
    </div>
@endsection
