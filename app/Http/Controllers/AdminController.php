<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\User;
use App\Models\TransactionItem;
use App\Models\Drug;
use App\Models\Report;

class AdminController extends Controller
{
    //to view admin dashboard
    public function index(){
        $drugs = Drug::all();
        $report = Report::whereDate('report_date', today())->first();
        return view("admin.dashboard", compact('drugs', 'report'));
    }

    // Show form to create a new drug
    public function create()
    {
        return view('admin.create_drug');
    }

    // Store new drug
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'stock' => 'required|integer|min:0',
            'unit_price' => 'required|numeric|min:0',
            'purchase_price' => 'required|numeric|min:0',
        ]);

        Drug::create($request->all());

        return redirect()->route('admin.dashboard')->with('success', 'Drug added successfully.');
    }

    // Show form to edit a drug
    public function edit($id)
    {
        $drug = Drug::findOrFail($id);
        return view('admin.edit_drug', compact('drug'));
    }

    // Update drug
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'stock' => 'required|integer|min:0',
            'unit_price' => 'required|numeric|min:0',
            'purchase_price' => 'required|numeric|min:0',
        ]);

        $drug = Drug::findOrFail($id);
        $drug->update($request->all());

        return redirect()->route('admin.dashboard')->with('success', 'Drug updated successfully.');
    }

    // Delete drug
    public function destroy($id)
    {
        $drug = Drug::findOrFail($id);
        $drug->delete();

        return redirect()->route('admin.dashboard')->with('success', 'Drug deleted successfully.');
    }

    //see transaction
    public function sale()
    {
        //return view ('admin.sales', compact('transaction'));
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

    return view("admin.sales", compact('drugs', 'transactions', 'report'));
    }
}
