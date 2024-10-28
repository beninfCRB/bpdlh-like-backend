@extends('layouts.guest')

@section('content')
    <div class="panel-heading bg-img">
        <div class="bg-overlay"></div>
        <h3 class="text-center m-t-10 text-white"> Sign In to <strong>CMS</strong> </h3>
    </div>


    <div class="panel-body">
        <form class="form-horizontal m-t-20" action="{{ route('login.auth') }}" method="POST">
            @csrf
            <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response">
            <div class="form-group ">
                <div class="col-xs-12">
                    <input class="form-control input-lg " type="text" name="email" value="{{ old('email') }}"
                        required="" placeholder="Email">
                    @error('email')
                        <span class="error">
                            {{ $message }}
                        </span>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <div class="col-xs-12">
                    <input class="form-control input-lg" type="password" name="password" required=""
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

        <script src="https://www.google.com/recaptcha/api.js?render={{ env('RECAPTCHA_SITE_KEY') }}"></script>
        <script>
            grecaptcha.ready(function() {
                grecaptcha.execute('{{ env('RECAPTCHA_SITE_KEY') }}', {
                    action: 'submit'
                }).then(function(token) {
                    document.getElementById('g-recaptcha-response').value = token;
                });
            });
        </script>
    </div>
@endsection
