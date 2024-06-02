
@extends('layouts.app')

@section('content')

<div class="background">
    <div class="shape"></div>
    <div class="shape"></div>
</div>
<form method="POST" action="{{ route('login') }}">
                        @csrf
    <div class="header">
        <div class="logo-container">
            <img src="/image/icon.jpg" alt="Logo" class="logo">
        </div>
        <div class="title-container">
            <h3 class="h3">@lang('login.login')</h3>
        </div>
    </div>
   


    <div class="form-group">
        <label for="email">Email:</label>
        <div class="input-group">
            <div class="input-group-prepend">
             <span class="input-group-text"><i class="fas fa-envelope"></i></span>
            </div>
            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
            @error('email')
            <div class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </div>
            @enderror
        </div>
    </div>

    <div class="form-group">
        <label for="password">Password:</label>
        <div class="input-group">
            <div class="input-group-prepend">
             <span class="input-group-text"><i class="fas fa-lock"></i></span>
            </div>
            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
            @error('password')
            <div class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </div>
            @enderror
        </div>
    </div>


        <button>Log In</button>
    @if (Route::has('password.request'))
                                    <a class="password-link" href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                @endif
    <!-- <div class="social">
        <div class="go"><i class="fab fa-google"></i> Google</div>
        <div class="fb"><i class="fab fa-register"> <a href="{{ route('register') }}"> @lang('login.register') </a> </i></div>
    </div> -->
</form>
@endsection
