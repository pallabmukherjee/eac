<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JournalVoucher;
use App\Models\JvLedgerHead;
use App\Models\DetailedHead;
use App\Models\WebsiteSetting;
use App\Models\VoucharEditRequest;
use Illuminate\Support\Facades\Auth;
use PDF;

class JournalVoucharController extends Controller
{
    public function index() {
        $url = route('superadmin.account.journalvoucher.store');
        $ledger = DetailedHead::orderBy('ledgers_head_code', 'asc')->get();
        return view("layouts.pages.account.journal_voucher.index", compact('url', 'ledger'));
    }

    public function store(Request $request) {
        $billNo = $this->generateBillNo();
        $voucahrID = 'JV-' . uniqid(20);
        $request->validate([
            'date' => 'required|date',
            'narration' => 'string',
            'remarks' => 'string',
            'ledger_head.*' => 'nullable|numeric',
            'amount.*' => 'nullable|numeric',
            'crdr.*' => 'nullable|numeric',
        ]);

        $paymentVoucher = JournalVoucher::create([
            'voucher_id' => $voucahrID,
            'bill_no' =>  $billNo,
            'date' => $request->date,
            'narration' => $request->narration,
            'remarks' => $request->remarks,
        ]);

        foreach ($request->ledger_head as $index => $ledger) {
            JvLedgerHead::create([
                'voucher_id' => $voucahrID,
                'ledger_head' => $ledger,
                'amount' => $request->amount[$index],
                'crdr' => $request->crdr[$index],
            ]);

            $detailedHeads = DetailedHead::where('ledgers_head_code', $ledger)->get();
            foreach ($detailedHeads as $detailedHead) {
                $newCreditAmount = $detailedHead->debit_amount;
                $newDebitAmount = $detailedHead->credit_amount;

                if ($request->crdr[$index] == 1) {
                    $newCreditAmount = $detailedHead->credit_amount + $request->amount[$index];
                } elseif ($request->crdr[$index] == 2) {
                    $newDebitAmount = $detailedHead->debit_amount + $request->amount[$index];
                }

                $detailedHead->update([
                    'credit_amount' => $newCreditAmount,
                    'debit_amount' => $newDebitAmount,
                ]);
            }
        }


        $balenceCalculate = DetailedHead::all();
        foreach ($balenceCalculate as $item) {
            $totalCreditAmount = $item->opening_credit + $item->credit_amount;
            $totalDebitAmount = $item->opening_debit + $item->debit_amount;
            if ($totalCreditAmount > $totalDebitAmount) {
                $newClosingCredit = $totalCreditAmount - $totalDebitAmount;
                $item->update([
                    'closing_credit' => $newClosingCredit,
                    'closing_debit' => 0.00,
                ]);
            } else {
                $newClosingDebit = $totalDebitAmount - $totalCreditAmount;
                $item->update([
                    'closing_debit' => $newClosingDebit,
                    'closing_credit' => 0.00,
                ]);
            }
        }

        return redirect()->back()->with('success', 'Journal Voucher successfully created!');
    }

    private function generateBillNo() {
        $currentYear = date('Y');
        $lastVoucher = JournalVoucher::whereYear('created_at', $currentYear)->orderBy('created_at', 'desc')->first();
        if ($lastVoucher) {
            $lastNumber = (int) substr($lastVoucher->bill_no, 5);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        $newBillNo = "JV-" . str_pad($newNumber, 5, '0', STR_PAD_LEFT);
        return $newBillNo;
    }

    public function list() {
        $journalVouchers = JournalVoucher::orderBy('created_at', 'desc')->get();
        return view("layouts.pages.account.journal_voucher.list", compact('journalVouchers'));
    }

    public function show($voucher_id) {
        $journalVoucher = JournalVoucher::where('voucher_id', $voucher_id)->firstOrFail();
        $journalVouchers = JvLedgerHead::where('voucher_id', $voucher_id)->orderBy('created_at', 'desc')->get();
        return view("layouts.pages.account.journal_voucher.show", compact('journalVoucher', 'journalVouchers'));
    }

    public function generatePDF($voucher_id){
        $journalVoucher = JournalVoucher::where('voucher_id', $voucher_id)->firstOrFail();
        $ledgerItem = JvLedgerHead::with('ledgerHead')->where('voucher_id', $voucher_id)->get();

        $website = WebsiteSetting::first();

        $totalCreditAmount = $ledgerItem->where('crdr', 1)->sum('amount');
        $totalDebitAmount = $ledgerItem->where('crdr', 2)->sum('amount');

        $totalAmountInWords = 'Rupees ' . numberToWords($totalCreditAmount) . ' Only';

        $pdf = PDF::loadView('layouts.pages.account.journal_voucher.pdf', compact(
            'website',
            'journalVoucher',
            'ledgerItem',
            'totalCreditAmount',
            'totalDebitAmount',
            'totalAmountInWords'
        ))
        ->setOption('margin-top', 0)
        ->setOption('margin-left', 0)
        ->setOption('margin-right', 0)
        ->setOption('margin-bottom', 0);

        return $pdf->stream('Journal_voucher_' . $journalVoucher->bill_no . '.pdf');
    }

    public function editRequest($voucher_id) {
        $voucher = JournalVoucher::where('voucher_id', $voucher_id)->firstOrFail();

        // Update edit_status to 1
        $voucher->edit_status = 3;
        $voucher->save();

        VoucharEditRequest::create([
            'vouchar_id' => $voucher_id,
            'bill_no'    => $voucher->bill_no,
            'user_id'    => Auth::id(),
            'edit_status' => 0,
        ]);

        // Redirect back or to another page with success message
        return redirect()->back()->with('success', 'Edit request submitted successfully.');
    }


    public function edit($voucher_id) {
        $url = route('superadmin.account.journalvoucher.update', $voucher_id);
        $journalVoucher = JournalVoucher::where('voucher_id', $voucher_id)->firstOrFail();
        $ledgerHeads = JvLedgerHead::where('voucher_id', $voucher_id)->get();
        $ledger = DetailedHead::orderBy('ledgers_head_code', 'asc')->get();

        return view("layouts.pages.account.journal_voucher.edit", compact('url', 'journalVoucher', 'ledgerHeads', 'ledger'));
    }

    public function update(Request $request, $voucher_id) {
        $request->validate([
            'date' => 'required|date',
            'narration' => 'string|nullable',
            'remarks' => 'string|nullable',
            'ledger_head.*' => 'nullable|numeric',
            'amount.*' => 'nullable|numeric',
            'crdr.*' => 'nullable|numeric',
        ]);

        $journalVoucher = JournalVoucher::where('voucher_id', $voucher_id)->firstOrFail();
        $oldEntries = JvLedgerHead::where('voucher_id', $voucher_id)->get();

        // 1. Reverse old effects
        foreach ($oldEntries as $entry) {
            $head = DetailedHead::where('ledgers_head_code', $entry->ledger_head)->first();
            if ($head) {
                if ($entry->crdr == 1) {
                    $head->credit_amount -= $entry->amount;
                } elseif ($entry->crdr == 2) {
                    $head->debit_amount -= $entry->amount;
                }
                $head->save();
            }
        }

        // 2. Delete old rows
        JvLedgerHead::where('voucher_id', $voucher_id)->delete();

        // 3. Update Journal Voucher main record
        $journalVoucher->update([
            'date' => $request->date,
            'narration' => $request->narration,
            'remarks' => $request->remarks,
            'edit_status' => 0,
        ]);

        // 4. Create new JV Ledger Heads and apply effects
        foreach ($request->ledger_head as $index => $ledgerCode) {
            $amount = $request->amount[$index];
            $crdr = $request->crdr[$index];

            JvLedgerHead::create([
                'voucher_id' => $voucher_id,
                'ledger_head' => $ledgerCode,
                'amount' => $amount,
                'crdr' => $crdr,
            ]);

            $head = DetailedHead::where('ledgers_head_code', $ledgerCode)->first();
            if ($head) {
                if ($crdr == 1) {
                    $head->credit_amount += $amount;
                } elseif ($crdr == 2) {
                    $head->debit_amount += $amount;
                }
                $head->save();
            }
        }

        // 5. Recalculate closing balances
        $balenceCalculate = DetailedHead::all();
        foreach ($balenceCalculate as $item) {
            $totalCreditAmount = $item->opening_credit + $item->credit_amount;
            $totalDebitAmount = $item->opening_debit + $item->debit_amount;
            if ($totalCreditAmount > $totalDebitAmount) {
                $newClosingCredit = $totalCreditAmount - $totalDebitAmount;
                $item->update([
                    'closing_credit' => $newClosingCredit,
                    'closing_debit' => 0.00,
                ]);
            } else {
                $newClosingDebit = $totalDebitAmount - $totalCreditAmount;
                $item->update([
                    'closing_debit' => $newClosingDebit,
                    'closing_credit' => 0.00,
                ]);
            }
        }

        return redirect()->route('superadmin.account.journalvoucher.list')->with('success', 'Journal Voucher updated successfully.');
    }

}
