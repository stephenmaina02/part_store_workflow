@extends('layouts.default')
@section('title', 'Profile')
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">
        <div class="row mb-3">
            <h1 class="h3 mb-2 text-gray-800">Profile</h1>
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
                <form action="{{ route('update-password') }}" method="POST">
                    @csrf
                    <div class="col-md-6 mb-3">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" value="{{ $user->email }}" disabled
                            class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="name">Name</label>
                        <input type="text" name="name" id="name" value="{{ $user->name }}" disabled
                            class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="password">Update password</label>
                        <input type="password" required name="password" id="password" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <button type="submit" class="btn btn-success btn sm">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
@endsection
