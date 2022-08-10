<?php

namespace App\Models;

use App\Models\Tool;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ToolIssue extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function tool()
    {
        return $this->belongsTo(Tool::class);
    }
    public static function search($search)
    {
        return empty($search) ? static::query()
            : static::query()->where('issued_to', 'like', '%' . $search . '%')
            ->orWhere('date_returned', 'like', '%' . $search . '%')
            ->orWhere('comment', 'like', '%' . $search . '%')
            ->orWhere('date_issued', 'like', '%' . $search . '%');
    }
}
