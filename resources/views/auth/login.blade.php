@extends('layouts.auth')
@section('title', 'Login')
@section('content')
    <div class="row d-flex justify-content-center">
        <div class="col-lg-10">
            <div class="p-5">
                <div class="text-center">
                    <h1 class="h4 text-gray-900 mb-4">Welcome Back!</h1>
                </div>
                <form class="user" method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="form-group">
                        <input type="email" class="form-control form-control-user" id="exampleInputEmail"
                            aria-describedby="emailHelp" placeholder="Enter Email Address..." name="email"
                            value="{{ old('email') }}" required autocomplete="email" autofocus>
                        @error('email')
                            <span class="text-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control form-control-user" id="exampleInputPassword"
                            placeholder="Password" name="password" required autocomplete="current-password">
                        @error('password')
                            <span class="text-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <div class="custom-control custom-checkbox small">
                            <input type="checkbox" class="custom-control-input" name="remember" id="remember"
                                {{ old('remember') ? 'checked' : '' }}>
                            <label class="custom-control-label" for="customCheck">Remember
                                Me</label>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary btn-user btn-block">
                        {{ __('Login') }}
                    </button>
                </form>
                <hr>
                <div class="text-center">
                    @if (Route::has('password.request'))
                        <a class="small" href="{{ route('password.request') }}">
                            {{ __('Forgot Your Password?') }}
                        </a>
                    @endif
                </div>
                <div class="text-center">
                    <a class="small" href="{{ route('register') }}">Create an Account!</a>
                </div>
            </div>
        </div>
    </div>
@endsection
