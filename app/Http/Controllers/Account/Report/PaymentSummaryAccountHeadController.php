<?php

namespace App\Http\Controllers\Account\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WebsiteSetting;
use App\Models\MajorHead;
use App\Models\DetailedHead;
use App\Models\PaymentVoucher;
use App\Models\LedgerReport;
use PDF;

class PaymentSummaryAccountHeadController extends Controller {

    public function index(){
        $title = "Payment Summary Account Head Wise";
        $url = route('superadmin.account.report.paymentSummaryAccountHeadReport');
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

        $payments = PaymentVoucher::whereBetween('date', [$fromDate, $toDate])->get();

        $rvLedgerHeads = LedgerReport::whereIn('voucher_id', $payments->pluck('p_voucher_id'))->get();

        if ($rvLedgerHeads->isEmpty()) {
            return response()->json(['message' => 'No records found for the specified date range.'], 404);
        }

        $groupedData = $rvLedgerHeads->groupBy('ledger')->map(function ($items) {
            $ledgerHead = $items->first()->ledgerHead;
            $majorHeadCode = substr($items->first()->ledger, 0, 3);
            $majorHead = MajorHead::where('code', $majorHeadCode)->first();
            $detailedHead = DetailedHead::where('ledgers_head_code', $items->first()->ledger)->first();

            $paymentAmount = $items->where('pay_deduct', 1)->sum('amount');
            $deductionAmount = $items->where('pay_deduct', 2)->sum('amount');
            return [
                'ledger_head' => $items->first()->ledger,
                'ledger_name' => $detailedHead->name,
                'major_head_name' => $majorHead ? $majorHead->name : 'N/A',
                'count' => $items->count(),
                'payment_amount' => $paymentAmount,
                'deduction_amount' => $deductionAmount,
            ];
        })->values();

        $pdf = PDF::loadView('layouts.pages.account.report.pdf.payment-summary-pdf', compact('groupedData', 'fromDate', 'toDate', 'website'))
        ->setOption('margin-top', 0)
        ->setOption('margin-left', 0)
        ->setOption('margin-right', 0)
        ->setOption('margin-bottom', 0);

        // Return the PDF as a response
        return $pdf->stream('payment_summary.pdf');

    }
}
