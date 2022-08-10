@extends('layouts.default')
@section('title', 'Requisitions Approval')
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">
        @livewire('requisition-approval-component')
    </div>
    <!-- /.container-fluid -->
@endsection
@section('scripts')
    <script>
        window.livewire.on('closeRequisitionApprovalModal', () => {
            $('#requisitionApprovalModal').modal('hide');
        });
        window.livewire.on('openRequisitionApprovalModal', () => {
            $('#requisitionApprovalModal').modal('show');
        });
        // window.livewire.on('loadDataTables', () => {
        //     $(document).ready(function() {
        //         $('.dataTable').DataTable();
        //     });
        // });
    </script>
@endsection
