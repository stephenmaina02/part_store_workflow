@extends('layouts.default')
@section('title', 'Tools Management')
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">
        @livewire('tools-component')
    </div>
    <!-- /.container-fluid -->
@endsection
@section('scripts')
    <script>
        window.livewire.on('closeToolModal', () => {
            $('#toolsModal').modal('hide');
        });
        // window.livewire.on('openTaskTypeModel', () => {
        //     $('#taskTypeModal').modal('show');
        // });
    </script>
@endsection
