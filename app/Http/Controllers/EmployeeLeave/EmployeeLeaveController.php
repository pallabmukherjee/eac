<?php

namespace App\Http\Controllers\EmployeeLeave;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EmployeeLeave;
use App\Models\EmployeeType;
use App\Http\Requests\Employe\StoreEmployeeLeaveRequest;
use App\Models\LeaveReportStatus;
use Carbon\Carbon;

class EmployeeLeaveController extends Controller
{
    public function index() {
        $title = "Employee Information List";
        $employeeLeaves = EmployeeLeave::with('employeeType')->orderBy('updated_at', 'desc')->get();
        return view("layouts.pages.employee-leave.list", compact('employeeLeaves', 'title'));
    }

    public function add() {
        $employeeTypes = EmployeeType::orderBy('updated_at', 'desc')->get();
        $url = route('superadmin.leave.employee.store');
        $title = "Add Employee Information";
        return view("layouts.pages.employee-leave.add", compact('url', 'title', 'employeeTypes'));
    }

    public function store(StoreEmployeeLeaveRequest $request) {
        $data = $request->validated();
        foreach (['cl', 'el', 'ml'] as $leaveType) {
            $data["{$leaveType}_in_hand"] = $data[$leaveType];
        }
        EmployeeLeave::create($data);
        return redirect()->route('superadmin.leave.employee.index')->with('success', 'Employee leave created successfully!');
    }

    public function edit($id) {
        $employeeTypes = EmployeeType::orderBy('updated_at', 'desc')->get();
        $url = route('superadmin.leave.employee.update', ['id' => $id]);
        $title = "Edit Employee Information";
        $employeeLeave = EmployeeLeave::findOrFail($id);
        return view('layouts.pages.employee-leave.add', compact('employeeLeave', 'url', 'title', 'employeeTypes'));
    }

    public function update(StoreEmployeeLeaveRequest $request, $id) {
        $employeeLeave = EmployeeLeave::findOrFail($id);
        $data = $request->validated();
        foreach (['cl', 'el', 'ml'] as $leaveType) {
            $data["{$leaveType}_in_hand"] = $data[$leaveType];
            $data["{$leaveType}_enjoyed"] = 0;
        }
        $employeeLeave->update($data);
        return redirect()->route('superadmin.leave.employee.index')->with('success', 'Employee leave record updated successfully!');
    }

    public function destroy($id) {
        $employeeLeave = EmployeeLeave::findOrFail($id);
        $employeeLeave->delete();

        return redirect()->route('superadmin.leave.employee.index')->with('success', 'Employee leave deleted successfully!');
    }
}
