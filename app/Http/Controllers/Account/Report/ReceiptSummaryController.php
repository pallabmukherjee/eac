<?php

namespace App\Http\Controllers\Account\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WebsiteSetting;
use App\Models\MajorHead;
use App\Models\ReceiptVoucher;
use App\Models\RvLedgerHead;
use PDF;

class ReceiptSummaryController extends Controller
{
    public function index(){
        $title = "Receipt Summary";
        $url = route('superadmin.account.report.receiptSummaryReport');
        return view("layouts.pages.account.report.report_form", compact('title', 'url'));
    }

    public function report(Request $request) {
        // Validate the incoming date range
        $validated = $request->validate([
            'from_date' => 'required|date',
            'to_date' => 'required|date|after_or_equal:from_date',
        ]);

        $website = WebsiteSetting::first();

        $fromDate = $validated['from_date'];
        $toDate = $validated['to_date'];

        $receipts = ReceiptVoucher::whereBetween('date', [$fromDate, $toDate])->get();

        $rvLedgerHeads = RvLedgerHead::whereIn('voucher_id', $receipts->pluck('voucher_id'))->get();

        if ($rvLedgerHeads->isEmpty()) {
            return response()->json(['message' => 'No records found for the specified date range.'], 404);
        }

        $groupedData = $rvLedgerHeads->groupBy('ledger_head')->map(function ($items) {
            $ledgerHead = $items->first()->ledgerHead;
            $majorHeadCode = substr($items->first()->ledger_head, 0, 3);
            $majorHead = MajorHead::where('code', $majorHeadCode)->first();
            return [
                'ledger_head' => $items->first()->ledger_head,
                'ledger_name' => $ledgerHead ? $ledgerHead->name : 'N/A',
                'major_head_name' => $majorHead ? $majorHead->name : 'N/A',
                'count' => $items->count(),
                'total_cash_amount' => $items->sum('cash_amount'),
                'total_cheque_amount' => $items->sum('cheque_amount'),
                'total_online_amount' => $items->sum('online_amount'),
                'total_amount' => number_format($items->sum('cash_amount') + $items->sum('cheque_amount') + $items->sum('online_amount'), 2, '.', ''),
            ];
        })->values();

        $pdf = PDF::loadView('layouts.pages.account.report.pdf.receipt-summary-pdf', compact('groupedData', 'fromDate', 'toDate', 'website'))
        ->setOption('margin-top', 0)
        ->setOption('margin-left', 0)
        ->setOption('margin-right', 0)
        ->setOption('margin-bottom', 0);

        // Return the PDF as a response
        return $pdf->stream('receipt_summary.pdf');

    }
}
