<div class="row">
    <div class="col-md-12">
        @if (session()->has('error'))
            <div class="alert alert-warning">
                {{ session('error') }}
            </div>
        @endif
        @if (session()->has('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
    </div>
</div>
