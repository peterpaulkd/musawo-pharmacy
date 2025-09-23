<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Report;
use App\Models\TransactionItem;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    // List all reports
    public function index()
    {
        $reports = Report::orderBy('report_date', 'desc')->get();
        return view('admin.reports.index', compact('reports'));
    }

    // Show a single report
    public function show(Report $report)
    {
        //$data = json_decode($report->data, true);
        $data = $report->data;
        return view('admin.reports.show', compact('report','data'));
    }

    // Download PDF
    public function download(Report $report)
    {
        $pdf = Pdf::loadView('admin.reports.pdf', compact('report'));
        return $pdf->download("report_{$report->report_date}.pdf");
    }

    // Optional: create new daily report from transactions
   public function storeFromTransactions()
{
    $today = today();

    // Get all transaction items for today
    $transactionItems = \App\Models\TransactionItem::whereHas('transaction', function($q) use ($today) {
        $q->whereDate('created_at', $today);
    })->get();

    // Group by drug
    $items = $transactionItems
        ->groupBy('drug_id')
        ->map(function($group) {
            $drug = $group->first()->drug; // same drug in group
            $totalQty = $group->sum('quantity');
            $totalAmount = $group->sum('subtotal');

            //profit benefits
            // Profit calculations
            $profitPerUnit = $drug->unit_price - $drug->purchase_price;
            $totalProfit = $profitPerUnit * $totalQty;

            return [
                'name' => $drug->name,
                'qty_sold' => $totalQty,
                'total_amount' => $totalAmount,
                'profit_per_unit' => $profitPerUnit,
                'total_profit' => $totalProfit,
            ];
        })
        ->values(); // reindex

    $grandTotal = $items->sum('total_amount');
    $totalProfit = $items->sum('total_profit');

    // Save report in daily_reports table
    $report = \App\Models\Report::updateOrCreate(
    ['report_date' => $today], // check by date
    [
        'data' => $items,
        'grand_total' => $grandTotal,
        'total_profit' => $totalProfit,
    ]
);

    return redirect()->route('admin.reports.index')
                     ->with('success', 'Daily report generated.');
}

}
