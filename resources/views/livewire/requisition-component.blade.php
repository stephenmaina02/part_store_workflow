<div>
    <div class="row mb-3">
        <h1 class="h3 mb-2 text-gray-800">Requisitions</h1>
    </div>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="row mb-2">
                <div class="col-md-12">
                    <button class="btn btn-primary btn-sm float-right" wire:click="clearFields" data-toggle="modal"
                        data-target="#requisitionModal"><i class="fa fa-file"></i>
                        New Requisition</button>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <label for="">Search</label>
                    <input type="text" wire:model="search" placeholder="Search" class="form-control">
                </div>
                <div class="col-md-3">
                    <label for="">Order By</label>
                    <select wire:model="order_by" class="form-control">
                        <option value="requistion_number">Requisition Number</option>
                        <option value="date">Request Date</option>
                        <option value="status">Status</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="">Order Mode</label>
                    <select wire:model="order_asc" class="form-control">
                        <option value="1">Ascending</option>
                        <option value="0">Descending</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="">Display per page</label>
                    <select wire:model="per_page" class="form-control">
                        <option value="7">7</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="">Filter By</label>
                    <select wire:model="filter" class="form-control">
                        <option value="Request">Request</option>
                        <option value="Approved">Approved</option>
                        <option value="Rejected">Rejected</option>
                        <option value="Partially Approved">Partially Approved</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    @include('livewire.inc.message')
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
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
                                <td>{{ $req->requistion_number }}</td>
                                <td>{{ $req->date }}</td>
                                <td>{{ App\Models\User::user_name($req->requested_by)->name }}</td>
                                <td>{{ $req->status }}</td>
                                <td>
                                    <div class="d-flex justify-content-left">
                                        <button class=" btn btn-sm btn-outline-secondary mr-3"
                                            wire:click="openRequisition('{{ $req->id }}')" data-toggle="modal"
                                            data-target="#requisitionModal"><i
                                                class="fa fa-eye"></i>
                                            View</button>
                                        <form action="{{ route('download-requisition-pdf') }}" method="POST">
                                            @csrf
                                            <input type="text" name="requisition_id" value="{{ $req->id }}"
                                                hidden>
                                            <button class="btn btn-sm btn-outline-success"><i
                                                    class="fa fa-download"></i> Download</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">No data to display at the moment!!!</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="d-flex justify-content-center">
                    {{-- {!! $requisitions->links() !!} --}}
                </div>
            </div>
        </div>
    </div>
    <!-- Requisition Modal -->
    <div wire:ignore.self class="modal fade" id="requisitionModal" tabindex="-1"
        aria-labelledby="requisitionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <form wire:submit.prevent="save">
                    <div class="modal-header">
                        <h5 class="modal-title" id="requisitionModalLabel">Requisition</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col-md-2">
                                <label for="requisition_no">Requisition Number</label>
                                <input type="text" name="requisition_no" wire:model.defer="requisition_no" readonly
                                    id="requisition_no" class="form-control">
                            </div>
                            <div class="col-md-2">
                                <label for="date">Request Date</label>
                                <input type="date" name="date" wire:model.defer="date" id="date"
                                    class="form-control" required {{ $requisition_id != '' ? 'disabled' : '' }}>
                                @error('date')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label for="requested_by">Requested By</label>
                                <input type="text" name="requested_by" wire:model.defer="requested_by" readonly
                                    id="requested_by" class="form-control">
                            </div>
                            @if ($requisition_id != '')
                                <div class="col-md-2">
                                    <label for="approval_status">Status</label>
                                    <input type="text" name="approval_status" wire:model.defer="approval_status"
                                        readonly id="status" class="form-control">
                                </div>
                            @endif
                            <div class="col-md-3">
                                <label for="notes">Notes</label>
                                <input type="text" name="notes" {{ $requisition_id != '' ? 'disabled' : '' }}
                                    wire:model.defer="notes" id="requested_by" class="form-control">
                            </div>
                        </div>
                        @if ($requisition_id == '')
                            <div class="row mb-1">
                                <div class="col-md-12">
                                    <button type="button" class="btn btn-sm btn-primary "
                                        wire:click.prevent="addRequisition"><i class="fa fa-plus"></i> Add
                                        Item</button>
                                </div>
                            </div>
                        @endif
                        <div class="row">
                            <div class="table-responsive col-md-12">
                                <table class="table table-stripped">
                                    <thead>
                                        <tr>
                                            <th>Item code</th>
                                            {{-- <th>Description</th> --}}
                                            {{-- <th>Unit</th>
                                            <th>Available</th> --}}
                                            <th>Quantity</th>
                                            {{-- <th>Unit Price</th> --}}
                                            @if ($requisition_id != '')
                                                <th>Approved Qty</th>
                                                <th>Issued Qty</th>
                                                <th>Status</th>
                                            @endif
                                            <th>Usage</th>
                                            @if ($requisition_id == '')
                                                <th>Action</th>
                                            @endif
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
                                                    <select
                                                        wire:model="requisitionArray.{{ $index }}.item_code"
                                                        class="form-control custom-input-width"
                                                        wire:change="change('{{ $index }}')"
                                                        {{ $requisition_id != '' ? 'disabled' : '' }}>
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
                                                        wire:model="requisitionArray.{{ $index }}.description"
                                                        class="form-control custom-input-width" readonly>
                                                </td> --}}
                                                {{-- <td>
                                                    <input type="text"
                                                        wire:model="requisitionArray.{{ $index }}.unit"
                                                        class="form-control custom-input-width" readonly>
                                                </td>
                                                <td>
                                                    <input type="text"
                                                        wire:model="requisitionArray.{{ $index }}.available"
                                                        class="form-control custom-input-width" readonly>
                                                </td> --}}
                                                <td>
                                                    <input type="number"
                                                        wire:model.defer="requisitionArray.{{ $index }}.quantity"
                                                        class="form-control custom-input-width" step='0.01'
                                                        required {{ $requisition_id != '' ? 'disabled' : '' }}>
                                                </td>
                                                {{-- <td>
                                                    <input type="text"
                                                        wire:model="requisitionArray.{{ $index }}.unit_price"
                                                        class="form-control custom-input-width" readonly>
                                                </td> --}}
                                                @if ($requisition_id != '')
                                                    <td>
                                                        <input type="number"
                                                            wire:model.defer="requisitionArray.{{ $index }}.approved_qty"
                                                            class="form-control custom-input-width" step='0.01'
                                                            disabled>
                                                    </td>
                                                    <td>
                                                        <input type="number"
                                                            wire:model.defer="requisitionArray.{{ $index }}.issued_qty"
                                                            class="form-control custom-input-width" step='0.01'
                                                            value="0.0" required>
                                                    </td>
                                                    <td>
                                                        <input type="text"
                                                            wire:model.defer="requisitionArray.{{ $index }}.status"
                                                            class="form-control custom-input-width" step='0.01'
                                                            disabled>
                                                    </td>
                                                @endif
                                                <td>
                                                    <input type="text"
                                                        wire:model.defer="requisitionArray.{{ $index }}.notes"
                                                        class="form-control custom-input-width" required
                                                        {{ $requisition_id != '' ? 'disabled' : '' }}>
                                                </td>
                                                <td>
                                                    @if ($requisition_id == '')
                                                        <a class="btn btn-danger btn-sm"
                                                            wire:click.prevent="removeEarn({{ $index }})"><i
                                                                class="fa fa-trash"></i></a>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        {{-- @if ($sage_status == 1 ) --}}
                            <button type="submit" class="btn btn-success btn-sm mr-2"><i class="fa fa-save"></i>
                                Submit</button>
                        {{-- @endif --}}
                        <button type="button" class="btn btn-secondary btn-sm mr-2" data-dismiss="modal"><i
                                class="fa fa-window-close"></i> Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
