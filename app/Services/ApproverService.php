<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\RequisitionApprovalTracking;

class ApproverService
{
    // Create Approvers record when creating requisition
    public static function approvalTracking($requestId)
    {
        $approvers = User::where('is_approver', 1)->get(['id', 'approval_level']);
        $trackingData = [];
        $date = Carbon::now();
        if (count($approvers) > 0) {
            foreach ($approvers as $approver) {
                $trackingData[] = [
                    'requisition_id' => $requestId,
                    'approver_id' => $approver->id,
                    'approval_level' => $approver->approval_level,
                    'role' => 'Approver',
                    'status' => 0,
                    'created_at' => $date,
                    'updated_at' => $date
                ];
            }
            $approverTracking = RequisitionApprovalTracking::insert($trackingData);
            Log::info($approverTracking . " approvers created for request id " . $requestId);
        }
        Log::info("Approvers have not been set");
    }
    public static function checkPreviousApprover($requestId)
    {
        $req = RequisitionApprovalTracking::where('requisition_id', $requestId)->where('status', 0)->where('role', 'Approver')->orderBy('approval_level', 'asc')->first();
        if (!is_null($req)) {
            if ($req->approver_id == auth()->user()->id)
                return true;
            else
                return false;
        }
        else
        return false;
    }
    public static function checkIfApprovedByAllApprovers($requestId)
    {
        $req = RequisitionApprovalTracking::where('requisition_id', $requestId)->where('role', 'Approver')->where('status', 0)->count();
        if ($req > 0)
            return false;
        else return true;
    }
}
