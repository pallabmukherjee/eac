<?php

namespace App\Http\Controllers\Account\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WebsiteSetting;
use App\Models\JournalVoucher;
use App\Models\JvLedgerHead;
use App\Models\DetailedHead;
use PDF;

class JournalSummaryController extends Controller {
    public function index(){
        $title = "Journal Summary";
        $url = route('superadmin.account.report.journalSummaryReport');
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

        $data = JournalVoucher::whereBetween('date', [$fromDate, $toDate])->get();

        if ($data->isNotEmpty()) {
            // Loop through the JournalVoucher data and get related JvLedgerHead data
            foreach ($data as $voucher) {
                $voucher->ledgerHeads = JvLedgerHead::where('voucher_id', $voucher->voucher_id)->with('ledgerHead')->get();
            }

            // Generate PDF using the data
            $pdf = PDF::loadView('layouts.pages.account.report.pdf.journal-summary-pdf', compact('data', 'fromDate', 'toDate', 'website'))
                      ->setOption('margin-top', 0)
                      ->setOption('margin-left', 0)
                      ->setOption('margin-right', 0)
                      ->setOption('margin-bottom', 0);

            // Return the PDF as a download (or view in a new tab)
            return $pdf->stream('journal-summary-report.pdf');
        } else {
            // If no data found, return an error or empty view
            return back()->with('error', 'No data found for the selected date range.');
        }
    }
}
