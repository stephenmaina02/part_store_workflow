<?php

namespace App\Models;

use App\Models\Requisition;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RequisitionDetail extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded=[];

    public function requisition()
    {
        return $this->belongsTo(Requisition::class, 'requisition_id');
    }
}
