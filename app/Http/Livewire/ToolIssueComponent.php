<?php

namespace App\Http\Livewire;

use App\Models\Tool;
use Livewire\Component;
use App\Models\ToolIssue;
use Carbon\Carbon;

class ToolIssueComponent extends Component
{
    public $search = '';
    public $filter = 'Issued';
    public $order_by = 'tool_id';
    public $order_asc = true;
    public $per_page = 7;

    public $tool, $issued_to, $date_issued, $time_issued, $date_returned, $time_returned, $status, $comment, $tool_issue_id;

    public function rules()
    {
        return [
            'tool' => 'required',
            'issued_to' => 'required',
            'date_issued' => 'required',
        ];
    }
    public function save()
    {
        $this->validate();
        if ($this->tool_issue_id == '') {
            $toolIssued = ToolIssue::create($this->data());
            session()->flash('success', 'Tool ' . $toolIssued->tool->code . ' issued to ' . $toolIssued->issued_to . ' successfully');
        } else {
            $toolIssueToUpdate = ToolIssue::findOrFail($this->tool_issue_id);
            $toolIssueToUpdate->update($this->data());
            session()->flash('success', 'Tool ' . $toolIssueToUpdate->tool->code . ' issued to ' . $toolIssueToUpdate->issued_to . ' updated successfully');
        }

        $this->emit('closeToolIssueModal');
        $this->clearFields();
    }
    public function openToolIssueEditForm($id)
    {
        $toolIssue=ToolIssue::findOrFail($id);
        $this->tool_issue_id=$toolIssue->id;
        $this->tool=$toolIssue->tool_id;
        $this->issued_to=$toolIssue->issued_to;
        $this->date_issued=$toolIssue->date_issued;
        $this->time_issued=$toolIssue->time_issued;
        $this->date_returned=$toolIssue->date_returned;
        $this->time_returned=$toolIssue->time_returned;
        $this->status=$toolIssue->status;
        $this->comment=$toolIssue->comment;

    }
    public function clearFields()
    {
        $this->reset(['tool', 'issued_to', 'date_issued', 'time_issued', 'date_returned', 'time_returned', 'status', 'comment', 'tool_issue_id']);
        $this->date_issued = Carbon::now()->format('Y-m-d');
        $this->time_issued = Carbon::now()->format('H:i');
        $this->status = 'Issued';
    }
    public function data()
    {
        return [
            'tool_id' => $this->tool,
            'issued_to' => $this->issued_to,
            'time_issued' => $this->time_issued,
            'date_issued' => $this->date_issued,
            'date_returned' => $this->date_returned,
            'time_returned' => $this->time_returned,
            'status' => $this->status,
            'comment' => $this->comment
        ];
    }
    public function render()
    {
        $tools = Tool::where('status', 1)->get();
        $tool_issues = ToolIssue::search($this->search)->where('status', $this->filter)->orderBy($this->order_by, $this->order_asc ? 'asc' : 'desc')->paginate($this->per_page);
        return view('livewire.tool-issue-component', ['tool_issues' => $tool_issues, 'tools' => $tools]);
    }
}
