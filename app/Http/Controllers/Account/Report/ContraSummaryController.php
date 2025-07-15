<?php

namespace App\Http\Controllers\Account\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WebsiteSetting;
use App\Models\ContraVoucher;
use App\Models\DetailedHead;
use PDF;

class ContraSummaryController extends Controller {
    public function index(){
        $title = "Contra Summary";
        $url = route('superadmin.account.report.contraSummaryReport');
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

        // Query the ContraVoucher model for data within the specified date range
        $data = ContraVoucher::whereBetween('date', [$fromDate, $toDate])->with(['fromBank', 'toBank'])->get()->groupBy('voucher_id');

        // If there is data, generate the PDF
        if ($data->isNotEmpty()) {
            // Generate PDF using the data
            $pdf = PDF::loadView('layouts.pages.account.report.pdf.contra-summary-pdf', compact('data', 'fromDate', 'toDate', 'website'))
                  ->setOption('margin-top', 0)
                  ->setOption('margin-left', 0)
                  ->setOption('margin-right', 0)
                  ->setOption('margin-bottom', 0);

            // Return the PDF as a download (or view in a new tab)
            return $pdf->stream('contra-summary-report.pdf');
        } else {
            // If no data found, return an error or empty view
            return back()->with('error', 'No data found for the selected date range.');
        }
    }
}
