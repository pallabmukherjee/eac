<?php

namespace App\Http\Controllers\Gratuity;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Gratuity;
use App\Models\GratuityRequest;
use App\Models\GratuityBill;
use App\Models\GratuityBillSummary;
use App\Models\Loan;

class GratuityReportController extends Controller
{
    public function index() {
        $totalEmployees = Gratuity::count();
        $totalApplications = GratuityBill::count();
        $totalGratuityPaid = GratuityBillSummary::sum('gratuity_amount');
        $totalOutstandingLoans = Loan::sum('loan_amount');

        return view("layouts.pages.gratuity.report.index", compact(
            'totalEmployees',
            'totalApplications',
            'totalGratuityPaid',
            'totalOutstandingLoans'
        ));
    }

    public function yearlyPaid() {
        $reports = GratuityBillSummary::selectRaw('YEAR(created_at) as year, SUM(gratuity_amount) as total_gratuity, SUM(loan_amount) as total_loan, SUM(total_amount) as total_paid')
            ->groupBy('year')
            ->orderBy('year', 'desc')
            ->get();
        return view("layouts.pages.gratuity.report.yearly", compact('reports'));
    }

    public function monthlyPaid() {
        $reports = GratuityBillSummary::selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, SUM(gratuity_amount) as total_gratuity, SUM(loan_amount) as total_loan, SUM(total_amount) as total_paid')
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();
        return view("layouts.pages.gratuity.report.monthly", compact('reports'));
    }

    public function employeeWise(Request $request) {
        $employees = Gratuity::all();
        $selectedEmployee = $request->get('emp_id');
        $reports = [];
        
        if ($selectedEmployee) {
            $reports = GratuityBillSummary::where('emp_id', $selectedEmployee)
                ->orderBy('created_at', 'desc')
                ->get();
        }
        
        return view("layouts.pages.gratuity.report.employee_wise", compact('employees', 'reports', 'selectedEmployee'));
    }
}
