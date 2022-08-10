@extends('layouts.default')
@section('title', 'Not Allowed')
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Not Allowed Error Text -->
        <div class="text-center">
            <div class="error mx-auto text-danger" data-text="405">405</div>
            <p class="lead text-gray-800 mb-5">You don't have permission to view this page</p>
            <p class="text-gray-500 mb-0">It looks like you found a glitch in the matrix...</p>
            <a href="{{ route('home') }}">&larr; Back to Dashboard</a>
        </div>

    </div>
    <!-- /.container-fluid -->
@endsection
