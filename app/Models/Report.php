<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    // Table name (optional if using default "daily_reports")
    protected $table = 'daily_reports';

    // Fillable fields
    protected $fillable = [
        'report_date',
        'data',
        'grand_total',
        'total_profit',
    ];

    // Cast items JSON to array automatically
    protected $casts = [
        'data' => 'array',
        'report_date' => 'date',
    ];

    public function getItemsAttribute()
    {
        return $this->data ?? [];  // now you can use $report->items
    }
}
