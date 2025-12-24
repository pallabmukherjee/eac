<?php

namespace App\Http\Controllers\Gratuity;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GratuityRopaYear;
use App\Models\Gratuity;
use App\Models\FinancialYear;
use Barryvdh\DomPDF\Facade\Pdf;

class GratuityController extends Controller
{
    public function add() {
        $url = route('superadmin.gratuity.store');
        $ropaYears = GratuityRopaYear::orderBy('created_at', 'desc')->get();
        $financialYears = FinancialYear::orderBy('created_at', 'desc')->get();
        return view("layouts.pages.gratuity.add", compact('url', 'ropaYears', 'financialYears'));
    }

    public function store(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'relation_name' => 'nullable|string|max:255',
            'employee_code' => 'required|string|max:255',
            'ppo_number' => 'required|string|max:255',
            'ppo_receive_date' => 'required|date',
            'retire_dead' => 'required|in:1,2', // Retire or Dead
            'retirement_date' => 'required|date',
            'financial_year' => 'required|string',
            'alive_status' => 'required|in:1,2', // Alive or Dead
            'loan_status' => 'required|in:1,2', // Yes or No
            'relation_died' => 'required|in:1,2', // Yes or No
            'warrant_name' => 'nullable|string|max:255',
            'warrant_adhar_no' => 'nullable|string|max:255',
            'ppo_amount' => 'required|numeric',
            'sanctioned_amount' => 'required|numeric',
            'ropa_year' => 'required|string',
            'bank_ac_no' => 'required|string',
            'ifsc' => 'required|string',
        ]);

        $relationName = $request->input('relation_name') ?? 'NA';
        // Create a new EmployeeRecord
        Gratuity::create([
            'name' => $request->input('name'),
            'relation_name' => $relationName,
            'employee_code' => $request->input('employee_code'),
            'ppo_number' => $request->input('ppo_number'),
            'ppo_receive_date' => $request->input('ppo_receive_date'),
            'retire_dead' => $request->input('retire_dead'),
            'retirement_date' => $request->input('retirement_date'),
            'financial_year' => $request->input('financial_year'),
            'alive_status' => $request->input('alive_status'),
            'loan_status' => $request->input('loan_status'),
            'relation_died' => $request->input('relation_died'),
            'warrant_name' => $request->input('warrant_name'),
            'warrant_adhar_no' => $request->input('warrant_adhar_no'),
            'ppo_amount' => $request->input('ppo_amount'),
            'sanctioned_amount' => $request->input('sanctioned_amount'),
            'ropa_year' => $request->input('ropa_year'),
            'bank_ac_no' => $request->input('bank_ac_no'),
            'ifsc' => $request->input('ifsc'),
        ]);

        return redirect()->back()->with('success', 'Employee record saved successfully!');
    }

    public function list() {
        $gratuitys = Gratuity::orderBy('created_at', 'desc')->get();
        return view("layouts.pages.gratuity.list", compact('gratuitys'));
    }

    public function edit($id) {
        $url = route('superadmin.gratuity.update', $id);
        $gratuity = Gratuity::where('id', $id)->first();

        $ropaYears = GratuityRopaYear::orderBy('created_at', 'desc')->get();
        $financialYears = FinancialYear::orderBy('created_at', 'desc')->get();

        return view("layouts.pages.gratuity.edit", compact('gratuity', 'ropaYears', 'url', 'financialYears'));
    }

    public function update(Request $request, $id) {
        // Validate the incoming request data
        $request->validate([
            'name' => 'required|string|max:255',
            'relation_name' => 'nullable|string|max:255',
            'employee_code' => 'required|string|max:255',
            'ppo_number' => 'required|string|max:255',
            'ppo_receive_date' => 'required|date',
            'retire_dead' => 'required|in:1,2', // Retire or Dead
            'retirement_date' => 'required|date',
            'financial_year' => 'required|string|max:4',
            'alive_status' => 'required|in:1,2', // Alive or Dead
            'loan_status' => 'required|in:1,2', // Yes or No
            'relation_died' => 'required|in:1,2', // Yes or No
            'warrant_name' => 'nullable|string|max:255',
            'warrant_adhar_no' => 'nullable|string|max:255',
            'ppo_amount' => 'required|numeric',
            'sanctioned_amount' => 'required|numeric',
            'ropa_year' => 'required|string|max:4',
            'bank_ac_no' => 'required|string',
            'ifsc' => 'required|string',
        ]);

        // Find the existing Gratuity record by ID
        $gratuity = Gratuity::findOrFail($id);
        $relationName = $request->input('relation_name') ?? 'NA';
        // Update the record with the new values
        $gratuity->update([
            'name' => $request->input('name'),
            'relation_name' => $relationName,
            'employee_code' => $request->input('employee_code'),
            'ppo_number' => $request->input('ppo_number'),
            'ppo_receive_date' => $request->input('ppo_receive_date'),
            'retire_dead' => $request->input('retire_dead'),
            'retirement_date' => $request->input('retirement_date'),
            'financial_year' => $request->input('financial_year'),
            'alive_status' => $request->input('alive_status'),
            'loan_status' => $request->input('loan_status'),
            'relation_died' => $request->input('relation_died'),
            'warrant_name' => $request->input('warrant_name'),
            'warrant_adhar_no' => $request->input('warrant_adhar_no'),
            'ppo_amount' => $request->input('ppo_amount'),
            'sanctioned_amount' => $request->input('sanctioned_amount'),
            'ropa_year' => $request->input('ropa_year'),
            'bank_ac_no' => $request->input('bank_ac_no'),
            'ifsc' => $request->input('ifsc'),
        ]);

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Employee record updated successfully!');
    }

    public function destroy($id) {
        $gratuity = Gratuity::findOrFail($id);
        $gratuity->delete();

        return redirect()->back()->with('success', 'Gratuity record deleted successfully.');
    }

    public function searchEmployees(Request $request) {
        $search = $request->input('q');
        $employees = Gratuity::where('name', 'LIKE', "%$search%")
            ->orWhere('employee_code', 'LIKE', "%$search%")
            ->get(['id', 'name', 'employee_code']);

        $formattedEmployees = [];
        foreach ($employees as $employee) {
            $formattedEmployees[] = [
                'id' => $employee->id,
                'text' => $employee->employee_code . ' - ' . $employee->name
            ];
        }

        return response()->json($formattedEmployees);
    }

    public function getEmployeeDetails($id) {
        $loanAmountSum = \App\Models\Loan::where('emp_code', $id)->sum('loan_amount');
        $gratuity = Gratuity::find($id);
        return response()->json([
            'loan_amount' => $loanAmountSum,
            'pending_gratuity' => $gratuity ? $gratuity->ppo_amount : 0
        ]);
    }

    public function export()
    {
        $fileName = "gratuity_data.csv";
        $gratuities = Gratuity::orderBy('id', 'asc')->get();

        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $columns = array('Sl. No.', 'Employee Code', 'Name', 'Relation Name', 'PPO Number', 'PPO Amount', 'Sanctioned Amount', 'Retirement Date', 'PPO Receive Date');

        $callback = function() use($gratuities, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($gratuities as $index => $item) {
                fputcsv($file, array(
                    $index + 1,
                    $item->employee_code,
                    $item->name,
                    $item->relation_name,
                    $item->ppo_number,
                    $item->ppo_amount,
                    $item->sanctioned_amount,
                    \Carbon\Carbon::parse($item->retirement_date)->format('d/m/Y'),
                    \Carbon\Carbon::parse($item->ppo_receive_date)->format('d/m/Y')
                ));
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportPdf()
    {
        $gratuities = Gratuity::orderBy('id', 'asc')->get();
        $pdf = Pdf::loadView('layouts.pages.gratuity.pdf', compact('gratuities'))
                  ->setPaper('legal', 'landscape');
        return $pdf->stream('gratuity_data.pdf');
    }
}
