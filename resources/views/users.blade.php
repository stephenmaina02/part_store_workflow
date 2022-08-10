@extends('layouts.default')
@section('title', 'Users')
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">
        @livewire('user-component')
    </div>
    <!-- /.container-fluid -->
@endsection
@section('scripts')
    <script>
        window.livewire.on('closeUserModal', () => {
            $('#userModal').modal('hide');
        });
        window.livewire.on('openUserModal', () => {
            $('#userModal').modal('show');
        });
    </script>
@endsection
