<?php

namespace App\Http\Livewire;

use App\Models\Tool;
use App\Models\User;
use Livewire\Component;
use App\Models\Requisition;

class DashboardComponent extends Component
{
    public function render()
    {
        if (auth()->user()->is_admin == 1)
            $requisitions = Requisition::get();
        else
            $requisitions = Requisition::where('requested_by', auth()->user()->id)->get();
        $users = User::all()->count();
        $tools = Tool::all()->count();

        return view('livewire.dashboard-component', [
            'requisitions' => $requisitions,
            'users' => $users,
            'tools' => $tools
        ]);
    }
}
