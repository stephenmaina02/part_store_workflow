<?php

namespace App\Http\Livewire;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

class UserComponent extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public $search = '';
    public $order_by = 'name';
    public $order_asc = true;
    public $per_page = 7;

    public $name, $email, $role, $is_approver, $approver_level, $password, $user_id;

    public function rules()
    {
        return [
            'name' => 'required',
            'email' => ['email', 'required', Rule::unique('users', 'email')->ignore($this->user_id)]
        ];
    }

    public function openUserEditForm($id)
    {
        $user = User::findOrFail($id);
        $this->user_id = $user->id;
        $this->email = $user->email;
        $this->name = $user->name;
        $this->role = $user->is_admin;
        $this->is_approver = $user->is_approver;
        $this->approver_level = $user->approval_level;
    }
    public function changeIsApprover()
    {
        if ($this->is_approver == 0) {
            $this->approver_level = 0;
        }
    }
    public function save()
    {
        $this->validate();
        if ($this->password == '') {
            DB::table('users')->where('id', $this->user_id)->update([
                'name' => $this->name, 'email' => $this->email, 'is_admin' => $this->role,
                'is_approver' => $this->is_approver, 'approval_level' => $this->approver_level
            ]);
        } else {
            DB::table('users')->where('id', $this->user_id)->update([
                'name' => $this->name, 'email' => $this->email, 'is_admin' => $this->role,
                'is_approver' => $this->is_approver, 'approval_level' => $this->approver_level,
                'password' => Hash::make($this->password), 'user_must_change_password' => true
            ]);
        }
        session()->flash('success', 'User ' . $this->name . ' updated successfully');
        $this->emit('closeUserModal');
        $this->reset(['user_id']);
    }
    public function syncSageUser()
    {
        $sageUsers = DB::select("SELECT CONCAT(cFirstName, ' ', cLastName) as name, cEmail email, bSysAccount as role FROM " . env('SAGE_DB_NAME') . "_rtblAgents WHERE cEmail<>''");
        $count = 0;
        if (count($sageUsers) > 0) {
            foreach ($sageUsers as $user) {
                $newUpUser = User::updateOrCreate(['email' => $user->email], [
                    'name' => $user->name, 'is_admin' => $user->role,
                    'password' => Hash::make('password'), 'user_must_change_password' => 1
                ]);
                if ($newUpUser->wasRecentlyCreated) {
                    Log::info('User ' . $newUpUser->email . ' created');
                    $count++;
                } else {
                    Log::info('User ' . $newUpUser->email . ' updated');
                    $count++;
                }
            }
        }
        session()->flash('success', $count . ' User(s) created/updated');
    }

    public function render()
    {
        // $users = User::all();
        $users = User::search($this->search)->orderBy($this->order_by, $this->order_asc ? 'asc' : 'desc')->paginate($this->per_page);
        return view('livewire.user-component', ['users' => $users]);
    }
}
