<?php

namespace App\Http\Livewire;

use App\Models\Tool;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;

class ToolsComponent extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public $filter = 1;
    public $order_by = 'code';
    public $order_asc = true;
    public $per_page = 7;
    public $code, $description, $tool_id, $value;
    public $status = 1;

    public function rules()
    {
        return [
            'code' => ['required', Rule::unique('tools', 'code')->ignore($this->tool_id)],
            'description' => 'required'
        ];
    }
    public function save()
    {

        $this->validate();
        if ($this->tool_id == '') {
            $newTool = new Tool();
            $newTool->code = strtoupper($this->code);
            $newTool->description = $this->description;
            $newTool->value=$this->value;
            $newTool->status = $this->status;
            $newTool->save();
            session()->flash('success', 'Tool ' . $newTool->description . ' added successfully');
        } else {
            $tool = Tool::findOrFail($this->tool_id);
            $tool->update(['code' => strtoupper($this->code), 'description' => $this->description, 'status' => $this->status, 'value'=>$this->value]);
            session()->flash('success', 'Tool ' . $tool->description . ' updated successfully');
        }
        $this->emit('closeToolModal');
        $this->clearFields();
    }
    public function openToolEditForm($id)
    {
        $tool = Tool::findOrFail($id);
        $this->code = $tool->code;
        $this->tool_id = $tool->id;
        $this->description = $tool->description;
        $this->status = $tool->status;
        $this->value=$tool->value;
        $this->emit('openToolModal');
    }
    public function clearFields()
    {
        $this->reset(['code', 'description', 'status', 'tool_id', 'value']);
    }
    public function render()
    {
        // $tools = Tool::all();
        $tools= Tool::search($this->search)->where('status', $this->filter)->orderBy($this->order_by, $this->order_asc ? 'asc' : 'desc')->paginate($this->per_page);
        return view('livewire.tools-component', ['tools' => $tools]);
    }
}
