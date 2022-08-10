<?php

namespace App\Http\Livewire;

use App\Models\RequisitionApprovalTracking;
use Livewire\Component;

class NotificationComponent extends Component
{
    public function render()
    {
        $notification = RequisitionApprovalTracking::where('approver_id', auth()->user()->id)->where('status', 0)->where('role', 'Approver')->get();
        return view('livewire.notification-component', compact('notification'));
    }
}
