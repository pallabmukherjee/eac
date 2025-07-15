<?php

namespace App\Http\Controllers\Account\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WebsiteSetting;
use App\Models\DetailedHead;
use App\Models\PaymentVoucher;
use App\Models\LedgerReport;
use App\Models\JournalVoucher;
use App\Models\JvLedgerHead;
use PDF;

class LedgerSummaryController extends Controller {

    public function index(){
        $title = "Ledger Summary";
        $url = route('superadmin.account.report.ledgerSummaryReport');
        $detailedHead = DetailedHead::all();
        return view("layouts.pages.account.report.ledger_form", compact('title', 'url', 'detailedHead'));
    }

    public function report(Request $request) {
        $request->validate([
            'from_date' => 'required|date',
            'to_date' => 'required|date',
            'ledger' => 'required|string',
        ]);

        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');
        $ledgerCode = $request->input('ledger');

        $website = WebsiteSetting::first();

        $paymentVouchers = PaymentVoucher::where('date', '>=', $fromDate)
            ->where('date', '<=', $toDate)
            ->get();

        $pVoucherIds = $paymentVouchers->pluck('p_voucher_id')->toArray();

        $ledgerReports = LedgerReport::whereIn('voucher_id', $pVoucherIds)->where('ledger', $ledgerCode)->join('payment_vouchers', 'ledger_reports.voucher_id', '=', 'payment_vouchers.p_voucher_id')->
        select(
            'ledger_reports.voucher_id',
            'ledger_reports.ledger',
            'ledger_reports.amount',
            'ledger_reports.pay_deduct',
            'payment_vouchers.date',
            'payment_vouchers.bill_no',
        )->get();

        $journalVouchers = JournalVoucher::where('date', '>=', $fromDate)->where('date', '<=', $toDate)->get();

        $jvVoucherIds = $journalVouchers->pluck('voucher_id')->toArray();

        $jvLedgerHeads = JvLedgerHead::whereIn('jv_ledger_head.voucher_id', $jvVoucherIds)->where('ledger_head', $ledgerCode)->join('journal_vouchers', 'jv_ledger_head.voucher_id', '=', 'journal_vouchers.voucher_id')->
        select(
            'jv_ledger_head.voucher_id',
            'jv_ledger_head.ledger_head',
            'jv_ledger_head.amount',
            'jv_ledger_head.crdr',
            'journal_vouchers.date',
            'journal_vouchers.bill_no',
        )->get();

        $combinedReports = $ledgerReports->map(function ($report) {
            return [
                'type' => 'Payment',
                'voucher_id' => $report->voucher_id,
                'ledger' => $report->ledger,
                'amount' => $report->amount,
                'paytype' => $report->pay_deduct,
                'date' => $report->date,
                'bill_no' => $report->bill_no,

            ];
        })->merge($jvLedgerHeads->map(function ($report) {
            return [
                'type' => 'Journal',
                'voucher_id' => $report->voucher_id,
                'ledger' => $report->ledger_head,
                'amount' => $report->amount,
                'paytype' => $report->crdr,
                'date' => $report->date,
                'bill_no' => $report->bill_no,
            ];
        }));

        $pdf = PDF::loadView('layouts.pages.account.report.pdf.ledger-summary-pdf', compact('website','ledgerCode', 'combinedReports', 'paymentVouchers', 'ledgerReports', 'journalVouchers', 'jvLedgerHeads', 'fromDate', 'toDate'))
        ->setOption('margin-top', 0)
        ->setOption('margin-left', 0)
        ->setOption('margin-right', 0)
        ->setOption('margin-bottom', 0);

        return $pdf->stream('LEDGER_REPORT.pdf');
    }
}
