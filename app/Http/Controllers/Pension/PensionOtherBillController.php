<?php

namespace App\Http\Controllers\Pension;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pensioner;
use App\Models\PensionerOtherBill;
use App\Models\PensionerOtherBillSummary;
use App\Models\WebsiteSetting;
use Carbon\Carbon;
use PDF;

class PensionOtherBillController extends Controller {
    public function index() {
        $pensionerReport = PensionerOtherBill::orderBy('created_at', 'desc')->get();
        return view("layouts.pages.pension.other.report", compact('pensionerReport'));
    }

    public function create() {
        $currentMonth = now()->format('Y-m');
        $existingReport = PensionerOtherBill::whereDate('created_at', '>=', now()->startOfMonth())->whereDate('created_at', '<=', now()->endOfMonth())->first();

        if ($existingReport) {
            return redirect()->back()->with('error', 'Report for the current month has already been created.');
        }

        $pensioners = Pensioner::with("ropa")->get();
        return view("layouts.pages.pension.other.create", compact('pensioners'));
    }

    public function store(Request $request) {
        $currentMonth = now()->format('Y-m');
        $existingReport = PensionerOtherBill::whereDate('created_at', '>=', now()->startOfMonth())->whereDate('created_at', '<=', now()->endOfMonth())->first();
        if ($existingReport) {
            return redirect()->route('superadmin.pension.other.index')->with('error', 'Report for the current month has already been created.');
        }
        $validated = $request->validate([
            'details' => 'nullable|string',
            'amount' => 'nullable|array',
            'amount.*' => 'nullable|numeric',
        ]);

        $reportID = 'Bill-' . uniqid();

        $pensionerReport = PensionerOtherBill::create([
            'bill_id' => $reportID,
            'details' => $validated['details'] ?? null,
        ]);

        foreach ($request->amount as $pensioner_id => $amount) {
            $amount = $amount ?? 0.00;

            PensionerOtherBillSummary::create([
                'pensioner_id' => $pensioner_id,
                'bill_id' => $reportID,
                'amount' => $amount,
            ]);

        }
        return redirect()->route('superadmin.pension.other.index')->with('success', 'Reports saved successfully!');
    }

    public function show($report_id) {
        $otherBill = PensionerOtherBill::where('bill_id', $report_id)->first();
        $pensionersReport = PensionerOtherBillSummary::with('pensionerDetails')->where('bill_id', $report_id)->where('amount', '>', 0)->orderBy('created_at', 'desc')->get();
        return view("layouts.pages.pension.other.show", compact('pensionersReport', 'otherBill'));
    }

    public function edit($report_id) {
        $report = PensionerOtherBill::where('bill_id', $report_id)->first();
        $pensionersReport = PensionerOtherBillSummary::with('pensionerDetails')->where('bill_id', $report_id)->orderBy('created_at', 'desc')->get();
        return view("layouts.pages.pension.other.edit", compact('pensionersReport', 'report'));
    }

    public function update(Request $request, $report_id) {
        $validated = $request->validate([
            'details' => 'nullable|string',
            'amount' => 'nullable|array',
            'amount.*' => 'nullable|numeric',
        ]);

        $pensionerReport = PensionerOtherBill::where('bill_id', $report_id)->first();

        if (!$pensionerReport) {
            return redirect()->route('superadmin.pension.other.index')->with('error', 'Report not found!');
        }
        
        $pensionerReport->update([
            'details' => $validated['details'] ?? null
        ]);

        foreach ($request->amount as $pensioner_id => $amount) {
            $amount = $amount ?? 0.00;
            $pensionerReportSummary = PensionerOtherBillSummary::where('bill_id', $report_id)->where('id', $pensioner_id)->first();

            if ($pensionerReportSummary) {
                $pensionerReportSummary->update([
                    'amount' => $amount,
                ]);
            }
        }
        return redirect()->back()->with('success', 'Report updated successfully!');
    }

    public function pdf($bill_id) {
        $report = PensionerOtherBill::where('bill_id', $bill_id)->first();
        $pensionersReport = PensionerOtherBillSummary::with('pensionerDetails')->where('bill_id', $bill_id)->where('amount', '>', 0)->orderBy('id', 'asc')->get();

        $website = WebsiteSetting::first();

        $pdf = PDF::loadView('layouts.pages.pension.other.pdf', compact('report', 'pensionersReport', 'website'))
            ->setOption('margin-top', 0)
            ->setOption('margin-left', 0)
            ->setOption('margin-right', 0)
            ->setOption('margin-bottom', 0)->setPaper('a4', 'landscape');

        return $pdf->stream('PensionerReport.pdf');
    }
}
