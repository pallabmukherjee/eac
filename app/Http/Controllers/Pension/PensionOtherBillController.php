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
        $pensionerReport = PensionerOtherBill::latest()->get();
        return view("layouts.pages.pension.other.report", compact('pensionerReport'));
    }

    public function create() {
        $pensioners = Pensioner::with("ropa")->get();
        return view("layouts.pages.pension.other.create", compact('pensioners'));
    }

    public function store(Request $request) {
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
        $pensionersReport = PensionerOtherBillSummary::with('pensionerDetails')->where('bill_id', $report_id)->where('amount', '>', 0)->latest()->get();
        return view("layouts.pages.pension.other.show", compact('pensionersReport', 'otherBill'));
    }

    public function edit($report_id) {
        $report = PensionerOtherBill::where('bill_id', $report_id)->first();
        $pensionersReport = PensionerOtherBillSummary::with('pensionerDetails')->where('bill_id', $report_id)->latest()->get();
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

    public function csv($bill_id) {
        $report = PensionerOtherBill::where('bill_id', $bill_id)->first();
        $pensionersReport = PensionerOtherBillSummary::with('pensionerDetails')->where('bill_id', $bill_id)->where('amount', '>', 0)->orderBy('id', 'asc')->get();
        $website = WebsiteSetting::first();

        $fileName = 'PensionerOtherBill.csv';

        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $columns = array('Srl', 'Name Of Pensioners', 'Type Of Pension', 'Pensioner In Case Of Family member', 'PPO No', 'Bank A/C', 'IFSC', 'Amount');

        $callback = function() use($pensionersReport, $columns, $report, $website) {
            $file = fopen('php://output', 'w');
            fputcsv($file, [$website->organization]);
            fputcsv($file, ['Pensioner Other Bill Report Of ' . \Carbon\Carbon::parse($report->created_at)->format('F-Y')]);
            if($report->details){
                fputcsv($file, [$report->details]);
            }
            fputcsv($file, ['Voucher No _______________ Voucher Date _____________']);
            fputcsv($file, $columns);

            $total_amount = 0;
            foreach ($pensionersReport as $key => $item) {
                $total_amount += $item->amount;
                $row['Srl']  = $key + 1;
                $row['Name Of Pensioners']    = $item->pensionerDetails->pensioner_name;
                if ($item->pensionerDetails->pension_type == 1) {
                    $row['Type Of Pension']  = 'Self';
                } else {
                    $row['Type Of Pension']  = 'Family member';
                }
                $row['Pensioner In Case Of Family member']    = $item->pensionerDetails->family_name;
                $row['PPO No']    = $item->pensionerDetails->ppo_number;
                $row['Bank A/C']    = $item->pensionerDetails->savings_account_number;
                $row['IFSC']    = $item->pensionerDetails->ifsc_code;
                $row['Amount']    = number_format($item->amount, 2);

                fputcsv($file, array($row['Srl'], $row['Name Of Pensioners'], $row['Type Of Pension'], $row['Pensioner In Case Of Family member'], $row['PPO No'], $row['Bank A/C'], $row['IFSC'], $row['Amount']));
            }
            fputcsv($file, ['', '', '', '', '', '', 'Total', number_format($total_amount, 2)]);
        };

        return response()->stream($callback, 200, $headers);
    }
}
