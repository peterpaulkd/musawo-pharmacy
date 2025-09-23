<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Drug extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'name',
        'unit_price',
        'stock',
        'purchase_price',
        'profit_per_unit',
    ];

    //to calculate profit for each product
    // Auto-set profit_per_unit before saving
    protected static function booted()
    {
        static::saving(function ($drug) {
            $drug->profit_per_unit = $drug->unit_price - $drug->purchase_price;
        });
    }
}
