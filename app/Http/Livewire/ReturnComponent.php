<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use App\Models\User;
use Livewire\Component;
use App\Models\Requisition;
use Livewire\WithPagination;
use App\Models\RequisitionDetail;
use App\Models\RequisitionReturn;
use Illuminate\Support\Facades\DB;
use App\Models\RequisitionReturnDetail;
use App\Services\SageService;

class ReturnComponent extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public $order_by = 'date';
    public $order_asc = true;
    public $per_page = 7;

    public $returnArray = [];
    public $requisition_no, $date, $returned_by, $notes, $selectedRequisition, $returnByUserId, $return_id;

    public function render()
    {
        $returns = RequisitionReturn::search($this->search)->orderBy($this->order_by, $this->order_asc ? 'asc' : 'desc')->paginate($this->per_page);;
        $date_from = date('Y-m-01', strtotime(Carbon::now()));
        $date_to = date('Y-m-t', strtotime(Carbon::now()));
        $this->date = Carbon::now()->format('Y-m-d');
        $requisitions = Requisition::where('date', '>=', $date_from)->where('date', '<=', $date_to)->orderBy('created_at', 'desc')->get();
        return view('livewire.return-component', ['returns' => $returns, 'requisitions' => $requisitions]);
    }
    public function clearFields()
    {
        $this->reset(['returnArray', 'requisition_no', 'date', 'returned_by', 'notes', 'selectedRequisition', 'return_id']);
    }
    public function addReturnLine()
    {
        $this->returnArray[] = [
            'item_code' => '',
            'description' => '',
            'quantity' => '',
            'notes' => 'N/A'
        ];
    }
    public function removeReturn($index)
    {
        unset($this->returnArray[$index]);
        $this->returnArray = array_values($this->returnArray);
    }
    public function changeRequisition()
    {
        $this->returnArray = [];
        $requi = Requisition::findOrFail($this->requisition_no);
        $this->selectedRequisition = $requi->requisition_details;
        $this->returned_by = $requi->user->name;
        $this->returnByUserId = $requi->requested_by;
    }
    public function changeSelectedItem($index)
    {
        $oldArray = $this->returnArray;
        $requisitionDetail = RequisitionDetail::where('requisition_id', $this->requisition_no)->where('item_id', $oldArray[$index]['item_code'])->first();
        $this->returnArray[$index] = [
            'item_code' => $oldArray[$index]['item_code'],
            'description' => $requisitionDetail->description,
            'record_id' => $requisitionDetail->item_id,
            'item_cod' => $requisitionDetail->item_code,
            'unit' => $requisitionDetail->unit,
            'transaction_id' => $requisitionDetail->transaction_id,
            'available_qty' => $requisitionDetail->available_qty,
            'cost' => $requisitionDetail->cost,
            'notes' => $oldArray[$index]['notes']
        ];
    }
    public function save()
    {
        $this->validate(['date' => 'required']);
        if (count($this->returnArray) == 0) {
            session()->flash('error', 'You must add atleast 1 item');
            return;
        } else if (!$this->checkAllAddedLinesHaveItem()) {
            session()->flash('error', 'One of the line added is blank. Remove or select item');
            return;
        } else {
            DB::beginTransaction();
            $return = RequisitionReturn::create([
                'requistion_number' => 'PWREQ' . $this->requisition_no, 'date' => $this->date, 'returned_by' => $this->returnByUserId,
                'notes' => $this->notes
            ]);
            $returnDetails = RequisitionReturnDetail::insert($this->returnDetails($return->id));

            if ($return && $returnDetails) {
                DB::commit();
                SageService::pushReturnsToSage($return->id);
                session()->flash('success', 'Return record created successfully');
            } else {
                DB::rollBack();
                session()->flash('error', 'Unable to create return');
            }
            $this->emit('closeReturnModal');
            $this->clearFields();
        }
    }
    public function checkAllAddedLinesHaveItem()
    {
        $results = [];
        foreach ($this->returnArray as $item) {
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
    public function returnDetails($ret_id)
    {
        $results = [];
        $date = Carbon::now();
        foreach ($this->returnArray as $item) {
            $results[] = [
                'requisition_return_id' => $ret_id,
                'item_code' => $item['item_cod'],
                'item_id' => $item['record_id'],
                'description' => $item['description'],
                'unit' => $item['unit'],
                'transaction_id' => $item['transaction_id'],
                'available_qty' => $item['available_qty'],
                'returned_qty' => $item['quantity'],
                'cost' => $item['cost'],
                'notes' => $item['notes'],
                'created_at' => $date,
                'updated_at' => $date
            ];
        }
        return $results;
    }
    public function openRequisition($id)
    {
        $this->clearFields();
        $ret = RequisitionReturn::findOrFail($id);
        $this->return_id = $ret->id;
        $this->requisition_no = substr($ret->requistion_number, 5);
        $this->date = $ret->date;
        $this->notes = $ret->notes;
        $this->returned_by = User::user_name($ret->returned_by)->name;
        foreach ($ret->requisition_return_details as $return) {
            $this->returnArray[] = [
                'requisition_return_id' => $return->id,
                'record_id' => $return->item_id,
                'item_code' => $return->item_id,
                'item_cod' => $return->item_code,
                'description' => $return->description,
                'unit' => $return->unit,
                'available' => $return->available_qty,
                'quantity' => $return->returned_qty,
                'cost' => $return->cost,
                'notes' => $return->notes,
            ];
        }
    }
}
