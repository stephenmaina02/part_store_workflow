<?php

namespace App\Services;

use App\Models\Requisition;
use App\Models\RequisitionApprovalTracking;
use App\Models\RequisitionDetail;
use App\Models\RequisitionNotification;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use App\Notifications\RequisitionCreatedNotification;

class NotificationService
{

    public static function createNotificationRecord($requestNumber, $userId, $role)
    {
        $requisition_notfication = new RequisitionNotification();
        $requisition_notfication->requisition_number = $requestNumber;
        $requisition_notfication->user_to_notify = $userId;
        $requisition_notfication->role = $role;
        if ($requisition_notfication->save()) {
            Log::info('Request ' . $requestNumber . ' saved to nofity ' . $userId . '. Role .' . $role);
        } else
            Log::warning('Unable to save notification record. Request number: ' . $requestNumber);
    }

    // get the first approver and notify
    public static function firstApprover()
    {
        $user = User::where('is_approver', 1)->orderBy('approval_level', 'asc')->first();
        if (!is_null($user)) {
            return $user->id;
        } else
            return 0;
    }
    public static function nextApprover($requestId)
    {
        $tracking = RequisitionApprovalTracking::where('requisition_id', $requestId)->where('status', 0)->where('role', 'Approver')->orderBy('approval_level', 'asc')->first();
        if (!is_null($tracking)) {
            self::createNotificationRecord('PWREQ' . $requestId, $tracking->approver_id, 'Approver');
            Log::info('Approver record for approver: ' . $tracking->approver_id . ' level: ' . $tracking->approval_level . '  for request ' . $requestId);
        } else {
            Log::warning("No more approvers found for request " . $requestId);
            $requi = Requisition::findOrFail($requestId);
            self::createNotificationRecord('PWREQ' . $requestId, $requi->requested_by, 'Approved');
            SageService::pushApprovedItemsToSageAsBatch($requestId);
        }
    }
    public static function rejectedItems($requestID)
    {
        $requisitionDetails = RequisitionDetail::where('requisition_id', $requestID)->where('status', 'Rejected')->get();
        if (!is_null($requisitionDetails)) {
            foreach ($requisitionDetails as $reqline) {
                $requisition = Requisition::findOrFail($requestID);
                $requisition_notfication = new RequisitionNotification();
                $requisition_notfication->requisition_number = $requisition->requistion_number;
                $requisition_notfication->user_to_notify = $requisition->requested_by;
                $requisition_notfication->role = 'Reject';
                $requisition_notfication->item_code = $reqline->item_code;
                $requisition_notfication->requested_qty = $reqline->request_qty;
                $requisition_notfication->comment = $reqline->notes;

                if ($requisition_notfication->save()) {
                    Log::info('Request ' . $requisition->requistion_number . ' saved to nofity ' . $requisition->requested_by . '. Role: Reject '. $reqline->item_code);
                } else
                    Log::warning('Unable to save notification record. Request number: ' . $requisition->requistion_number);
            }
        }
    }
}
