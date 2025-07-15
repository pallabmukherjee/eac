<?php

namespace App\Http\Controllers\Account\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WebsiteSetting;
use App\Models\MajorHead;
use App\Models\PaymentVoucher;
use App\Models\LedgerReport;
use App\Models\PVoucharBank;
use PDF;

class ChequeOnlineController extends Controller {
    public function index(){
        $title = "Cheque or Online Summary";
        $url = route('superadmin.account.report.chequeOnlineReport');
        return view("layouts.pages.account.report.report_form", compact('title', 'url'));
    }

    public function report(Request $request) {
        $validated = $request->validate([
            'from_date' => 'required|date',
            'to_date' => 'required|date|after_or_equal:from_date',
        ]);

        $website = WebsiteSetting::first();

        $fromDate = $validated['from_date'];
        $toDate = $validated['to_date'];

        $payments = PaymentVoucher::with(['voucherBank','beneficiary', 'ledgerReports'])->where('payment_mode', 2)->whereBetween('date', [$fromDate, $toDate])->get();


        $totalNetAmount = 0;
        foreach ($payments as $payment) {
            $payment->groupedLedgerReports = $payment->ledgerReports->groupBy('pay_deduct')->map(function ($group) {
                return $group->sum('amount');
            });

            $valuePay = $payment->groupedLedgerReports->get(1, 0);
            $valueDeduct = $payment->groupedLedgerReports->get(2, 0);

            $payment->netAmount = $valuePay - $valueDeduct;
            $totalNetAmount += $payment->netAmount;
        }

        $groupedData = [
            'grouped_payments' => $payments,
            'fromDate' => $fromDate,
            'toDate' => $toDate,
            'website' => $website,
            'totalNetAmount' => $totalNetAmount,
        ];

        $pdf = PDF::loadView('layouts.pages.account.report.pdf.chequeOnline-pdf', compact('groupedData', 'fromDate', 'toDate', 'website'))
            ->setOption('margin-top', 0)
            ->setOption('margin-left', 0)
            ->setOption('margin-right', 0)
            ->setOption('margin-bottom', 0);

        return $pdf->stream('receipt_summary.pdf');
    }
}
