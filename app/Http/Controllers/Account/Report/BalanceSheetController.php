<?php

namespace App\Http\Controllers\Account\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\YearlyAccountStatus;
use App\Models\WebsiteSetting;
use App\Models\MajorHead;
use App\Models\YearlyAccountBalence;
use PDF;

class BalanceSheetController extends Controller {

    public function index(){
        $title = "Balance Sheet";
        $url = route('superadmin.account.report.balanceSheetReport');
        $yearlyStatuses = YearlyAccountStatus::all();
        return view("layouts.pages.account.report.major_head_form", compact('title', 'url', 'yearlyStatuses'));
    }


    public function report(Request $request) {
        $request->validate([
            'from_date' => 'required|integer|exists:yearly_account_status,year',
        ]);
        $website = WebsiteSetting::first();

        $selectedYear = $request->input('from_date');
        $previousYear = $selectedYear - 1; // Calculate the previous year

        // Reference numbers for the first table
        $referenceNumbers1 = ['B-1', 'B-2', 'B-3'];
        // Reference number for the second table
        $referenceNumbers2 = ['B-4'];
        // Reference numbers for the third table
        $referenceNumbers3 = ['B-5', 'B-6'];
        // Reference numbers for the fourth table
        $referenceNumbers4 = ['B-11'];
        // Reference numbers for the fifth table
        $referenceNumbers5 = ['B-12', 'B-13'];
        // Reference numbers for the sixth table
        $referenceNumbers6 = ['B-14', 'B-15', 'B-16', 'B-17', 'B-18', 'B-7', 'B-8', 'B-9', 'B-10'];
        // Reference numbers for the seventh table
        $referenceNumbers7 = ['B-19'];
        // Reference numbers for the eighth table
        $referenceNumbers8 = ['B-20'];

        // Function to fetch and sum balances
        $summedBalances = function($referenceNumbers) use ($selectedYear, $previousYear) {
            $majorHeads = MajorHead::whereIn('schedule_reference_no', $referenceNumbers)->get();
            $summedBalances = [];

            $yearlyBalances = YearlyAccountBalence::whereIn('year', [$selectedYear, $previousYear])
                ->whereIn('major_head_code', $majorHeads->pluck('code'))
                ->get();

            foreach ($yearlyBalances as $balance) {
                if (!isset($summedBalances[$balance->major_head_code])) {
                    $summedBalances[$balance->major_head_code] = [
                        'major_head' => $majorHeads->firstWhere('code', $balance->major_head_code),
                        'yearly_data' => [
                            $previousYear => [
                                'opening_debit' => 0,
                                'opening_credit' => 0,
                                'closing_debit' => 0,
                                'closing_credit' => 0,
                            ],
                            $selectedYear => [
                                'opening_debit' => 0,
                                'opening_credit' => 0,
                                'closing_debit' => 0,
                                'closing_credit' => 0,
                            ],
                        ],
                    ];
                }

                // Sum the balances for each year
                $summedBalances[$balance->major_head_code]['yearly_data'][$balance->year]['opening_debit'] += $balance->opening_debit;
                $summedBalances[$balance->major_head_code]['yearly_data'][$balance->year]['opening_credit'] += $balance->opening_credit;
                $summedBalances[$balance->major_head_code]['yearly_data'][$balance->year]['closing_debit'] += $balance->closing_debit;
                $summedBalances[$balance->major_head_code]['yearly_data'][$balance->year]['closing_credit'] += $balance->closing_credit;
            }

            return $summedBalances;
        };

        // Fetch balances for all reference number groups
        $summedBalances1 = $summedBalances($referenceNumbers1);
        $summedBalances2 = $summedBalances($referenceNumbers2);
        $summedBalances3 = $summedBalances($referenceNumbers3);
        $summedBalances4 = $summedBalances($referenceNumbers4);
        $summedBalances5 = $summedBalances($referenceNumbers5);
        $summedBalances6 = $summedBalances($referenceNumbers6);
        $summedBalances7 = $summedBalances($referenceNumbers7);
        $summedBalances8 = $summedBalances($referenceNumbers8);

        // return view('layouts.pages.account.report.pdf.balance_sheet', compact(
        //     'summedBalances1',
        //     'summedBalances2',
        //     'summedBalances3',
        //     'summedBalances4',
        //     'summedBalances5',
        //     'summedBalances6',
        //     'summedBalances7',
        //     'summedBalances8',
        //     'selectedYear',
        //     'previousYear',
        //     'website'
        // ));

        $pdf = PDF::loadView('layouts.pages.account.report.pdf.balance_sheet', compact(
            'summedBalances1',
            'summedBalances2',
            'summedBalances3',
            'summedBalances4',
            'summedBalances5',
            'summedBalances6',
            'summedBalances7',
            'summedBalances8',
            'selectedYear',
            'previousYear',
            'website'
        ))
        ->setOption('margin-top', 0)
        ->setOption('margin-left', 0)
        ->setOption('margin-right', 0)
        ->setOption('margin-bottom', 0)
        ->setPaper('a4', 'portrait')
        ->setOption('dpi', 120)
        ->setOption('defaultFont', 'sans-serif');

        return $pdf->stream('balance_sheet.pdf');
    }

}
