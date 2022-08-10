@extends('layouts.default')
@section('title', 'Requisitions')
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">
        @livewire('requisition-component')
    </div>
    <!-- /.container-fluid -->
@endsection
@section('scripts')
    <script>
        window.livewire.on('closeRequisitionModal', () => {
            $('#requisitionModal').modal('hide');
        });
        window.livewire.on('openRequisitionModal', () => {
            $('#requisitionModal').modal('show');
        });
    </script>
@endsection
