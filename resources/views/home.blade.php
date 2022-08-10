@extends('layouts.default')
@section('title', 'Dashboard')
@section('content')
    <div id="content">
        <!-- Begin Page Content -->
        <div class="container-fluid">
            @livewire('dashboard-component')
        </div>
        <!-- /.container-fluid -->

    </div>
@endsection
