<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RequisitionApprovalTracking extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded=[];

    public function requisition()
    {
        return $this->belongsTo(Requisition::class, 'requisition_id');
    }
}
