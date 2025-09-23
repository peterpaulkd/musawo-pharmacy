<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TransactionItem extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'drug_id',
        'quantity',
        'price',
        'subtotal',
        'purchase_price',
        'profit_per_unit',
        'total_profit',
    ];

    //to calculate profits
    protected static function booted()
    {
        static::creating(function ($item) {
            // Calculate profit per unit
            $item->profit_per_unit = $item->unit_price - $item->purchase_price;
            // Calculate total profit
            $item->total_profit = $item->profit_per_unit * $item->quantity;
        });

        // Optional: also update on update
        static::updating(function ($item) {
            $item->profit_per_unit = $item->unit_price - $item->purchase_price;
            $item->total_profit = $item->profit_per_unit * $item->quantity;
        });
    }

    public function drug()
    {
        return $this->belongsTo(Drug::class);
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}
