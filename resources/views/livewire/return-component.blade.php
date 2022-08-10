<div>
    <div class="row mb-3">
        <h1 class="h3 mb-2 text-gray-800">Returns</h1>
    </div>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="row mb-2">
                <div class="col-md-12">
                    <button class="btn btn-primary btn-sm float-right" wire:click="clearFields" data-toggle="modal"
                        data-target="#returnModal"><i class="fa fa-file"></i>
                        New Return</button>
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
                        <option value="date">Return Date</option>
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
                {{-- <div class="col-md-2">
                    <label for="">Filter By</label>
                    <select wire:model="filter" class="form-control">
                        <option value="Request">Request</option>
                        <option value="Approved">Approved</option>
                        <option value="Rejected">Rejected</option>
                        <option value="Partially Approved">Partially Approved</option>
                    </select>
                </div> --}}
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
                            <th>Returned By</th>
                            <th>Date Requested</th>
                            <th>Date Returned</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($returns as $ret)
                            <tr>
                                <td>{{ $ret->requistion_number }}</td>
                                <td>{{ App\Models\User::user_name($ret->returned_by)->name }}</td>
                                <td>{{ $ret->date }}</td>
                                <td>{{ date('Y-m-d', strtotime($ret->created_at)) }}</td>
                                <td>{{ $ret->status }}</td>
                                <td>
                                    <div class="d-flex justify-content-left">
                                        <button class=" btn btn-sm btn-outline-secondary mr-3"
                                            wire:click="openRequisition('{{ $ret->id }}')" data-toggle="modal"
                                            data-target="#returnModal"><i class="fa fa-eye"></i>
                                            View</button>
                                        {{-- <form action="{{ route('download-requisition-pdf') }}" method="POST">
                                            @csrf
                                            <input type="text" name="requisition_id" value="{{ $req->id }}"
                                                hidden>
                                            <button class="btn btn-sm btn-outline-success"><i
                                                    class="fa fa-download"></i> Download</button>
                                        </form> --}}
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
    <div wire:ignore.self class="modal fade" id="returnModal" tabindex="-1" aria-labelledby="returnModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <form wire:submit.prevent="save">
                    <div class="modal-header">
                        <h5 class="modal-title" id="returnModalLabel">Return</h5>
                        <div wire:loading wire:target="save">
                            <span style="color: green; font-size: 13pt; font-weight: bold;">Creating
                                Return...</span>
                        </div>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label for="requisition_no">Requisition Number</label>
                                <select name="requisition_no" wire:model="requisition_no" id="requisition_no"
                                    class="form-control" wire:change="changeRequisition">
                                    <option value=""></option>
                                    @forelse ($requisitions as $requi)
                                        <option value="{{ $requi->id }}">
                                            {{ $requi->requistion_number . ' - ' . $requi->user->name . ' (' . $requi->date . ')' }}
                                        </option>
                                    @empty
                                    @endforelse
                                </select>
                                @error('requisition_no')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label for="date">Return Date</label>
                                <input type="date" name="date" wire:model.defer="date" id="date"
                                    class="form-control" required>
                                @error('date')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label for="returned_by">Returned By</label>
                                <input type="text" name="returned_by" wire:model.defer="returned_by" readonly
                                    id="returned_by" class="form-control">
                            </div>
                            <div class="col-md-3">
                                <label for="notes">Notes</label>
                                <input type="text" name="notes" wire:model.defer="notes" id="notes"
                                    class="form-control">
                            </div>
                        </div>
                        <div class="row mb-1">
                            <div class="col-md-12">
                                @if ($return_id == '')
                                    <button type="button" class="btn btn-sm btn-primary "
                                        {{ $requisition_no == '' ? 'disabled' : '' }}
                                        wire:click.prevent="addReturnLine">
                                        <span wire:target="addReturnLine"
                                            wire:loading.class="spinner-border spinner-border-sm" role="status"
                                            aria-hidden="true"></span>
                                        <i class="fa fa-plus"></i> Add
                                        Return Item</button>
                                @endif
                            </div>
                        </div>
                        <div class="row">
                            <div class="table-responsive col-md-12">
                                <table class="table table-stripped">
                                    <thead>
                                        <tr>
                                            <th>Item code</th>
                                            <th>Description</th>
                                            <th>Quantity</th>
                                            <th>Notes</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($returnArray as $index => $ret)
                                            <tr>
                                                {{-- <input type="text" hidden
                                                    wire:model="returnArray.{{ $index }}.record_id"> --}}
                                                <td>
                                                    @if ($return_id == '')
                                                        <select wire:model="returnArray.{{ $index }}.item_code"
                                                            class="form-control custom-input-width"
                                                            wire:change="changeSelectedItem('{{ $index }}')">
                                                            <option value=""></option>
                                                            @forelse ($selectedRequisition as $item)
                                                                <option value="{{ $item->item_id }}">
                                                                    {{ $item->item_code . ' - ' . $item->description }}
                                                                </option>
                                                            @empty
                                                            @endforelse
                                                        </select>
                                                    @else
                                                        <input type="text" class="form-control custom-input-width"
                                                            value={{ $returnArray[$index]['item_cod'] . ' - ' . $returnArray[$index]['description'] }}
                                                            disabled>
                                                    @endif


                                                </td>
                                                <td>
                                                    <input type="text"
                                                        wire:model.defer="returnArray.{{ $index }}.description"
                                                        class="form-control custom-input-width" disabled>
                                                </td>
                                                <td>
                                                    <input type="number"
                                                        wire:model.defer="returnArray.{{ $index }}.quantity"
                                                        class="form-control custom-input-width" step='0.01'
                                                        required>
                                                </td>
                                                <td>
                                                    <input type="text"
                                                        wire:model.defer="returnArray.{{ $index }}.notes"
                                                        class="form-control custom-input-width" required>
                                                </td>
                                                <td>
                                                    @if ($return_id == '')
                                                        <a class="btn btn-danger btn-sm"
                                                            wire:click.prevent="removeReturn({{ $index }})"><i
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
                        @if ($return_id == '')
                            <button type="submit" class="btn btn-success btn-sm mr-2"><i class="fa fa-save"></i>
                                Submit</button>
                        @endif
                        <button type="button" class="btn btn-secondary btn-sm mr-2" data-dismiss="modal"><i
                                class="fa fa-window-close"></i> Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
