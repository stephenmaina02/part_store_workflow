@extends('layouts.default')
@section('title', 'Returns')
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">
        @livewire('return-component')
    </div>
    <!-- /.container-fluid -->
@endsection
@section('scripts')
    <script>
        window.livewire.on('closeReturnModal', () => {
            $('#returnModal').modal('hide');
        });
        // window.livewire.on('openRequisitionModal', () => {
        //     $('#requisitionModal').modal('show');
        // });
    </script>
@endsection
