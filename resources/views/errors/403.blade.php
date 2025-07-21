@extends('layouts.guest')

@section('content')
    <div class="ex-page-content text-center">
        <h1>403!</h1>
        <h2>Sorry, you not allowed</h2>
        <br />
        <br />
        <a class="btn btn-purple waves-effect waves-light" href="{{ route('home') }}"><i class="fa fa-angle-left"></i> Back to
            Home</a>
    </div>
@endsection
