<?php

namespace App\Http\Controllers\Account\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WebsiteSetting;
use App\Models\MajorHead;
use App\Models\PaymentVoucher;
use App\Models\LedgerReport;
use PDF;

class PaymentSummaryBenificiaryController extends Controller {
    public function index(){
        $title = "Payment Summary Party or Benificiary Wise";
        $url = route('superadmin.account.report.benificiaryReport');
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

        // Step 1: Retrieve PaymentVoucher records within the specified date range
        $payments = PaymentVoucher::whereBetween('date', [$fromDate, $toDate])->get();

        // Step 2: Group by payee and extract p_voucher_id values
        $groupedPayments = $payments->groupBy('payee')->map(function ($group) {
            return $group->pluck('p_voucher_id');
        });

        // Prepare data for the PDF
        $groupedData = [
            'grouped_payments' => $groupedPayments,
            'fromDate' => $fromDate,
            'toDate' => $toDate,
            'website' => $website,
        ];

        // Step 3: Generate the PDF
        $pdf = PDF::loadView('layouts.pages.account.report.pdf.benificiary-pdf', compact('groupedData', 'fromDate', 'toDate', 'website'))
            ->setOption('margin-top', 0)
            ->setOption('margin-left', 0)
            ->setOption('margin-right', 0)
            ->setOption('margin-bottom', 0);

        // Step 4: Return the PDF as a response
        return $pdf->stream('receipt_summary.pdf');
    }
}
