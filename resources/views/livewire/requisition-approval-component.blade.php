<div>
    <div class="row mb-3">
        <div class="col-6">
            <h1 class="h3 mb-2 text-gray-800">Requisitions Approval</h1>
        </div>
        {{-- <div class="col-6">
            <button class="btn btn-info float-right" data-toggle="modal"
                data-target="#requisitionApprovalModal"><i class="fa fa-file"></i>
                New Requisition</button>
        </div> --}}
    </div>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="row">
                <div class="col-md-3">
                    <label for="">Order By</label>
                    <select wire:model="order_by" class="form-control">
                        <option value="requisition_id">Requisition Number</option>
                        <option value="created_at">Request Date</option>
                        <option value="status">Status</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="">Order Mode</label>
                    <select wire:model="order_asc" class="form-control">
                        <option value="1">Ascending</option>
                        <option value="0">Descending</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="">Display per page</label>
                    <select wire:model="per_page" class="form-control">
                        <option value="7">7</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="">Filter By</label>
                    <select wire:model="filter" class="form-control">
                        <option value="0">Request</option>
                        <option value="1">Approved/Rejected</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr class="bg-info text-white">
                            <th>Requisition Number</th>
                            <th>Date</th>
                            <th>Requested By</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($requisitions as $req)
                            <tr>
                                <td>{{ $req->requisition->requistion_number }}</td>
                                <td>{{ $req->requisition->date }}</td>
                                <td>{{ App\Models\User::user_name($req->requisition->requested_by)->name }}</td>
                                <td>{{ $req->requisition->status }}</td>
                                <td>
                                    @if (App\Services\ApproverService::checkPreviousApprover($req->requisition_id, $req->approval_level))
                                        <button class=" btn btn-sm btn-outline-secondary"
                                            wire:click="openApprovalForm('{{ $req->requisition_id }}')"
                                            data-toggle="modal" data-target="#requisitionApprovalModal"><i
                                                class="fa fa-eye"></i>
                                            View</button>
                                    @else
                                        <small>No action</small>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">No requests to display at the moment!!!</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="d-flex justify-content-center">
                    {!! $requisitions->links() !!}
                </div>
            </div>
        </div>
    </div>
    <!-- Requisition Modal -->
    <div wire:ignore.self class="modal fade" id="requisitionApprovalModal" tabindex="-1"
        aria-labelledby="requisitionApprovalModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <form wire:submit.prevent="submitApproval">
                    <div class="modal-header">
                        <h5 class="modal-title" id="requisitionApprovalModalLabel">Requisition</h5>

                        <div wire:loading wire:target="submitApproval">
                            <span style="color: green; font-size: 13pt; font-weight: bold;">Submitting
                                Approvals...</span>
                        </div>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="requisition_no">Requisition Number</label>
                                <input type="text" name="requisition_no" wire:model.defer="requisition_no" readonly
                                    id="requisition_no" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label for="date">Request Date</label>
                                <input type="date" name="date" wire:model.defer="date" id="date"
                                    class="form-control" readonly>
                                @error('date')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="requested_by">Requested By</label>
                                <input type="text" name="requested_by" wire:model.defer="requested_by" readonly
                                    id="requested_by" class="form-control">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                @if (session()->has('error'))
                                    <div class="alert alert-warning">
                                        {{ session('error') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="row">
                            <div class="table-responsive col-md-12">
                                <table class="table table-stripped">
                                    <thead>
                                        <tr>
                                            <th>Item code</th>
                                            {{-- <th>Unit</th> --}}
                                            <th>Available</th>
                                            <th>Quantity</th>
                                            <th>Usage</th>
                                            <th>Status</th>
                                            <th>Returnable</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($requisitionArray as $index => $req)
                                            <tr>
                                                @if ($requisition_id != '')
                                                    <input type="text" hidden
                                                        wire:model="requisitionArray.{{ $index }}.record_id">
                                                @endif
                                                <td>
                                                    <select wire:model="requisitionArray.{{ $index }}.item_code"
                                                        class="form-control custom-input-width" disabled>
                                                        <option value=""></option>
                                                        @foreach ($items as $item)
                                                            <option value="{{ $item->code }}">
                                                                {{ $item->code . ' - ' . $item->description }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                {{-- <td>
                                                    <input type="text"
                                                        wire:model="requisitionArray.{{ $index }}.unit"
                                                        class="form-control custom-input-width" readonly>
                                                </td> --}}
                                                <td>
                                                    <input type="text"
                                                        wire:model="requisitionArray.{{ $index }}.available"
                                                        class="form-control custom-input-width" readonly>
                                                </td>
                                                <td>
                                                    <input type="number"
                                                        wire:model.defer="requisitionArray.{{ $index }}.quantity"
                                                        class="form-control custom-input-width" step='0.01'
                                                        required>
                                                </td>
                                                <td>
                                                    <input type="text"
                                                        wire:model.defer="requisitionArray.{{ $index }}.notes"
                                                        class="form-control custom-input-width">
                                                </td>
                                                <td>
                                                    <select
                                                        wire:model.defer="requisitionArray.{{ $index }}.status"
                                                        class="form-control custom-input-width" required>
                                                        <option value="">Select Action</option>
                                                        @if ($requisition_id == '')
                                                            <option value="Request">Request</option>
                                                        @endif
                                                        <option value="Approved">Approve</option>
                                                        <option value="Rejected">Reject</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <select
                                                        wire:model.defer="requisitionArray.{{ $index }}.is_returnable"
                                                        class="form-control custom-input-width">
                                                        <option value="0">No</option>
                                                        <option value="1">Yes</option>
                                                    </select>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="float-right">
                            <button type="submit" class="btn btn-success btn-sm mr-2"><i class="fa fa-save"></i>
                                Submit</button>
                            <button type="button" class="btn btn-secondary btn-sm mr-2" data-dismiss="modal"><i
                                    class="fa fa-window-close"></i> Close</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
