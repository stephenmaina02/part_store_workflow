@extends('layouts.auth')
@section('title', 'Register')
@section('content')
    <div class="row d-flex justify-content-center">
        <div class="col-lg-10">
            <div class="p-5">
                <div class="text-center">
                    <h1 class="h4 text-gray-900 mb-4">Create an Account!</h1>
                </div>
                <form class="user" method="POST" action="{{ route('register') }}">
                    @csrf
                    <div class="form-group">
                        <input type="text" id="name" class="form-control form-control-user" placeholder="Name"
                            name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                        @error('name')
                            <span class="text-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <input type="email" id="email" class="form-control form-control-user"
                            placeholder="Email Address" name="email" value="{{ old('email') }}" required
                            autocomplete="email">
                        @error('email')
                            <span class="text-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-6 mb-3 mb-sm-0">
                            <input type="password" class="form-control form-control-user" id="password"
                                placeholder="Password" name="password" required autocomplete="new-password">
                            @error('password')
                                <span class="text-danger" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-sm-6">
                            <input type="password" class="form-control form-control-user" id="password-confirm"
                                placeholder="Repeat Password" name="password_confirmation" required
                                autocomplete="new-password">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary btn-user btn-block">
                        {{ __('Register Account') }}
                    </button>
                    <hr>
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
                    <a class="small" href="{{ route('login') }}">Already have an account? Login!</a>
                </div>
            </div>
        </div>
    </div>
@endsection
