<div>
    <div class="col-md-12">
        <div class="row mb-3">
            <h1 class="h3 mb-2 text-gray-800">Tool Issue</h1>
        </div>
    </div>
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="row mb-2">
                <div class="col-md-12">
                    <button class="btn btn-sm btn-primary float-right" wire:click="clearFields" data-toggle="modal"
                        data-target="#toolIssueModal"><i class="fas fa-hands"></i> Issue Tool</button>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <label for="">Search</label>
                    <input type="text" wire:model="search" placeholder="Search" class="form-control">
                </div>
                <div class="col-md-2">
                    <label for="">Order By</label>
                    <select wire:model="order_by" class="form-control">
                        <option value="tool_id">Tool</option>
                        <option value="issued_to">Issued To</option>
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
                <div class="col-md-3">
                    <label for="">Filter By</label>
                    <select wire:model="filter" class="form-control">
                        <option value="Issued">Issued</option>
                        <option value="Returned">Returned</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="card-body">
            @include('livewire.inc.message')
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr class="bg-info text-white">
                            <th>Tool</th>
                            <th>Issued To</th>
                            <th>Date Issued</th>
                            <th>Date Returned</th>
                            <th>Status</th>
                            <th>Comment</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($tool_issues as $issue)
                            <tr>
                                <td>{{ $issue->tool->code }}</td>
                                <td>{{ $issue->issued_to }}</td>
                                <td>{{ $issue->date_issued . ' ' . $issue->time_issued }}</td>
                                <td>{{ $issue->date_returned . ' ' . $issue->time_returned }}
                                </td>
                                <td>{{ $issue->status }}</td>
                                <td>{{ $issue->comment }}</td>
                                <td><button class="btn btn-sm btn-outline-secondary" style="min-width: 70px"
                                    wire:click="openToolIssueEditForm('{{ $issue->id }}')">
                                    <span wire:target="openToolIssueEditForm"
                                    wire:loading.class="spinner-border spinner-border-sm" role="status"
                                    aria-hidden="true"></span>
                                    <i class="fa fa-edit"></i>
                                    Edit</button></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">No tools issued at the moment</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="d-flex justify-content-right">
                    {!! $tool_issues->links() !!}
                </div>
            </div>
        </div>
    </div>
    <!-- Tool Issue Modal -->
    <div wire:ignore.self class="modal fade" id="toolIssueModal" tabindex="-1" aria-labelledby="toolIssueModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form wire:submit.prevent="save">
                    <div class="modal-header">
                        <h5 class="modal-title" id="toolIssueModalLabel">Tool Issue</h5>
                        <div wire:loading wire:target="save">
                            <span style="color: green; font-size: 13pt; font-weight: bold;">Creating/Updating
                                tool issued...</span>
                        </div>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="tool">Tool</label>
                                <select name="tool" id="tool" wire:model.defer="tool" class="form-control" {{ $tool_issue_id!='' ? 'disabled' : '' }}>
                                    <option value="">--Select tool--</option>
                                    @foreach ($tools as $tl)
                                        <option value="{{ $tl->id }}">
                                            {{ $tl->code . ' - ' . $tl->description }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('tool_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="issued_to">Issued To</label>
                                <input type="text" name="issued_to" wire:model.defer="issued_to" id="issued_to"
                                    class="form-control" required  {{ $tool_issue_id!='' ? 'disabled' : '' }}>
                                @error('issued_to')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="role">Date issued</label>
                                <div class="row">
                                    <div class="col-md-8">
                                        <input type="date" name="date_issued" class="form-control" id="date_issued"
                                            wire:model.defer="date_issued"  {{ $tool_issue_id!='' ? 'disabled' : '' }}>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="time" name="time_issued" class="form-control" id="time_issued"
                                            wire:model.defer="time_issued"  {{ $tool_issue_id!='' ? 'disabled' : '' }}>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="role">Date Returned</label>
                                <div class="row">
                                    <div class="col-md-8">
                                        <input type="date" name="date_returned" class="form-control"
                                            id="date_returned" wire:model.defer="date_returned">
                                    </div>
                                    <div class="col-md-4">
                                        <input type="time" name="time_returned" class="form-control"
                                            id="time_returned" wire:model.defer="time_returned">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="status">Status</label>
                                <select name="status" id="status" wire:model.defer="status"
                                    class="form-control">
                                    <option value="Issued">Issued</option>
                                    <option value="Returned">Returned</option>
                                </select>
                                @error('status')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="comment">Comment</label>
                                <input type="text" name="comment" wire:model.defer="comment" id="comment"
                                    class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="float-right">
                            <button type="submit" class="btn btn-success btn-sm mr-2"><i class="fa fa-save"></i>
                                Save</button>
                            <button type="button" class="btn btn-secondary btn-sm mr-2" data-dismiss="modal"><i
                                    class="fa fa-window-close"></i> Close</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
