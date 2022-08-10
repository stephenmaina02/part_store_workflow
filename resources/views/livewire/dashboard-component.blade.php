<div>
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
        {{-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                class="fas fa-download fa-sm text-white-50"></i> Generate Report</a> --}}
    </div>

    <!-- Content Row -->
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Requisitions</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ count($requisitions) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-briefcase fa-2x text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Approved Requisitions</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ count($requisitions->where('status', 'Approved')) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-double fa-2x text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Partially Approved Requisitions</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ count($requisitions->where('status', 'Partially Approved')) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check fa-2x text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Pending Requisitions</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ count($requisitions->where('status', 'Request')) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-comments fa-2x text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Rejected Requisitions</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ count($requisitions->where('status', 'Rejected')) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-ban fa-2x text-danger"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @if (auth()->user()->is_admin == 1)
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Totals Users</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ $users }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-users fa-2x text-success"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                    Totals Tools</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ $tools }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-tools fa-2x text-info"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
