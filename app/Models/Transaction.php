<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    //
    use HasFactory;

    protected $fillable = ['date', 'total', 'total_profit'];

    public function items()
    {
        return $this->hasMany(TransactionItem::class);
    }
}
