<?php

namespace App\Http\Controllers\EmployeeLeave;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EmployeeLeave;
use App\Models\LeaveEnjoyed;
use App\Models\LeaveReport;
use App\Models\LeaveReportStatus;
use App\Models\LeaveIncrement;
use PDF;
use Carbon\Carbon;

class LeaveReportsController extends Controller {

    public function index() {
        $currentYear = Carbon::now()->format('Y');
        $currentMonth = Carbon::now()->format('n');

        // Helper function to check leave increment existence
        $checkLeaveIncrement = function($type, $month) use ($currentYear) {
            return LeaveIncrement::where('month', "{$month}-{$currentYear}")->where('type', $type)->exists();
        };

        // Check for Casual Leave increment
        $hasCLIncrement = $checkLeaveIncrement('CL', 'January');

        // Check for Earned Leave increments
        $hasJanuaryIncrement = $checkLeaveIncrement('EL', 'January');
        $hasJulyIncrement = $checkLeaveIncrement('EL', 'July');

        // Check for Medical Leave increments
        $hasMLJanuaryIncrement = $checkLeaveIncrement('ML', 'January');
        $hasMLJulyIncrement = $checkLeaveIncrement('ML', 'July');

        // Prepare data for the view
        $showJanuaryButton = ($currentMonth == 1 && !$hasJanuaryIncrement);
        $showJulyButton = ($currentMonth == 7 && !$hasJulyIncrement);
        $showMLJanuaryButton = ($currentMonth == 1 && !$hasMLJanuaryIncrement);
        $showMLJulyButton = ($currentMonth == 7 && !$hasMLJulyIncrement);

        // Previous month data
        $previousMonthYear = Carbon::now()->subMonth()->format('F-Y');
        $hasPreviousMonthData = LeaveReportStatus::where('report_month', $previousMonthYear)->exists();

        // Get report months
        $reportMonths = LeaveReportStatus::orderBy('updated_at', 'desc')->get();

        return view('layouts.pages.leave-report.report', compact(
            'reportMonths',
            'hasPreviousMonthData',
            'currentYear',
            'hasCLIncrement',
            'showJanuaryButton',
            'showJulyButton',
            'showMLJanuaryButton',
            'showMLJulyButton'
        ));
    }

    public function create() {
        $title = "Leave Report Generate";
        $currentMonth = now()->format('Y-m');
        $existingReport = LeaveReportStatus::whereDate('created_at', '>=', now()->startOfMonth())->whereDate('created_at', '<=', now()->endOfMonth())->first();

        if ($existingReport) {
            return redirect()->back()->with('error', 'Report for the current month has already been created.');
        }

        $employeeLeaves = EmployeeLeave::with('employeeType')->orderBy('updated_at', 'desc')->get();
        return view("layouts.pages.leave-report.create", compact('employeeLeaves', 'title'));
    }


    public function storeLeaveReport() {
        $startOfMonth = now()->startOfMonth();
        $endOfMonth = now()->endOfMonth();

        // Check if a report for the current month already exists
        $existingReport = LeaveReportStatus::whereBetween('created_at', [$startOfMonth, $endOfMonth])->first();
        if ($existingReport) {
            return redirect()->route('superadmin.leave.report.index')->with('error', 'Report for the current month has already been created.');
        }

        $currentMonth = now()->format('F');
        $currentYear = now()->format('Y');

        // Fetch all employee leaves
        $employeeLeaves = EmployeeLeave::all();

        // Create a new LeaveReportStatus entry
        LeaveReportStatus::create([
            'report_month' => $currentMonth . '-' . $currentYear,
        ]);

        $leaveEnjoyedDataToDelete = [];

        // Loop through each employee leave to generate reports
        foreach ($employeeLeaves as $employeeLeave) {
            // Get all leaves enjoyed by the employee in the previous month
            $leaveEnjoyedData = LeaveEnjoyed::where('emp_id', $employeeLeave->id)
            ->whereYear('leave_date', $currentYear)
            ->whereMonth('leave_date', now()->month)
            ->get();

            // Calculate total leaves for the current month
            $totalCL = $leaveEnjoyedData->where('leave_type', 'CL');
            $totalEL = $leaveEnjoyedData->where('leave_type', 'EL');
            $totalML = $leaveEnjoyedData->where('leave_type', 'ML');

            // Get the count of enjoyed leaves
            $countCL = $totalCL->count();
            $countEL = $totalEL->count();
            $countML = $totalML->count();

            // Collect leave dates for each type and format them
            $clDates = $totalCL->pluck('leave_date')->map(function ($date) {
                return \Carbon\Carbon::parse($date)->format('d');
            })->implode(', ');

            $elDates = $totalEL->pluck('leave_date')->map(function ($date) {
                return \Carbon\Carbon::parse($date)->format('d');
            })->implode(', ');

            $mlDates = $totalML->pluck('leave_date')->map(function ($date) {
                return \Carbon\Carbon::parse($date)->format('d');
            })->implode(', ');

            // Create a new LeaveReport entry for each employee
            LeaveReport::create([
                'emp_id' => $employeeLeave->id,
                'emp_name' => $employeeLeave->emp_name,
                'emp_designation' => $employeeLeave->designation,
                'month' => $currentMonth . '-' . $currentYear,
                'cl' => $employeeLeave->cl,
                'cl_enjoyed' => $countCL,
                'cl_in_hand' => $employeeLeave->cl_in_hand,
                'cl_date' => $clDates,
                'el' => $employeeLeave->el,
                'el_enjoyed' => $countEL,
                'el_in_hand' => $employeeLeave->el_in_hand,
                'el_date' => $elDates,
                'ml' => $employeeLeave->ml,
                'ml_enjoyed' => $countML,
                'ml_in_hand' => $employeeLeave->ml_in_hand,
                'ml_date' => $mlDates,
            ]);

            // Store the leave data for later deletion
            $leaveEnjoyedDataToDelete = array_merge($leaveEnjoyedDataToDelete, $leaveEnjoyedData->pluck('id')->toArray());

            // Update EmployeeLeave table after generating the report
            $employeeLeave->update([
                'cl' => $employeeLeave->cl_in_hand,
                'cl_enjoyed' => 0,
                'el' => $employeeLeave->el_in_hand,
                'el_enjoyed' => 0,
                'ml' => $employeeLeave->ml_in_hand,
                'ml_enjoyed' => 0,
            ]);
        }

        if (!empty($leaveEnjoyedDataToDelete)) {
            LeaveEnjoyed::whereIn('id', $leaveEnjoyedDataToDelete)->delete();
        }

        return  redirect()->route('superadmin.leave.report.index')->with('success', 'Leave report generated successfully!');
    }

    public function downloadReport(Request $request) {
        $request->validate([
            'month' => 'required|date_format:F-Y', // Ensure it's in YYYY-MM format
            'format' => 'required|in:csv,pdf', // Ensure format is either 'csv' or 'pdf'
        ]);
        // Retrieve the month and format from the request
        $month = $request->input('month');
        $format = $request->input('format'); // Use input() for 'format'


        // Fetch leave reports for the selected month
        $leaveReports = LeaveReport::with('employeeType')->where('month', $month)->get();

        // Check the format and generate accordingly
        if ($format == 'csv') {
            // Generate CSV
            $csvFileName = 'leave_reports_' . $month . '.csv';

            // Set headers for CSV download
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="' . $csvFileName . '"');

            // Create a CSV file in memory
            $handle = fopen('php://output', 'w');

            // Add the header row
            fputcsv($handle, [
                'Emp ID', 'Emp Name', 'Emp Designation', 'Month',
                'CL', 'CL Enjoyed', 'CL Date', 'CL In Hand',
                'EL', 'EL Enjoyed', 'EL Date', 'EL In Hand',
                'ML', 'ML Enjoyed', 'ML Date' ,'ML In Hand'
            ]);

            // Add the data rows
            foreach ($leaveReports as $report) {
                fputcsv($handle, [
                    $report->emp_id,
                    $report->emp_name,
                    $report->employeeType ? $report->employeeType->employee_type : 'N/A',
                    $report->month,
                    $report->cl,
                    $report->cl_enjoyed,
                    $report->cl_date,
                    $report->cl_in_hand,
                    $report->el,
                    $report->el_enjoyed,
                    $report->el_date,
                    $report->el_in_hand,
                    $report->ml,
                    $report->ml_enjoyed,
                    $report->ml_date,
                    $report->ml_in_hand,
                ]);
            }

            // Close the handle after writing all data
            fclose($handle);

            // Return the response
            return response()->stream(function () use ($handle) {
                // No need to close the handle here, as it has already been closed
            }, 200, [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $csvFileName . '"',
            ]);

        } elseif ($format == 'pdf') {
            // Generate PDF
            $pdf = PDF::loadView('layouts.pages.leave-report.pdf', compact('leaveReports', 'month'))->setPaper('a4', 'landscape');

            // Download the PDF
            return $pdf->download('leave_reports_' . $month . '.pdf');
        }

        // If the format is not valid, redirect back with an error
        return redirect()->back()->with('error', 'Invalid format selected.');
    }




    public function CLincrement(Request $request) {
        $currentYear = Carbon::now()->format('Y');
        $monthString = 'January-' . $currentYear;
        LeaveIncrement::create([
            'month' => $monthString,
            'type' => 'CL',
        ]);

        EmployeeLeave::query()->update([
            'cl' => 14,
            'cl_in_hand' => 14,
        ]);
        return redirect()->back()->with('success', 'Casual Leave increment recorded for ' . $currentYear);
    }

    public function ELincrement(Request $request)
    {
        $currentYear = Carbon::now()->format('Y');
        $currentMonth = Carbon::now()->format('F');
        $monthToStore =  $currentMonth . '-' . $currentYear;

        $hasIncrement = LeaveIncrement::where('month', $monthToStore)->where('type', 'EL')->exists();

        if (!$hasIncrement) {
            LeaveIncrement::create([
                'month' => $monthToStore,
                'type' => 'EL',
            ]);
            EmployeeLeave::query()->increment('el', 15);
            EmployeeLeave::query()->increment('el_in_hand', 15);
            return redirect()->back()->with('success', 'Earned Leave increment recorded for ' . $monthToStore);
        }

        return redirect()->back()->with('info', 'Earned Leave has already been incremented for ' . $monthToStore);
    }

    public function MLincrement(Request $request) {
        $currentYear = Carbon::now()->format('Y');
        $currentMonth = Carbon::now()->format('F');
        $monthToStore = $currentMonth . '-' . $currentYear;

        $hasIncrement = LeaveIncrement::where('month', $monthToStore)->where('type', 'ML')->exists();

        if (!$hasIncrement) {
            LeaveIncrement::create([
                'month' => $monthToStore,
                'type' => 'ML',
            ]);
            EmployeeLeave::query()->increment('ml', 15);
            EmployeeLeave::query()->increment('ml_in_hand', 15);
            return redirect()->back()->with('success', 'Maternity Leave increment recorded for ' . $monthToStore);
        }

        return redirect()->back()->with('info', 'Maternity Leave has already been incremented for ' . $monthToStore);
    }

    public function view($report_month) {
        $report = LeaveReportStatus::where('report_month', $report_month)->first();
        $leaveReport = LeaveReport::with('employeeType')->where('month', $report_month)->orderBy('created_at', 'desc')->get();
        return view("layouts.pages.leave-report.view", compact('leaveReport', 'report'));
    }

    public function update(Request $request) {
        $request->validate([
            'id' => 'required|exists:leave_reports,id',
            'emp_id' => 'required|exists:employee_leaves,id',
            'cl_date' => 'nullable|string',
            'el_date' => 'nullable|string',
            'ml_date' => 'nullable|string',
        ]);

        // Update LeaveReport
        $leaveReport = LeaveReport::findOrFail($request->id);

        $this->processLeave($leaveReport, 'cl', $request->cl_date, false);
        $this->processLeave($leaveReport, 'el', $request->el_date, false);
        $this->processLeave($leaveReport, 'ml', $request->ml_date, true);

        $leaveReport->save();

        // Copy values to EmployeeLeave
        $employeeLeave = EmployeeLeave::findOrFail($request->emp_id);
        $employeeLeave->update([
            'cl' => $leaveReport->cl_in_hand,
            'el' => $leaveReport->el_in_hand,
            'ml' => $leaveReport->ml_in_hand,
            'cl_in_hand' => $leaveReport->cl_in_hand,
            'el_in_hand' => $leaveReport->el_in_hand,
            'ml_in_hand' => $leaveReport->ml_in_hand,
        ]);

        return redirect()->back()->with('success', 'Leave updated successfully!');
    }

    private function processLeave($leaveReport, $type, $datesString, $isML) {
        $dateField = $type.'_date';
        $enjoyedField = $type.'_enjoyed';
        $inHandField = $type.'_in_hand';

        $oldDates = $leaveReport->$dateField ? explode(', ', $leaveReport->$dateField) : [];
        $newDates = $datesString ? explode(',', $datesString) : [];

        $newDates = array_map(function($date) {
            $parts = explode('-', trim($date));
            return end($parts); // Now using proper variable reference
        }, $newDates);

        $newDates = array_filter($newDates);
        $dateDifference = count($newDates) - count($oldDates);

        if ($dateDifference != 0) {
            $adjustment = $isML ? $dateDifference * 2 : $dateDifference;
            $leaveReport->$enjoyedField += $dateDifference;
            $leaveReport->$inHandField -= $adjustment;
        }

        $leaveReport->$dateField = !empty($newDates) ? implode(', ', $newDates) : null;
    }

}
