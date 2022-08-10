<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Requisition;
use Livewire\WithPagination;
use App\Services\SageService;
use App\Models\RequisitionDetail;
use App\Services\ApproverService;
use App\Services\NotificationService;
use App\Models\RequisitionApprovalTracking;

class RequisitionApprovalComponent extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $filter = 0;
    public $order_by = 'requisition_id';
    public $order_asc = true;
    public $per_page = 7;
    public $requisitionArray = [];
    public $requisition_no, $date, $requested_by, $requisition_id;

    public function openApprovalForm($id)
    {
        $this->clearFields();
        $reqs = Requisition::findOrFail($id);
        $this->requisition_no = $reqs->requistion_number;
        $this->date = $reqs->date;
        $this->requested_by = $reqs->user->name;
        $this->requisition_id = $reqs->id;
        foreach ($reqs->requisition_details as $reqline) {
            $this->requisitionArray[] = [
                'record_id' => $reqline->id,
                'item_code' => $reqline->item_code,
                'description' => $reqline->description,
                'unit' => $reqline->unit,
                'available' => $reqline->available_qty,
                'quantity' => $reqline->request_qty,
                'unit_price' => $reqline->cost,
                'status' => $reqline->status,
                'notes' => $reqline->notes,
                'is_returnable' => $reqline->is_returnable
            ];
        }
    }
    public function submitApproval()
    {
        $line_aproved = [];
        $line_rejected = [];
        $line_request = [];
        foreach ($this->requisitionArray as $reqline) {
            if ($reqline['status'] == 'Approved')
                array_push($line_aproved, $reqline['status']);
            if ($reqline['status'] == 'Rejected')
                array_push($line_rejected, $reqline['status']);
            if ($reqline['status'] == 'Request')
                array_push($line_request, $reqline['status']);
            RequisitionDetail::where('id', $reqline['record_id'])->first()
                ->update([
                    'item_code' => $reqline['item_code'], 'approved_qty' => $reqline['quantity'], 'notes' => $reqline['notes'],
                    'status' => $reqline['status'], 'is_returnable' => $reqline['is_returnable']
                ]);
        }
        $requisition_status = '';
        if (count($line_aproved) == count($this->requisitionArray))
            $requisition_status = 'Approved';
        elseif (count($line_rejected) == count($this->requisitionArray))
            $requisition_status = 'Rejected';
        elseif (count($line_request) == count($this->requisitionArray))
            $requisition_status = 'Request';
        else
            $requisition_status = 'Partially Approved';
        Requisition::where('id', $this->requisition_id)->first()->update(['status' => $requisition_status]);
        $requisitionApproved = RequisitionApprovalTracking::where('requisition_id', $this->requisition_id)->where('approver_id', auth()->user()->id)
            ->where('role', 'Approver')->first();
        $requisitionApproved->status = 1;
        $requisitionApproved->save();
        NotificationService::nextApprover($this->requisition_id);
        NotificationService::rejectedItems($this->requisition_id);
        $this->emit('closeRequisitionApprovalModal');
        $this->clearFields();
        session()->flash('success', 'Approval submitted successfully');
    }
    public function clearFields()
    {
        $this->reset(['requisitionArray', 'requisition_no', 'date', 'requested_by', 'requisition_id']);
    }
    public function render()
    {
        $items = SageService::sageItems();
        $requisitions = RequisitionApprovalTracking::where('approver_id', auth()->user()->id)
            ->where('role', 'Approver')->where('status', $this->filter)->with('requisition')
            ->orderBy($this->order_by, $this->order_asc ? 'asc' : 'desc')
            ->paginate($this->per_page);;
        return view('livewire.requisition-approval-component', ['requisitions' => $requisitions, 'items' => $items]);
    }
}
