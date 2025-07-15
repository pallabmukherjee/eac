<?php

namespace App\Http\Controllers\Account\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WebsiteSetting;
use App\Models\YearlyAccountBalence;
use App\Models\YearlyAccountStatus;
use PDF;

class MajorHeadIncomeExpenditureController extends Controller {

    public function index(){
        $title = "Major Head Income & Expenditure Summary";
        $url = route('superadmin.account.report.majorHeadIncomeExpenditureReport');
        $yearlyStatuses = YearlyAccountStatus::all();
        return view("layouts.pages.account.report.major_head_form", compact('title', 'url', 'yearlyStatuses'));
    }

    public function report(Request $request) {
        // Validate the selected year
        $request->validate([
            'from_date' => 'required|integer|exists:yearly_account_status,year',
        ]);

        $website = WebsiteSetting::first();

        // Get the selected year
        $selectedYear = $request->input('from_date');

        // Fetch data from YearlyAccountBalence based on the selected year
        $yearlyIncomeBalances = YearlyAccountBalence::where('year', $selectedYear)->where('major_head_code', 'like', '1%')->get();

        $groupedIncomeBalances = $yearlyIncomeBalances->groupBy(function($item) {
            return substr($item->major_head_code, 0, 3); // Group by the first 3 digits
        });

        $groupedIncomeBalances = $groupedIncomeBalances->map(function($group) {
            $sumOpeningCredit = $group->sum('opening_credit');
            $sumClosingCredit = $group->sum('closing_credit');

            // Add the sums to the group's data
            return [
                'balances' => $group,
                'sum_opening_credit' => $sumOpeningCredit,
                'sum_closing_credit' => $sumClosingCredit,
            ];
        });


        $yearlyExpenditureBalances = YearlyAccountBalence::where('year', $selectedYear)->where('major_head_code', 'like', '2%')->get();
        $groupedExpenditureBalances = $yearlyExpenditureBalances->groupBy(function($item) {
            return substr($item->major_head_code, 0, 3); // Group by the first 3 digits
        });

        $groupedExpenditureBalances = $groupedExpenditureBalances->map(function($group) {
            $sumExpenditureOpeningCredit = $group->sum('opening_credit');
            $sumExpenditureClosingCredit = $group->sum('closing_credit');

            // Add the sums to the group's data
            return [
                'balances' => $group,
                'sum_Expenditure_opening_credit' => $sumExpenditureOpeningCredit,
                'sum_Expenditure_closing_credit' => $sumExpenditureClosingCredit,
            ];
        });

        $pdf = PDF::loadView('layouts.pages.account.report.pdf.major-head-pdf', compact('groupedIncomeBalances', 'groupedExpenditureBalances', 'selectedYear', 'website'))
        ->setOption('margin-top', 0)
        ->setOption('margin-left', 0)
        ->setOption('margin-right', 0)
        ->setOption('margin-bottom', 0);

        // You can now pass this data to a view or generate a report, for example, a PDF.
        //return view('layouts.pages.account.report.pdf.major-head-pdf', compact('groupedIncomeBalances', 'groupedExpenditureBalances', 'selectedYear', 'website'));

        return $pdf->stream('major_head_INCOME_EXPENDITURE.pdf');
    }
}
