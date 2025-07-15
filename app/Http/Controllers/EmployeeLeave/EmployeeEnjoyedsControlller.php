<?php

namespace App\Http\Controllers\EmployeeLeave;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EmployeeLeave;
use App\Models\LeaveEnjoyed;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class EmployeeEnjoyedsControlller extends Controller {
    public function add($id) {
        $employeeLeave = EmployeeLeave::findOrFail($id);
        $url = route('superadmin.leave.enjoyed.store');
        $title = "Add Leave";
        return view("layouts.pages.leave-enjoyed.add", compact('url', 'title', 'employeeLeave'));
    }

    public function store(Request $request) {
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

}
