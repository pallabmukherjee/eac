<?php

namespace App\Http\Controllers\Gratuity;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Loan;
use App\Models\Gratuity;

class LoanController extends Controller
{
    public function index(Request $request) {
        if ($request->ajax()) {
            $loans = Loan::where('emp_code', $request->emp_id)->get();
            return response()->json($loans);
        }
        $url = route('superadmin.gratuity.loan.store');
        $loans = Loan::with('emp')->orderBy('created_at', 'desc')->get();
        $emp = Gratuity::where('loan_status', 1)->orderBy('created_at', 'desc')->get();
        return view("layouts.pages.gratuity.loan.index", compact('url', 'loans', 'emp'));
    }

    public function store(Request $request) {
        $request->validate([
            'emp_code' => 'required|string',
            'bank_name' => 'required|array|min:1',
            'bank_name.*' => 'required|string',
            'loan_amount' => 'required|array|min:1',
            'loan_amount.*' => 'required|numeric|min:0',
            'loan_details' => 'required|array|min:1',
            'loan_details.*' => 'required|string',
        ]);

        $empCode = $request->input('emp_code');
        $bankNames = $request->input('bank_name');
        $loanAmounts = $request->input('loan_amount');
        $loanDetails = $request->input('loan_details');

        foreach ($bankNames as $index => $bankName) {
            Loan::create([
                'emp_code' => $empCode,
                'bank_name' => $bankName,
                'loan_amount' => $loanAmounts[$index],
                'loan_details' => $loanDetails[$index],
            ]);
        }

        return redirect()->back()->with('success', 'Loans have been successfully saved!');
    }

    public function edit($empCode) {
        $loanRecords = Loan::where('emp_code', $empCode)->get();

        return view('layouts.pages.gratuity.loan.edit', compact('loanRecords', 'empCode'));
    }

    public function update(Request $request, $empCode)
{
    // Validate request data
    $request->validate([
        'emp_code' => 'required|string',
        'bank_name' => 'required|array|min:1',
        'bank_name.*' => 'required|string',
        'loan_amount' => 'required|array|min:1',
        'loan_amount.*' => 'required|numeric|min:0',
        'loan_details' => 'required|array|min:1',
        'loan_details.*' => 'required|string',
    ]);

    // Delete old loans for this emp_code
    Loan::where('emp_code', $empCode)->delete();

    // Save new loans
    foreach ($request->bank_name as $index => $bankName) {
        Loan::create([
            'emp_code' => $empCode,
            'bank_name' => $bankName,
            'loan_amount' => $request->loan_amount[$index],
            'loan_details' => $request->loan_details[$index],
        ]);
    }

    // Redirect back with success message
    return redirect()->route('superadmin.gratuity.loan.index')->with('success', 'Loan records updated successfully.');
}



    public function destroy($id) {
        $loan = Loan::findOrFail($id);
        $loan->delete();

        return redirect()->back()->with('success', 'Loan deleted successfully.');
    }
}
