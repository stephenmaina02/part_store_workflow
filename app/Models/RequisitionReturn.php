<?php

namespace App\Models;

use App\Models\RequisitionReturnDetail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RequisitionReturn extends Model
{
    use HasFactory;
    protected $guarded=[];

    public function requisition_return_details()
    {
        return $this->hasMany(RequisitionReturnDetail::class,'requisition_return_id');
    }
    public static function search($search)
    {
        return empty($search) ? static::query()
            : static::query()->where('requistion_number', 'like', '%' . $search . '%')
            ->orWhere('date', 'like', '%' . $search . '%');
    }
}
