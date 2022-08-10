<?php

namespace App\Models;

use App\Models\RequisitionReturn;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RequisitionReturnDetail extends Model
{
    use HasFactory;
    protected $guarded=[];

    public function requisition_return()
    {
        return $this->belongsTo(RequisitionReturn::class);
    }
}
