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
        $pensioners = Pensioner::with("ropa")->where('no_claimant', 0)->where('life_certificate', '!=', 2)->orderBy('id', 'asc')->get();
        return view("layouts.pages.pension.report.create", compact('pensioners'));
    }

    public function store(Request $request) {
        $existingReport = PensionerReport::where('month', $request->month)->where('year', $request->year)->first();
        if ($existingReport) {
            return redirect()->route('superadmin.pension.report.index')->with('error', 'Report for the selected month and year has already been created.');
        }
        $validated = $request->validate([
            'month' => 'required|numeric|between:1,12',
            'year' => 'required|numeric',
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
            'month' => $request->month,
            'year' => $request->year,
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
        $pensionersReport = PensionerReportSummary::with('pensionerDetails')->whereHas('pensionerDetails', function($q) { $q->where('no_claimant', 0)->where('life_certificate', '!=', 2); })->where('report_id', $report_id)->orderBy('id', 'asc')->get();
        return view("layouts.pages.pension.report.show", compact('report', 'pensionersReport'));
    }

    public function edit($report_id) {
        $report = PensionerReport::where('report_id', $report_id)->first();
        $pensionersReport = PensionerReportSummary::with('pensionerDetails')->whereHas('pensionerDetails', function($q) { $q->where('no_claimant', 0)->where('life_certificate', '!=', 2); })->where('report_id', $report_id)->orderBy('created_at', 'desc')->get();
        return view("layouts.pages.pension.report.edit", compact('pensionersReport', 'report'));
    }

    public function update(Request $request, $report_id) {
        // Validate the request for the required fields
        $validated = $request->validate([
            'month' => 'required|numeric|between:1,12',
            'year' => 'required|numeric',
            'arrear' => 'nullable|array',
            'arrear.*' => 'nullable|numeric',
            'overdrawn' => 'nullable|array',
            'overdrawn.*' => 'nullable|numeric',
            'remarks' => 'nullable|array',
            'remarks.*' => 'nullable|string',
        ]);

        // Check if a report with the same month and year already exists, excluding the current report being updated
        $existingReport = PensionerReport::where('month', $request->month)
                                        ->where('year', $request->year)
                                        ->where('report_id', '!=', $report_id) // Exclude the current report
                                        ->first();

        if ($existingReport) {
            return redirect()->back()->with('error', 'A report for the selected month and year already exists.');
        }

        // Find the report by report_id
        $pensionerReport = PensionerReport::where('report_id', $report_id)->first();

        if (!$pensionerReport) {
            return redirect()->route('superadmin.pension.report.index')->with('error', 'Report not found!');
        }

        $pensionerReport->month = $request->month;
        $pensionerReport->year = $request->year;
        $pensionerReport->save();

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
        $pensionersReport = PensionerReportSummary::with('pensionerDetails')->whereHas('pensionerDetails', function($q) { $q->where('no_claimant', 0)->where('life_certificate', '!=', 2); })->where('report_id', $report_id)->orderBy('id', 'asc')->get();

        $website = WebsiteSetting::first();

        $total_basic_pension = 0;
        $total_dr = 0;
        $total_medical = 0;
        $total_other = 0;
        $total_arrear = 0;
        $total_overdrawn = 0;
        $total_gross = 0;

        foreach ($pensionersReport as $item) {
            $basic_pension = ceil($item->pensionerDetails->basic_pension);
            $dr_raw = ($item->pensionerDetails->basic_pension * $item->pensionerDetails->dr_percentage) / 100;
            $dr = ($dr_raw - floor($dr_raw)) >= 0.01 ? ceil($dr_raw) : floor($dr_raw);
            $medical = ceil($item->pensionerDetails->medical_allowance);
            $other = ceil($item->pensionerDetails->other_allowance);
            $arrear = ceil($item->arrear);
            $overdrawn = ceil($item->overdrawn);
            $gross = $item->net_pension;

            $total_basic_pension += $basic_pension;
            $total_dr += $dr;
            $total_medical += $medical;
            $total_other += $other;
            $total_arrear += $arrear;
            $total_overdrawn += $overdrawn;
            $total_gross += $gross;
        }

        $pdf = PDF::loadView('layouts.pages.pension.report.pdf', compact(
            'report', 
            'pensionersReport', 
            'website',
            'total_basic_pension',
            'total_dr',
            'total_medical',
            'total_other',
            'total_arrear',
            'total_overdrawn',
            'total_gross'
        ))
            ->setOption('margin-top', 0)
            ->setOption('margin-left', 0)
            ->setOption('margin-right', 0)
            ->setOption('margin-bottom', 0)->setPaper('a3', 'landscape');

        return $pdf->stream('PensionerReport.pdf');
    }

    public function csv($report_id)
    {
        $report = PensionerReport::where('report_id', $report_id)->first();
        $pensionersReport = PensionerReportSummary::with('pensionerDetails')->whereHas('pensionerDetails', function($q) { $q->where('no_claimant', 0)->where('life_certificate', '!=', 2); })->where('report_id', $report_id)->orderBy('id', 'asc')->get();
        $website = WebsiteSetting::first();

        $filename = 'PensionerReport_' . \Carbon\Carbon::parse($report->created_at)->format('F-Y') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($pensionersReport, $website, $report) {
            $file = fopen('php://output', 'w');

            fputcsv($file, [
                'Sl. No',
                'Name Of Pensioners',
                'Aadhaar No',
                'Type Of Pension',
                'Pensioner In Case Of Family member',
                'PPO Number',
                'PPO Date',
                'Bank AC No.',
                'IFSC CODE',
                'Date of Retirement',
                'Basic pension',
                'D/R',
                'M/A',
                'Other',
                'Arrear',
                'Overdrawn',
                'Total',
                'Remarks'
            ]);

            $total_basic_pension = 0;
            $total_dr = 0;
            $total_medical = 0;
            $total_other = 0;
            $total_arrear = 0;
            $total_overdrawn = 0;
            $total_gross = 0;
            $sl_no = 1;

            foreach ($pensionersReport as $item) {
                $aadhar = $item->pensionerDetails->aadhar_number;
                $masked = str_repeat('*', strlen($aadhar) - 4) . substr($aadhar, -4);

                $basic_pension = ceil($item->pensionerDetails->basic_pension);
                $dr_raw = ($item->pensionerDetails->basic_pension * $item->pensionerDetails->dr_percentage) / 100;
                $dr = ($dr_raw - floor($dr_raw)) >= 0.01 ? ceil($dr_raw) : floor($dr_raw);
                $medical = ceil($item->pensionerDetails->medical_allowance);
                $other = ceil($item->pensionerDetails->other_allowance);
                $arrear = ceil($item->arrear);
                $overdrawn = ceil($item->overdrawn);
                $gross = $item->net_pension;

                $total_basic_pension += $basic_pension;
                $total_dr += $dr;
                $total_medical += $medical;
                $total_other += $other;
                $total_arrear += $arrear;
                $total_overdrawn += $overdrawn;
                $total_gross += $gross;

                fputcsv($file, [
                    $sl_no++,
                    $item->pensionerDetails->pensioner_name,
                    $masked,
                    $item->pensionerDetails->pension_type == 1 ? 'Self' : 'Family member',
                    $item->pensionerDetails->family_name,
                    $item->pensionerDetails->ppo_number,
                    $item->pensionerDetails->ppo_date ? \Carbon\Carbon::parse($item->pensionerDetails->ppo_date)->format('d/m/Y') : 'NA',
                    $item->pensionerDetails->savings_account_number . "\t",
                    $item->pensionerDetails->ifsc_code,
                    $item->pensionerDetails->retirement_date ? \Carbon\Carbon::parse($item->pensionerDetails->retirement_date)->format('d/m/Y') : 'Alive',
                    number_format($basic_pension, 2),
                    number_format($dr, 2),
                    number_format($medical, 2),
                    number_format($other, 2),
                    number_format($arrear, 2),
                    number_format($overdrawn, 2),
                    number_format($gross, 2),
                    $item->remarks
                ]);
            }

            fputcsv($file, [
                'Total', '', '', '', '', '', '', '', '', '',
                number_format($total_basic_pension, 2),
                number_format($total_dr, 2),
                number_format($total_medical, 2),
                number_format($total_other, 2),
                number_format($total_arrear, 2),
                number_format($total_overdrawn, 2),
                number_format($total_gross, 2),
                ''
            ]);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function destroy($report_id)
    {
        $report = PensionerReport::where('report_id', $report_id)->first();

        if (!$report) {
            return redirect()->route('superadmin.pension.report.index')->with('error', 'Report not found!');
        }

        // Delete associated PensionerReportSummary records
        PensionerReportSummary::where('report_id', $report_id)->delete();

        // Delete the PensionerReport record
        $report->delete();

        return redirect()->route('superadmin.pension.report.index')->with('success', 'Report deleted successfully!');
    }
}