<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\Requisition;
use Livewire\WithPagination;
use App\Models\RequisitionDetail;
use Illuminate\Support\Facades\DB;
use App\Models\RequisitionApprovalTracking;
use App\Services\ApproverService;
use App\Services\NotificationService;
use App\Services\SageService;

class RequisitionComponent extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $requisitionArray = [];
    public $requisition_no, $date, $requested_by, $requisition_id, $requisitionss, $notes, $sage_status, $approval_status;
    public $search = '';
    public $filter = 'Request';
    public $order_by = 'requistion_number';
    public $order_asc = true;
    public $per_page = 7;

    public function save()
    {
        $this->validate(['date' => 'required']);
        if (count($this->requisitionArray) == 0) {
            session()->flash('error', 'You must add atleast 1 item');
            return;
        } else if (!$this->checkAllAddedLinesHaveItem()) {
            session()->flash('error', 'One of the line added is blank. Remove or select item');
            return;
        } else {
            if ($this->requisition_id == '') {
                DB::beginTransaction();
                $requistion = Requisition::create(['date' => $this->date, 'requested_by' => auth()->user()->id, 'notes' => $this->notes]);
                $req_id = $requistion->id;
                $final_req_id = 'PWREQ' . $req_id;
                DB::table('requisitions')->where('id', $req_id)->update(['requistion_number' => $final_req_id]);
                $requistionDetails = RequisitionDetail::insert($this->itemDetails($req_id));
                $appTracking = RequisitionApprovalTracking::create([
                    'requisition_id' => $req_id, 'approver_id' => $requistion->requested_by,
                    'role' => 'Requester', 'status' => 0
                ]);
                if ($requistion && $requistionDetails && $appTracking) {
                    ApproverService::approvalTracking($req_id);
                    NotificationService::createNotificationRecord($final_req_id, auth()->user()->id, 'Requester');
                    NotificationService::createNotificationRecord($final_req_id, NotificationService::firstApprover(), 'Approver');
                    DB::commit();
                    session()->flash('success', 'Requisition created successfully');
                } else
                    DB::rollBack();
            } else {
                foreach ($this->requisitionArray as $line) {
                    RequisitionDetail::where('id', $line['record_id'])->first()
                        ->update([
                            'issued_qty' => $line['issued_qty']
                        ]);
                }
                session()->flash('success', 'Issued Quantities for requisition PWREQ'.$this->requisition_id.' updated successfully');
            }
            $this->emit('closeRequisitionModal');
            $this->clearFields();
        }
    }
    public function clearFields()
    {
        $this->reset(['requisition_id', 'requisition_no', 'date', 'requested_by', 'requisitionArray', 'notes']);
    }
    public function itemDetails($req_id)
    {
        $results = [];
        $date = Carbon::now();
        foreach ($this->requisitionArray as $item) {
            $results[] = [
                'requisition_id' => $req_id,
                'item_code' => $item['item_code'],
                'item_id' => SageService::selectedItem($item['item_code'])->stock_id,
                'description' => SageService::selectedItem($item['item_code'])->description,
                'unit' => SageService::selectedItem($item['item_code'])->unit,
                'transaction_id' => SageService::selectedItem($item['item_code'])->transaction_code,
                'available_qty' => $item['available'],
                'request_qty' => $item['quantity'],
                'cost' => $item['unit_price'],
                'notes' => $item['notes'],
                'created_at' => $date,
                'updated_at' => $date
            ];
        }
        return $results;
    }
    public function checkAllAddedLinesHaveItem()
    {
        $results = [];
        foreach ($this->requisitionArray as $item) {
            if ($item['item_code'] == '') {
                array_push($results, 'F');
            } else
                array_push($results, 'T');
        }
        if (in_array('F', $results))
            return false;
        else
            return true;
    }

    public function addRequisition()
    {
        $this->requisitionArray[] = [
            'item_code' => '',
            'description' => '',
            'unit' => '',
            'available' => '',
            'quantity' => '',
            'unit_price' => '',
            'status' => '',
            'notes' => 'N/A'
        ];
    }

    public function change($index)
    {
        $oldArray = $this->requisitionArray;
        $item_details = SageService::selectedItem($oldArray[$index]['item_code']);
        $this->requisitionArray[$index] = [
            'item_code' => $item_details->code,
            'description' => $item_details->description,
            'unit' => $item_details->unit,
            'available' => number_format($item_details->available, 2, '.', ''),
            'quantity' => '',
            'unit_price' => number_format($item_details->unit_cost, 2, '.', ''),
            'notes' => $oldArray[$index]['notes']

        ];
    }
    public function removeEarn($index)
    {
        unset($this->requisitionArray[$index]);
        $this->requisitionArray = array_values($this->requisitionArray);
    }

    public function openRequisition($req_id)
    {
        $this->clearFields();
        $reqs = Requisition::findOrFail($req_id);
        $this->requisition_no = $reqs->requistion_number;
        $this->date = $reqs->date;
        $this->requisition_id = $reqs->id;
        $this->notes = $reqs->notes;
        $this->sage_status = $reqs->sage_sync_status;
        $this->approval_status = $reqs->status;
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
                'approved_qty' => $reqline->approved_qty,
                'issued_qty' => $reqline->issued_qty
            ];
        }
        // $this->requested_by = $reqs->requested_by;
        $this->emit('openRequisitionModal');
    }
    public function render()
    {
        $this->date = Carbon::now()->format('Y-m-d');
        $this->requested_by = auth()->user()->name;
        $items = SageService::sageItems();
        if (auth()->user()->is_admin == 1) {
            $requisitions = Requisition::search($this->search)->where('status', $this->filter)->orderBy($this->order_by, $this->order_asc ? 'asc' : 'desc')->paginate($this->per_page);
        } else {
            $requisitions = Requisition::search($this->search)->where('status', $this->filter)->orderBy($this->order_by, $this->order_asc ? 'asc' : 'desc')->where('requested_by', auth()->user()->id)->paginate($this->per_page);
        }
        return view('livewire.requisition-component', ['items' => $items, 'requisitions' => $requisitions]);
    }
}
