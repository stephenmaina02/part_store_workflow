@extends('layouts.default')
@section('title', 'Tools Issue')
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">
        @livewire('tool-issue-component')
    </div>
    <!-- /.container-fluid -->
@endsection
@section('scripts')
    <script>
        window.livewire.on('closeToolIssueModal', () => {
            $('#toolIssueModal').modal('hide');
        });
        // window.livewire.on('openTaskTypeModel', () => {
        //     $('#taskTypeModal').modal('show');
        // });
    </script>
@endsection
