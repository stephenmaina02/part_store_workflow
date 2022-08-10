<div>
    <div class="col-md-12">
        <div class="row mb-3">
            <h1 class="h3 mb-2 text-gray-800">Users</h1>
        </div>
    </div>
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="row mb-2">
                <div class="col-md-12">
                    <button class="btn btn-sm btn-primary float-right" wire:click="syncSageUser"><i
                            class="fas fa-sync"></i>
                        Sync users</button>
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
                        <option value="name">Name</option>
                        <option value="email">Email</option>
                        <option value="is_approver">Is Approver</option>
                        <option value="is_admin">Role</option>
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
            </div>
        </div>
        <div class="card-body">
            @include('livewire.inc.message')
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr class="bg-info text-white">
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Is Approver</th>
                            <th>Approval Level</th>
                            <th>Must Change Password</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->is_admin == 0 ? 'Normal User' : 'Admin' }}</td>
                                <td>{{ $user->is_approver == 0 ? 'No' : 'Yes' }}</td>
                                <td>{{ $user->approval_level == '' ? 0 : $user->approval_level }}</td>
                                <td>{{ $user->user_must_change_password == 0 ? 'Yes' : 'No' }}</td>
                                <td><button class=" btn btn-sm btn-outline-secondary" style="min-width: 70px"
                                        wire:click="openUserEditForm('{{ $user->id }}')">
                                        <span wire:target="openUserEditForm"
                                            wire:loading.class="spinner-border spinner-border-sm" role="status"
                                            aria-hidden="true"></span>
                                        <i class="fa fa-edit"></i>
                                        Edit</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="d-flex justify-content-right">
                    {!! $users->links() !!}
                </div>
            </div>
        </div>
    </div>
    <!-- User Modal -->
    <div wire:ignore.self class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form wire:submit.prevent="save">
                    <div class="modal-header">
                        <h5 class="modal-title" id="userModalLabel">User</h5>
                        <div wire:loading wire:target="save">
                            <span style="color: green; font-size: 13pt; font-weight: bold;">Updating
                                User(s)...</span>
                        </div>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="name">Name</label>
                                <input type="text" name="name" wire:model.defer="name" id="name"
                                    class="form-control">
                                @error('name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="email">Email</label>
                                <input type="email" name="email" wire:model.defer="email" id="email"
                                    class="form-control" required>
                                @error('email')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="role">User Role</label>
                                <select name="role" id="role" wire:model.defer="role" class="form-control">
                                    <option value="0">Normal User</option>
                                    <option value="1">Admin</option>
                                </select>
                                @error('role')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="is_approver">Is Approver</label>
                                <select name="is_approver" id="is_approver" wire:model="is_approver"
                                    wire:change="changeIsApprover" class="form-control">
                                    <option value="0">No</option>
                                    <option value="1">Yes</option>
                                </select>
                                @error('is_approver')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        @if ($is_approver == 1)
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label for="approver_level">Approval Level</label>
                                    <input type="number" name="approver_level" wire:model.defer="approver_level"
                                        id="approver_level" class="form-control" required>
                                    @error('approver_level')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        @endif
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="password">Password</label>
                                <input type="password" name="password" wire:model.defer="password" id="password"
                                    class="form-control">
                                @error('password')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
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
