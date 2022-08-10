<?php

namespace App\Http\Controllers;

use App\Models\Requisition;
use Barryvdh\DomPDF\PDF;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function requisitionReport()
    {
        $req_id=request()->input('requisition_id');
        $requisition=Requisition::where('id',$req_id)->with('requisition_details')->first();
        $pdf = app('dompdf.wrapper');
        $pdf->loadView('reports.requisition', compact('requisition'))->setPaper('a4', 'potrait');
        // return $pdf->stream($requisition->requisition_number.' Requisition.pdf', array( 'Attachment'=>0 ));
        return $pdf->download('Requisition '.$requisition->requistion_number.'.pdf');

    }
}
