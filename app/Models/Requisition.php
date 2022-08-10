<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Requisition extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];

    public static function search($search)
    {
        return empty($search) ? static::query()
            : static::query()->where('requistion_number', 'like', '%' . $search . '%')
            ->orWhere('date', 'like', '%' . $search . '%');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }
    public function requisition_details(){
        return $this->hasMany(RequisitionDetail::class);
    }
    public function requestTracking()
    {
       return $this->hasMany(RequisitionApprovalTracking::class);
    }

}
