@extends('layouts.default')
@section('title', 'Update Password')
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">
        <div class="row mb-3">
            <h1 class="h3 mb-2 text-gray-800">Update Password</h1>
        </div>
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                @if (session()->has('message'))
                    <div class="alert alert-success">
                        {{ session()->get('message') }}
                    </div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form action="{{ route('update-password-on-logon') }}" method="POST">
                    @csrf
                    <div class="col-md-6 mb-3">
                        <label for="password">New Password</label>
                        <input type="password" required name="password" id="password" class="form-control">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="password">Confirm Password</label>
                        <input type="password" required name="password_confirmation" id="password_confirmation" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <button type="submit" class="btn btn-success btn-sm">Submit</button>
                        <a class="btn btn-sm btn-secondary" href="{{ route('home') }}">Go to Dashboard</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
@endsection
