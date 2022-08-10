<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Requisition;
use App\Models\RequisitionReturn;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class SageService
{
    public static function selectedItem($item_code)
    {
        return DB::selectOne("SELECT StockLink stock_id,  Code code, Description_1 description,
        Pack as unit, QtyAvailable available,AveUCst unit_cost, 'IS' as transaction_code  FROM " . env('SAGE_DB_NAME') . "_bvStockFull WHERE ItemActive=1 AND Code=?", [$item_code]);
    }
    public static function sageItems()
    {
        return DB::select("SELECT Code code,  Description_1 description  FROM " . env('SAGE_DB_NAME') . "_bvStockFull WHERE ItemActive=1");
    }
    public static function pushApprovedItemsToSageAsBatch($requestID)
    {
        $request = Requisition::findOrFail($requestID);
        if ($request->status == 'Approved' || $request->status == 'Partially Approved') {
            $insertStatus = [];
            $date = Carbon::now();
            DB::beginTransaction();
            foreach ($request->requisition_details as $req) {
                if ($req->status == 'Approved') {
                    $lines = DB::insert(
                        "INSERT INTO " . env('SAGE_DB_NAME') . "_etblInvJrBatchLines (iInvJrBatchID, iStockID, iWarehouseID, dTrDate, iTrCodeID,cReference,cDescription,fQtyIn, fQtyOut,fNewCost,iProjectID,bIsSerialItem,bIsLotItem,iSNGroupID,
            iJobID,iLotID, iUnitsOfMeasureStockingID,iUnitsOfMeasureCategoryID,iUnitsOfMeasureID,_etblInvJrBatchLines_iBranchID, _etblInvJrBatchLines_dCreatedDate, _etblInvJrBatchLines_dModifiedDate,
            _etblInvJrBatchLines_iCreatedBranchID,_etblInvJrBatchLines_iModifiedBranchID, _etblInvJrBatchLines_iCreatedAgentID, _etblInvJrBatchLines_iModifiedAgentID, _etblInvJrBatchLines_iChangeSetID)
            VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)",
                        [3, $req->item_id, 0, $request->date, 36, $request->requistion_number, $req->notes == 'N/A' ? 'Stock Requisition App' : $req->notes, 0, $req->approved_qty, $req->cost, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, $date, $date, 0, 0, 1, 1, 1]
                    );
                    array_push($insertStatus, $lines);
                }
            }
            if (in_array(false, $insertStatus)) {
                DB::rollBack();
                Log::error('Error inserting to sage DB');
            } else {
                DB::commit();
                $request->sage_sync_status = 1;
                $request->save();
                Log::info('Inventory Issue Submit successfully');
            }
        } else
            Log::info('Requisition with ID ' . $requestID . ' cannot be inserted to sage since its either rejected or in request status');
    }
    public static function pushReturnsToSage($id)
    {
        $return = RequisitionReturn::findOrFail($id);
        DB::beginTransaction();
        $date = Carbon::now();
        $insertStatus = [];
        foreach ($return->requisition_return_details as $ret) {
            $lines = DB::insert(
                "INSERT INTO " . env('SAGE_DB_NAME') . "_etblInvJrBatchLines (iInvJrBatchID, iStockID, iWarehouseID, dTrDate, iTrCodeID,cReference,cDescription,fQtyIn, fQtyOut,fNewCost,iProjectID,bIsSerialItem,bIsLotItem,iSNGroupID,
                iJobID,iLotID, iUnitsOfMeasureStockingID,iUnitsOfMeasureCategoryID,iUnitsOfMeasureID,_etblInvJrBatchLines_iBranchID, _etblInvJrBatchLines_dCreatedDate, _etblInvJrBatchLines_dModifiedDate,
                _etblInvJrBatchLines_iCreatedBranchID,_etblInvJrBatchLines_iModifiedBranchID, _etblInvJrBatchLines_iCreatedAgentID, _etblInvJrBatchLines_iModifiedAgentID, _etblInvJrBatchLines_iChangeSetID)
                VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)",
                [3, $ret->item_id, 0, $return->date, 36, $return->requistion_number, $ret->notes == 'N/A' ? 'Stock Requisition App Return' : $ret->notes, $ret->returned_qty, 0, $ret->cost, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, $date, $date, 0, 0, 1, 1, 1]
            );
            array_push($insertStatus, $lines);
        }
        if (in_array(false, $insertStatus)) {
            DB::rollBack();
            Log::error('Error inserting to sage DB');
        } else {
            DB::commit();
            $return->sage_sync_status = 1;
            $return->save();
            Log::info('Requisition Return Submit successfully');
        }
    }
}
