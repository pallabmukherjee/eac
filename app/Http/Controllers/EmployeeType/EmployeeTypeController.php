<?php

namespace App\Http\Controllers\EmployeeType;
use App\Models\EmployeeType;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EmployeeTypeController extends Controller
{
    public function list() {
        $employeeTypes = EmployeeType::orderBy('updated_at', 'desc')->get();
        return view('layouts.pages.employee-type.list', compact('employeeTypes'));
    }

    public function add() {
        $url = route('superadmin.leave.type.store');
        $title = "Add Employee Type";
        return view('layouts.pages.employee-type.add', compact('url', 'title'));
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'employee_type' => 'required|string|max:255',
        ]);

        EmployeeType::create([
            'employee_type' => $validated['employee_type'],
        ]);

        return redirect()->route('superadmin.leave.type.list')->with('success', 'Employee type created successfully!');
    }

    public function edit($id)
    {
        $url = route('superadmin.leave.type.update', ['id' => $id]);
        $title = "Edit Employee Type";
        $employeeType = EmployeeType::findOrFail($id);
        return view('layouts.pages.employee-type.add', compact('employeeType', 'url', 'title'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'employee_type' => 'required|string|max:255',
        ]);

        $employeeType = EmployeeType::findOrFail($id);
        $employeeType->employee_type = $request->employee_type;
        $employeeType->save();

        return redirect()->route('superadmin.leave.type.list')->with('success', 'Employee Type updated successfully!');
    }

    public function destroy($id)
    {
        $employeeType = EmployeeType::findOrFail($id);
        $employeeType->delete();

        return redirect()->route('superadmin.leave.type.list')->with('success', 'Employee Type deleted successfully!');
    }
}
