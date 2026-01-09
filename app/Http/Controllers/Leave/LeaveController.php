<?php

namespace App\Http\Controllers\Leave;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EmployeeLeave;
use App\Models\EmployeeType;
use App\Http\Requests\Employe\StoreEmployeeLeaveRequest;
use App\Models\LeaveReportStatus;
use App\Models\LeaveEnjoyed;
use App\Models\LeaveIncrement;
use App\Models\LeaveReport;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use PDF;

class LeaveController extends Controller
{
    public function dashboard()
    {
        return view('layouts.pages.leave.dashboard');
    }

    // from EmployeeLeaveController
    public function employeeLeaveList() {
        $title = "Employee Information List";
        $employeeLeaves = EmployeeLeave::with('employeeType')->orderBy('updated_at', 'desc')->get();
        return view("layouts.pages.leave.employee-leave.list", compact('employeeLeaves', 'title'));
    }

    public function employeeLeaveAdd() {
        $employeeTypes = EmployeeType::orderBy('updated_at', 'desc')->get();
        $url = route('superadmin.leave.employee.store');
        $title = "Add Employee Information";
        return view("layouts.pages.leave.employee-leave.add", compact('url', 'title', 'employeeTypes'));
    }

    public function employeeLeaveStore(StoreEmployeeLeaveRequest $request) {
        $data = $request->validated();
        foreach (['cl', 'el', 'ml'] as $leaveType) {
            $data["{$leaveType}_in_hand"] = $data[$leaveType];
        }
        EmployeeLeave::create($data);
        return redirect()->route('superadmin.leave.employee.index')->with('success', 'Employee leave created successfully!');
    }

    public function employeeLeaveEdit($id) {
        $employeeTypes = EmployeeType::orderBy('updated_at', 'desc')->get();
        $url = route('superadmin.leave.employee.update', ['id' => $id]);
        $title = "Edit Employee Information";
        $employeeLeave = EmployeeLeave::findOrFail($id);
        return view('layouts.pages.leave.employee-leave.add', compact('employeeLeave', 'url', 'title', 'employeeTypes'));
    }

    public function employeeLeaveUpdate(StoreEmployeeLeaveRequest $request, $id) {
        $employeeLeave = EmployeeLeave::findOrFail($id);
        $data = $request->validated();
        foreach (['cl', 'el', 'ml'] as $leaveType) {
            $data["{$leaveType}_in_hand"] = $data[$leaveType];
            $data["{$leaveType}_enjoyed"] = 0;
        }
        $employeeLeave->update($data);
        return redirect()->route('superadmin.leave.employee.index')->with('success', 'Employee leave record updated successfully!');
    }

    public function employeeLeaveDestroy($id) {
        $employeeLeave = EmployeeLeave::findOrFail($id);
        $employeeLeave->delete();

        return redirect()->route('superadmin.leave.employee.index')->with('success', 'Employee leave deleted successfully!');
    }

    // from EmployeeEnjoyedsControlller
    public function leaveEnjoyedAdd($id) {
        $employeeLeave = EmployeeLeave::findOrFail($id);
        $url = route('superadmin.leave.enjoyed.store');
        $title = "Add Leave";
        return view("layouts.pages.leave.leave-enjoyed.add", compact('url', 'title', 'employeeLeave'));
    }

    public function leaveEnjoyedStore(Request $request) {
        // Validate the request if necessary
        $request->validate([
            'emp_id' => 'required|integer',
            'cl_dates' => 'nullable|string',
            'el_dates' => 'nullable|string',
            'ml_dates' => 'nullable|string',
        ]);

        // Start a database transaction
        DB::beginTransaction();

        try {
            // Initialize counters for enjoyed leaves
            $clCount = 0;
            $elCount = 0;
            $mlCount = 0;

            // Process Casual Leave dates
            if ($request->filled('cl_dates')) {
                $cl_dates = explode(',', $request->cl_dates);
                foreach ($cl_dates as $date) {
                    $date = trim($date); // Trim any extra spaces

                    // Check for existing record across all leave types
                    if (LeaveEnjoyed::where('emp_id', $request->emp_id)->where('leave_date', $date)->exists()) {
                        $validator = Validator::make([], []);
                        $validator->errors()->add('cl_dates', "Duplicate entry found for employee ID {$request->emp_id} on date: $date");
                        throw new \Illuminate\Validation\ValidationException($validator);
                    }

                    // Create the new record
                    LeaveEnjoyed::create([
                        'emp_id' => $request->emp_id,
                        'leave_type' => 'CL',
                        'leave_date' => $date,
                    ]);

                    // Increment the count for Casual Leave
                    $clCount++;
                }
            }

            // Process Earned Leave dates
            if ($request->filled('el_dates')) {
                $el_dates = explode(',', $request->el_dates);
                foreach ($el_dates as $date) {
                    $date = trim($date); // Trim any extra spaces

                    // Check for existing record across all leave types
                    if (LeaveEnjoyed::where('emp_id', $request->emp_id)->where('leave_date', $date)->exists()) {
                        $validator = Validator::make([], []);
                        $validator->errors()->add('el_dates', "Duplicate entry found for employee ID {$request->emp_id} on date: $date");
                        throw new \Illuminate\Validation\ValidationException($validator);
                    }

                    // Create the new record
                    LeaveEnjoyed::create([
                        'emp_id' => $request->emp_id,
                        'leave_type' => 'EL',
                        'leave_date' => $date,
                    ]);

                    // Increment the count for Earned Leave
                    $elCount++;
                }
            }

            // Process Medical Leave dates
            if ($request->filled('ml_dates')) {
                $ml_dates = explode(',', $request->ml_dates);
                $countIncrement = 2;
                foreach ($ml_dates as $date) {
                    $date = trim($date); // Trim any extra spaces

                    // Check for existing record across all leave types
                    if (LeaveEnjoyed::where('emp_id', $request->emp_id)->where('leave_date', $date)->exists()) {
                        $validator = Validator::make([], []);
                        $validator->errors()->add('ml_dates', "Duplicate entry found for employee ID {$request->emp_id} on date: $date");
                        throw new \Illuminate\Validation\ValidationException($validator);
                    }

                    // Create the new record
                    LeaveEnjoyed::create([
                        'emp_id' => $request->emp_id,
                        'leave_type' => 'ML',
                        'leave_date' => $date,
                    ]);

                    // Increment the count for Medical Leave
                    $mlCount += $countIncrement;
                }
            }

            // Commit the transaction if all inserts are successful
            DB::commit();

            // Update the employee leaves record
            $employeeLeave = EmployeeLeave::where('id', $request->emp_id)->first();

            if ($employeeLeave) {
                // Update the leave counts based on leave type
                $employeeLeave->cl_enjoyed += $clCount; // Increment enjoyed casual leave
                $employeeLeave->cl_in_hand -= $clCount; // Decrement in-hand casual leave

                $employeeLeave->el_enjoyed += $elCount; // Increment enjoyed earned leave
                $employeeLeave->el_in_hand -= $elCount; // Decrement in-hand earned leave

                $employeeLeave->ml_enjoyed += $mlCount; // Increment enjoyed medical leave
                $employeeLeave->ml_in_hand -= $mlCount; // Decrement in-hand medical leave

                // Save the updated EmployeeLeave record
                $employeeLeave->save();
            } else {
                // Handle case where employee leave record does not exist
                return redirect()->back()->with('error', 'Employee leave record not found.');
            }

            // Optionally, you can return a response or redirect
            return redirect()->back()->with('success', 'Leave dates stored successfully!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Rollback the transaction if any error occurs
            DB::rollBack();

            // Return the validation errors to the view
            return redirect()-> back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            // Rollback the transaction if any other error occurs
            DB::rollBack();

            // Return a generic error message
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    // from LeaveReportsController
    public function reportsIndex() {
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

        return view('layouts.pages.leave.leave-report.report', compact(
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

    public function reportsCreate() {
        $title = "Leave Report Generate";
        $currentMonth = now()->format('Y-m');
        $existingReport = LeaveReportStatus::whereDate('created_at', '>=', now()->startOfMonth())->whereDate('created_at', '<=', now()->endOfMonth())->first();

        if ($existingReport) {
            return redirect()->back()->with('error', 'Report for the current month has already been created.');
        }

        $employeeLeaves = EmployeeLeave::with('employeeType')->orderBy('updated_at', 'desc')->get();
        return view("layouts.pages.leave.leave-report.create", compact('employeeLeaves', 'title'));
    }


    public function reportsStoreLeaveReport() {
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

    public function reportsDownloadReport(Request $request) {
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
            $pdf = PDF::loadView('layouts.pages.leave.leave-report.pdf', compact('leaveReports', 'month'))->setPaper('a4', 'landscape');

            // Download the PDF
            return $pdf->download('leave_reports_' . $month . '.pdf');
        }

        // If the format is not valid, redirect back with an error
        return redirect()->back()->with('error', 'Invalid format selected.');
    }

    public function reportsView($report_month) {
        $report = LeaveReportStatus::where('report_month', $report_month)->first();
        $leaveReport = LeaveReport::with('employeeType')->where('month', 'LIKE', $report_month)->orderBy('created_at', 'desc')->get();
        return view("layouts.pages.leave.leave-report.view", compact('leaveReport', 'report'));
    }

    public function reportsUpdate(Request $request) {
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

    // from EmployeeTypeController
    public function employeeTypeList() {
        $employeeTypes = EmployeeType::orderBy('updated_at', 'desc')->get();
        return view('layouts.pages.leave.employee-type.list', compact('employeeTypes'));
    }

    public function employeeTypeAdd() {
        $url = route('superadmin.leave.type.store');
        $title = "Add Employee Type";
        return view('layouts.pages.leave.employee-type.add', compact('url', 'title'));
    }

    public function employeeTypeStore(Request $request) {
        $validated = $request->validate([
            'employee_type' => 'required|string|max:255',
        ]);

        EmployeeType::create([
            'employee_type' => $validated['employee_type'],
        ]);

        return redirect()->route('superadmin.leave.type.list')->with('success', 'Employee type created successfully!');
    }

    public function employeeTypeEdit($id)
    {
        $url = route('superadmin.leave.type.update', ['id' => $id]);
        $title = "Edit Employee Type";
        $employeeType = EmployeeType::findOrFail($id);
        return view('layouts.pages.leave.employee-type.add', compact('employeeType', 'url', 'title'));
    }

    public function employeeTypeUpdate(Request $request, $id)
    {
        $request->validate([
            'employee_type' => 'required|string|max:255',
        ]);

        $employeeType = EmployeeType::findOrFail($id);
        $employeeType->employee_type = $request->employee_type;
        $employeeType->save();

        return redirect()->route('superadmin.leave.type.list')->with('success', 'Employee Type updated successfully!');
    }

    public function employeeTypeDestroy($id)
    {
        $employeeType = EmployeeType::findOrFail($id);
        $employeeType->delete();

        return redirect()->route('superadmin.leave.type.list')->with('success', 'Employee Type deleted successfully!');
    }

    public function leaveCalendar()
    {
        $employees = EmployeeLeave::all();
        $leaveTypes = ['CL', 'EL', 'ML'];
        return view('layouts.pages.leave.leave-calendar.index', compact('employees', 'leaveTypes'));
    }

    public function storeLeaveCalendar(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employee_leaves,id',
            'leave_type' => 'required|in:CL,EL,ML',
            'selected_dates' => 'required|string',
        ]);

        DB::beginTransaction();

        try {
            $leaveType = $request->leave_type;
            $dates = explode(',', $request->selected_dates);
            $count = 0;

            foreach ($dates as $date) {
                $date = trim($date);

                if (LeaveEnjoyed::where('emp_id', $request->employee_id)->where('leave_date', $date)->exists()) {
                    $validator = Validator::make([], []);
                    $validator->errors()->add('selected_dates', "Duplicate entry found for employee ID {$request->employee_id} on date: $date");
                    throw new \Illuminate\Validation\ValidationException($validator);
                }

                LeaveEnjoyed::create([
                    'emp_id' => $request->employee_id,
                    'leave_type' => $leaveType,
                    'leave_date' => $date,
                ]);

                $count++;
            }

            $employeeLeave = EmployeeLeave::findOrFail($request->employee_id);

            $enjoyedField = strtolower($leaveType) . '_enjoyed';
            $inHandField = strtolower($leaveType) . '_in_hand';

            $employeeLeave->$enjoyedField += $count;
            $employeeLeave->$inHandField -= $count;

            $employeeLeave->save();

            DB::commit();

            return redirect()->back()->with('success', 'Leave dates stored successfully!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return redirect()->back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
