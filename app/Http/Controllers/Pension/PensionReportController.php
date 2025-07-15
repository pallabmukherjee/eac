<?php

namespace App\Http\Controllers\Pension;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pensioner;
use App\Models\RopaYear;
use App\Models\PensionerReport;
use App\Models\PensionerReportSummary;
use App\Models\LifeCertificate;
use App\Models\WebsiteSetting;
use Carbon\Carbon;
use PDF;

class PensionReportController extends Controller
{
    public function index() {
        $pensionerReport = PensionerReport::orderBy('created_at', 'desc')->get();
        return view("layouts.pages.pension.report.report", compact('pensionerReport'));
    }

    public function create() {
        $currentMonth = now()->format('Y-m');
        $existingReport = PensionerReport::whereDate('created_at', '>=', now()->startOfMonth())->whereDate('created_at', '<=', now()->endOfMonth())->first();

        if ($existingReport) {
            return redirect()->back()->with('error', 'Report for the current month has already been created.');
        }

        $pensioners = Pensioner::with("ropa")->orderBy('id', 'asc')->get();
        return view("layouts.pages.pension.report.create", compact('pensioners'));
    }

    public function store(Request $request) {
        $currentMonth = now()->format('Y-m');
        $existingReport = PensionerReport::whereDate('created_at', '>=', now()->startOfMonth())->whereDate('created_at', '<=', now()->endOfMonth())->first();
        if ($existingReport) {
            return redirect()->route('superadmin.pension.report.index')->with('error', 'Report for the current month has already been created.');
        }
        $validated = $request->validate([
            'gross' => 'nullable|array',
            'gross.*' => 'nullable|numeric',
            'arrear' => 'nullable|array',
            'arrear.*' => 'nullable|numeric',
            'overdrawn' => 'nullable|array',
            'overdrawn.*' => 'nullable|numeric',
            'remarks' => 'nullable|array',
            'remarks.*' => 'nullable|string',
        ]);

        $reportID = 'Pension-Report' . uniqid();

        $pensionerReport = PensionerReport::create([
            'report_id' => $reportID,
        ]);

        // Loop through each pensioner and process the data
        foreach ($request->arrear as $pensioner_id => $arrear) {
            $arrear = $arrear ?? 0.00;
            $overdrawn = $request->overdrawn[$pensioner_id] ?? 0.00;
            $remarks = $request->remarks[$pensioner_id] ?? '';
            $gross = $request->gross[$pensioner_id] ?? 0.00;
            $netPension = $gross + $arrear - $overdrawn;

            $lifeCertificate = LifeCertificate::where('user_id', $pensioner_id)->where('status', 2)->first();
            if ($lifeCertificate) {
                $netPension += $lifeCertificate->amount;
                $lifeCertificate->status = 3;
                $lifeCertificate->save();
            }
            PensionerReportSummary::create([
                'pensioner_id' => $pensioner_id,
                'report_id' => $reportID,
                'gross' => $gross,
                'arrear' => $arrear,
                'overdrawn' => $overdrawn,
                'net_pension' => $netPension,
                'remarks' => $remarks,
            ]);



            $pensioner = Pensioner::find($pensioner_id);
            if ($pensioner && $pensioner->life_certificate == 2) {
                $existingLifeCertificate = LifeCertificate::where('user_id', $pensioner_id)->where('status', 1)->first();

                if (!$existingLifeCertificate) {
                    LifeCertificate::create([
                        'user_id' => $pensioner_id,
                        'month' => 1,
                        'amount' => $netPension,
                        'status' => 1,
                    ]);
                } else {
                    $nextMonth = $existingLifeCertificate->month + 1;
                    $newAmount = $existingLifeCertificate->amount + $netPension;
                    $existingLifeCertificate->update([
                        'month' => $nextMonth,
                        'amount' => $newAmount,
                    ]);
                }
            }

        }
        return redirect()->route('superadmin.pension.report.index')->with('success', 'Reports saved successfully!');
    }

    public function show($report_id) {
        $report = PensionerReport::where('report_id', $report_id)->first();
        $pensionersReport = PensionerReportSummary::with('pensionerDetails')->where('report_id', $report_id)->orderBy('id', 'asc')->get();
        return view("layouts.pages.pension.report.show", compact('report', 'pensionersReport'));
    }

    public function edit($report_id) {
        $report = PensionerReport::where('report_id', $report_id)->first();
        $pensionersReport = PensionerReportSummary::with('pensionerDetails')->where('report_id', $report_id)->orderBy('created_at', 'desc')->get();
        return view("layouts.pages.pension.report.edit", compact('pensionersReport', 'report'));
    }

    public function update(Request $request, $report_id) {
        // Validate the request for the required fields
        $validated = $request->validate([
            'arrear' => 'nullable|array',
            'arrear.*' => 'nullable|numeric',
            'overdrawn' => 'nullable|array',
            'overdrawn.*' => 'nullable|numeric',
            'remarks' => 'nullable|array',
            'remarks.*' => 'nullable|string',
        ]);

        // Find the report by report_id
        $pensionerReport = PensionerReport::where('report_id', $report_id)->first();

        if (!$pensionerReport) {
            return redirect()->route('superadmin.pension.report.index')->with('error', 'Report not found!');
        }

        // Loop through each pensioner and update the required fields
        foreach ($request->arrear as $pensioner_id => $arrear) {
            $arrear = $arrear ?? 0.00;
            $overdrawn = $request->overdrawn[$pensioner_id] ?? 0.00;
            $remarks = $request->remarks[$pensioner_id] ?? '';

            // Find the pensioner report summary for the given pensioner_id
            $pensionerReportSummary = PensionerReportSummary::where('report_id', $report_id)->where('id', $pensioner_id)->first();

            if ($pensionerReportSummary) {
                // Update the summary with the new values
                $pensionerReportSummary->update([
                    'arrear' => $arrear,
                    'overdrawn' => $overdrawn,
                    'remarks' => $remarks,
                    'net_pension' => ($pensionerReportSummary->gross + $arrear - $overdrawn), // Recalculate net pension
                ]);
            }
        }

        // Redirect with a success message
        return redirect()->back()->with('success', 'Report updated successfully!');
        //return redirect()->route('superadmin.pension.report.index')->with('success', 'Report updated successfully!');
    }

    public function pdf($report_id) {
        $report = PensionerReport::where('report_id', $report_id)->first();
        $pensionersReport = PensionerReportSummary::with('pensionerDetails')->where('report_id', $report_id)->orderBy('id', 'asc')->get();

        $website = WebsiteSetting::first();

        $pdf = PDF::loadView('layouts.pages.pension.report.pdf', compact('report', 'pensionersReport', 'website'))
            ->setOption('margin-top', 0)
            ->setOption('margin-left', 0)
            ->setOption('margin-right', 0)
            ->setOption('margin-bottom', 0)->setPaper('a3', 'landscape');

        return $pdf->stream('PensionerReport.pdf');
    }

    public function csv($report_id)
{
    $report = PensionerReport::where('report_id', $report_id)->first();
    $pensionersReport = PensionerReportSummary::with('pensionerDetails')->where('report_id', $report_id)->orderBy('id', 'asc')->get();
    $website = WebsiteSetting::first();

    // Set headers for CSV download
    $filename = 'PensionerReport_' . \Carbon\Carbon::parse($report->created_at)->format('F-Y') . '.csv';
    $headers = [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => "attachment; filename=\"$filename\"",
    ];

    // Create CSV content
    $callback = function() use ($pensionersReport, $website, $report) {
        $file = fopen('php://output', 'w');

        // Header row
        fputcsv($file, [
            'Name Of Pensioners',
            'Aadhaar No',
            'Type Of Pension',
            'Pensioner In Case Of Family Pension',
            'PPO Number',
            'PPO Date',
            'Bank AC No.',
            'IFSC CODE',
            'Date of Retirement',
            'Basic Pension',
            'D/R',
            'M/A',
            'Other',
            'Total',
            'Remarks'
        ]);

        // Initialize totals
        $total_basic_pension = 0;
        $total_dr = 0;
        $total_medical = 0;
        $total_other = 0;
        $total_gross = 0;

        // Data rows
        foreach ($pensionersReport as $item) {
            $aadhar = $item->pensionerDetails->aadhar_number;
            $masked = str_repeat('*', strlen($aadhar) - 4) . substr($aadhar, -4);

            $basic_pension = $item->pensionerDetails->basic_pension;
            $dr = ($item->pensionerDetails->basic_pension * $item->pensionerDetails->dr_percentage) / 100;
            $medical = $item->pensionerDetails->medical_allowance;
            $other = $item->pensionerDetails->other_allowance;
            $gross = $item->gross;

            $total_basic_pension += $basic_pension;
            $total_dr += $dr;
            $total_medical += $medical;
            $total_other += $other;
            $total_gross += $gross;

            fputcsv($file, [
                $item->pensionerDetails->pensioner_name,
                $masked,
                $item->pensionerDetails->pension_type == 1 ? 'Self' : 'Family member',
                $item->pensionerDetails->family_name,
                $item->pensionerDetails->ppo_number,
                $item->pensionerDetails->ppo_date ? \Carbon\Carbon::parse($item->pensionerDetails->ppo_date)->format('d/m/Y') : 'NA',
                $item->pensionerDetails->savings_account_number,
                $item->pensionerDetails->ifsc_code,
                $item->pensionerDetails->retirement_date ? \Carbon\Carbon::parse($item->pensionerDetails->retirement_date)->format('d/m/Y') : 'Alive',
                number_format(ceil($basic_pension), 2),
                number_format(ceil($dr), 2),
                number_format($medical, 2),
                number_format($other, 2),
                number_format($gross, 2),
                $item->remarks
            ]);
        }

        // Total row
        fputcsv($file, [
            'Total', '', '', '', '', '', '', '', '',
            number_format(ceil($total_basic_pension), 2),
            number_format(ceil($total_dr), 2),
            number_format($total_medical, 2),
            number_format($total_other, 2),
            number_format($total_gross, 2),
            ''
        ]);

        fclose($file);
    };

    return response()->stream($callback, 200, $headers);
}
}
