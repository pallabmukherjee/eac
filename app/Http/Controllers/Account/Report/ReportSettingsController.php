<?php

namespace App\Http\Controllers\Account\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\DetailedHead;
use App\Models\YearlyAccountBalence;
use App\Models\YearlyAccountStatus;
use Carbon\Carbon;


class ReportSettingsController extends Controller {

    public function index() {
        $currentDate = now();
        $currentYear = now()->year;

        if (now()->lessThan(Carbon::create(null, 4, 1))) {
            return redirect()->back()->with('error', 'This operation can only be performed after March.');
        }

        if (YearlyAccountStatus::where('year', $currentYear)->exists()) {
            return redirect()->back()->with('error', 'Current year Report already exists. Operation aborted.');
        }

        $detailedHeads = DetailedHead::all();

        YearlyAccountStatus::create([
            'year' => $currentYear,
        ]);

        foreach ($detailedHeads as $detailedHead) {
            $majorHeadCode = substr($detailedHead->ledgers_head_code, 0, 3);
            $minorHeadCode = substr($detailedHead->ledgers_head_code, 3, 2);

            YearlyAccountBalence::create([
                'year' => $currentYear,
                'ledgers_head_code' => $detailedHead->ledgers_head_code,
                'major_head_code' => $majorHeadCode,
                'minor_head_code' => $minorHeadCode,
                'opening_debit' => $detailedHead->opening_debit,
                'opening_credit' => $detailedHead->opening_credit,
                'closing_debit' => $detailedHead->closing_debit,
                'closing_credit' => $detailedHead->closing_credit,
            ]);

            $detailedHead->update([
                'opening_debit' => $detailedHead->closing_debit,
                'opening_credit' => $detailedHead->closing_credit,
                'debit_amount' => 0,
                'credit_amount' => 0,
            ]);

        }

        return redirect()->back()->with('success', 'Data stored successfully!');
    }

}
