<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\User;
use App\Models\TransactionItem;
use App\Models\Drug;
use App\Models\Report;

class NurseController extends Controller
{
    //to show the nurse dashboard
    public function index()
{
    // Recent transactions
    $transactions = Transaction::with('items.drug')
        ->orderBy('date', 'desc')
        ->take(5)
        ->get();

    // All drugs
    $drugs = Drug::all();

    // ✅ Calculate today's sales (grand total)
    $today = now()->toDateString();
    $todayTransactions = Transaction::with('items')
        ->whereDate('date', $today)
        ->get();

    $grandTotal = 0;
    foreach ($todayTransactions as $transaction) {
        foreach ($transaction->items as $item) {
            $grandTotal += $item->quantity * $item->price; 
        }
    }

    // Build a report object/array
    $report = (object)[
        'grand_total' => $grandTotal,
    ];

    return view("nurse.dashboard", compact('drugs', 'transactions', 'report'));
}


    //to show inventory
    public function inventory(){
        $drugs = Drug::all();
        $report = Report::orderBy('report_date', 'desc')->first();
        return view("nurse.inventory",compact('drugs','report'));
    }

    //transaction
    public function dayTransactions() {
    $transactions = Transaction::with('items.drug')
                    ->whereDate('created_at', now())
                    ->get();
    $drugs = Drug::all();
    return view('nurse.day_transactions', compact('transactions', 'drugs'));
}


public function storeSale(Request $request)
{
    $request->validate([
        'drug_id' => 'required|array|min:1',
        'drug_id.*' => 'required|exists:drugs,id',
        'quantity' => 'required|array|min:1',
        'quantity.*' => 'required|integer|min:1',
    ]);

    $transaction = Transaction::create(['date' => now(), 'total' => 0]);
    $total = 0;

    $drugIds = $request->input('drug_id');
    $quantities = $request->input('quantity');

    foreach ($drugIds as $index => $drugId) {
        $drug = Drug::findOrFail($drugId);
        $qty = $quantities[$index];
        $price = (int) round($drug->unit_price);
        $subtotal = $price * $qty;

        $transaction->items()->create([
            'drug_id' => $drug->id,
            'quantity' => $qty,
            'price' => $drug->unit_price,
            'subtotal' => $subtotal,
        ]);

        $drug->decrement('stock', $qty);
        $total += $subtotal;
    }

    $transaction->update(['total' => $total]);

    return redirect()->route('nurse.dashboard')
                 ->with('success', 'Sale added successfully!');

}



}