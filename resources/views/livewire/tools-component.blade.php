<div>
    <div class="col-md-12">
        <div class="row mb-3">
            <h1 class="h3 mb-2 text-gray-800">Tools</h1>
        </div>
    </div>
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="row mb-2">
                <div class="col-md-12">
                    <button class="btn btn-sm btn-primary float-right" data-toggle="modal" data-target="#toolsModal"><i
                            class="fas fa-file"></i>
                        New Tool</button>
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
                        <option value="code">Code</option>
                        <option value="description">Description</option>
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
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
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
                            <th>#</th>
                            <th>Code</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th>Tool Value</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($tools as  $tool)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $tool->code }}</td>
                                <td>{{ $tool->description }}</td>
                                <td>{{ $tool->status == 1 ? 'Active' : 'Inactive' }}</td>
                                <td>{{ $tool->value }}</td>
                                <td><button class="btn btn-sm btn-outline-secondary" style="min-width: 70px"
                                        data-toggle="modal" data-target="#toolsModal"
                                        wire:click="openToolEditForm('{{ $tool->id }}')"><i class="fa fa-edit"></i>
                                        Edit</button></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">No tools added at the moment</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="d-flex justify-content-right">
                    {!! $tools->links() !!}
                </div>
            </div>
        </div>
    </div>
    <!-- Tools Modal -->
    <div wire:ignore.self class="modal fade" id="toolsModal" tabindex="-1" aria-labelledby="toolsModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form wire:submit.prevent="save">
                    <div class="modal-header">
                        <h5 class="modal-title" id="toolsModalLabel">Tool Details</h5>
                        <div wire:loading wire:target="save">
                            <span style="color: green; font-size: 13pt; font-weight: bold;">Creating/Updating
                                tool...</span>
                        </div>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group mb-3">
                            <label for="code">Code</label>
                            <input type="text" name="code" wire:model.defer="code" id="code"
                                class="form-control text-uppercase">
                            @error('code')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="description">Description</label>
                            <input type="text" name="description" wire:model.defer="description" id="description"
                                class="form-control" required>
                            @error('description')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="status">User Role</label>
                            <select name="status" id="status" wire:model.defer="status" class="form-control">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                            @error('status')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="value">Value</label>
                            <input type="number" step="0.01" name="value" wire:model.defer="value" id="value"
                                class="form-control text-uppercase">
                            @error('value')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
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
