<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Requisition;
use Illuminate\Support\Facades\Log;
use App\Models\RequisitionNotification;
use Illuminate\Support\Facades\Notification;
use App\Notifications\RequisitionCreatedNotification;
use App\Notifications\RequisitionApprovalNotification;
use App\Notifications\RequisitionApprovedNotification;
use App\Notifications\RequisitionRejectionNotification;

class NotificationController extends Controller
{
    public function sendMailNotification()
    {
        $notifications = RequisitionNotification::where('status', 0)->get();
        if (count($notifications) > 0) {
            foreach ($notifications as $notify) {
                if ($notify->role == 'Requester') {
                    $this->requisitionCreateNotify($notify->user_to_notify, $notify->requisition_number, $notify->id);
                }
                if ($notify->role == 'Approver') {
                    $this->sendNotificationToApprover($notify->user_to_notify, $notify->requisition_number, $notify->id);
                }
                if ($notify->role == 'Approved') {
                    // $requis=Requisition::findOrFail(substr($notify->requisition_number, 5));
                    $this->requestCreatorNotifyAfterApproval($notify->user_to_notify, $notify->requisition_number, $notify->id);
                }
                if ($notify->role == 'Reject') {
                    $this->requestCreatorNotifyAfterReject(
                        $notify->user_to_notify,
                        $notify->requisition_number,
                        $notify->id,
                        $notify->item_code,
                        $notify->requested_qty,
                        $notify->comment
                    );
                }
            }
        }
    }
    public function sendNotificationToApprover($approver, $requestID, $notify_id)
    {
        $user = User::where('id', $approver)->first();
        if (!is_null($approver)) {
            $reqData = $this->approvalMessage($user->name, $requestID);
            Notification::send($user, new RequisitionApprovalNotification($reqData));
            Log::info('Mail notification for requistion no:' . $requestID . ' sent to ' . $user->email . ' for approval');
            RequisitionNotification::findOrFail($notify_id)->update(['status' => 1]);
        } else {
            Log::info($requestID . 'Fully Approved');
        }
    }
    public function requisitionCreateNotify($requestedById, $requestNumber, $notify_id)
    {
        $user = User::where('id', $requestedById)->first();
        $reqData = [
            'greeting' => 'Hi ' . $user->name . ',',
            'body' => 'Please note your requisiton with requisition number ' . $requestNumber . ' has been submitted successfully. You will be notified after approval',
            'thanks' => 'Thank you for using our application!',
            'requisition_number' => $requestNumber
        ];
        Notification::send($user, new RequisitionCreatedNotification($reqData));
        Log::info('Mail notification for requistion no: ' . $requestNumber . ' sent to ' . $user->email . ' successfully');
        RequisitionNotification::findOrFail($notify_id)->update(['status' => 1]);
    }
    public function requestCreatorNotifyAfterApproval($requestedById, $requestNumber, $notify_id)
    {
        $user = User::where('id', $requestedById)->first();
        $reqData = [
            'greeting' => 'Hi ' . $user->name . ',',
            'body' => 'Please note your requisiton with requisition number ' . $requestNumber . ' has been Approved. Please proceed to confirm the item issued',
            'thanks' => 'Thank you for using our application!',
            'requisition_number' => $requestNumber
        ];
        Notification::send($user, new RequisitionApprovedNotification($reqData));
        Log::info('Mail notification for requistion no: ' . $requestNumber . ' sent to ' . $user->email . ' successfully after approval');
        RequisitionNotification::findOrFail($notify_id)->update(['status' => 1]);
    }
    public function requestCreatorNotifyAfterReject($requestedById, $requestNumber, $notify_id, $item_code, $requested_qty, $comment)
    {
        $user = User::where('id', $requestedById)->first();
        $reqData = [
            'greeting' => 'Hi ' . $user->name . ',',
            'body' => 'Please note your requisiton with requisition number ' . $requestNumber . ' has been rejected for Item: ' . $item_code . '.
            Requested Quantity ' . $requested_qty . ' and rejection comment is ' . $comment,
            'thanks' => 'Thank you for using our application!',
            'requisition_number' => $requestNumber
        ];
        Notification::send($user, new RequisitionRejectionNotification($reqData));
        Log::info('Mail notification for requistion no: ' . $requestNumber . ' sent to ' . $user->email . ' successfully after approval');
        RequisitionNotification::findOrFail($notify_id)->update(['status' => 1]);
    }
    public function approvalMessage($name, $requestNumber)
    {
        return [
            'greeting' => 'Hi ' . $name . ',',
            'body' => 'Please note a new requisiton with requisition number ' . $requestNumber . ' has been submitted for approval. Kindly action on the request',
            'thanks' => 'Thank you for using our application!',
            'requisition_number' => $requestNumber
        ];
    }
    // turing Challenges
    public function UniqueNumbers($nums)
    {
        $count_results = [];
        $count_results = array_count_values($nums);

        $results = [];
        foreach ($count_results as $key => $value) {
            if ($value == 1) {
                array_push($results, $key);
            }
        }
        return $results;
    }
    public function count_vowels($string)
    {
        $vowels=['a','e','i','o','u'];
        $results=0;
        $string=strtolower($string);
        for($i=0; $i<strlen($string); $i++){
            if(in_array($string[$i], $vowels)){
                $results++;
            }
        }
        return $results;
    }
    public function test()
    {
        // dd($this->UniqueNumbers([1,2,2,3,4,3,6,4]));
        dd($this->count_vowels('mn'));
    }
}
