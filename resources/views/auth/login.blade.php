@extends('layouts.guest')

@section('content')
<div class="panel-heading bg-img">
    <div class="bg-overlay"></div>
    <h3 class="text-center m-t-10 text-white"> Sign In to <strong>CMS</strong> </h3>
</div>


<div class="panel-body">
    <form class="form-horizontal m-t-20" action="/login" method="POST">
        @csrf
        <div class="form-group ">
            <div class="col-xs-12">
                <input class="form-control input-lg " type="text" name="username" required="" placeholder="Username">
                @error('username')
                <span class="error">
                    {{ $message }}
                </span>
                @enderror
            </div>
        </div>

        <div class="form-group">
            <div class="col-xs-12">
                <input class="form-control input-lg" type="password" name="password_new" required=""
                    placeholder="Password">
            </div>
        </div>

        <div class="form-group ">
            <div class="col-xs-12">
                <div class="checkbox checkbox-primary">
                    <input id="remember" type="checkbox" name="remember">
                    <label for="remember">
                        Remember me
                    </label>
                </div>

            </div>
        </div>

        <div class="form-group text-center m-t-40">
            <div class="col-xs-12">
                <button class="btn btn-primary btn-lg w-lg waves-effect waves-light" type="submit">Log
                    In</button>
            </div>
        </div>
    </form>
</div>
@endsection