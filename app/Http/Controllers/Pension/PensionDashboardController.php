<?php

namespace App\Http\Controllers\Pension;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pensioner;
use App\Models\PensionerReport;
use App\Models\PensionerOtherBill;
use Carbon\Carbon;

class PensionDashboardController extends Controller
{
    public function index() {
        $totalPensioners = Pensioner::where('no_claimant', 0)->count();
        $totalDead = Pensioner::where('alive_status', 2)->count();
        $total5YearsCompleted = Pensioner::where('five_year_date', '<', now())->count();
        $total80YearsCompleted = Pensioner::where('dob', '<', now()->subYears(80))->count();
        $totalReportsGenerated = PensionerReport::count();
        $totalLifeCertificateYes = Pensioner::where('life_certificate', 1)->count();
        
        $latestReports = PensionerReport::orderBy('created_at', 'desc')->take(5)->get();
        $latestOtherBills = PensionerOtherBill::orderBy('created_at', 'desc')->take(5)->get();

        return view("layouts.pages.pension.dashboard", compact(
            'totalPensioners',
            'totalDead',
            'total5YearsCompleted',
            'total80YearsCompleted',
            'totalReportsGenerated',
            'totalLifeCertificateYes',
            'latestReports',
            'latestOtherBills'
        ));
    }
}
