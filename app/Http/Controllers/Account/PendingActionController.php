<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PVoucharDeduct;
use App\Models\PaymentVoucher;
use App\Models\MajorHead;
use App\Models\DetailedHead;
use App\Models\LedgerReport;
use App\Models\RvChequeList;

class PendingActionController extends Controller
{
    public function index() {
        $pendingvouchar = PVoucharDeduct::orderBy('created_at', 'desc')->get()
                                    ->groupBy('voucher_id')
                                    ->map(function ($item) {
                                        return $item->first(); // Get the first record for each voucher_id
                                    });
        return view("layouts.pages.account.payment_deduction.list", compact('pendingvouchar'));
    }

    public function show($voucher_id) {
        $paymentVoucher = PaymentVoucher::where('p_voucher_id', $voucher_id)->with(['beneficiary', 'schemeFund'])->firstOrFail();
        $ledgerItem = PVoucharDeduct::where('voucher_id', $voucher_id)->get();
        $pay = PVoucharDeduct::where('voucher_id', $voucher_id)->sum('amount');
        $totalAmountInWords = 'Rupees ' . numberToWords($pay) . ' Only';

        $majorHeadIds = MajorHead::where(function($query) {
            $query->where('code', 'like', '310')
                  ->orWhere('code', 'like', '320')
                  ->orWhere('code', 'like', '341');
        })->pluck('id');
        $schemefund = DetailedHead::whereIn('major_head_code', $majorHeadIds)->orderBy('created_at', 'desc')->get();
        $bank = DetailedHead::where('ledgers_head_code', 'like', '450%')->orderBy('created_at', 'desc')->get();
        return view("layouts.pages.account.payment_deduction.show", compact('paymentVoucher', 'ledgerItem', 'pay', 'totalAmountInWords', 'schemefund', 'bank'));
    }

    public function store(Request $request, $voucher_id) {
        $billNo = $this->generateBillNo();
        $unicID = 'PV-' . uniqid(20);
        $pv = PaymentVoucher::where('p_voucher_id', $voucher_id)->with(['beneficiary', 'schemeFund'])->firstOrFail();

        $paymentVoucher = PaymentVoucher::create([
            'p_voucher_id' => $unicID,
            'bill_no' => $billNo,
            'date' => now()->format('Y-m-d'),
            'payee' => $pv->payee,
            'scheme_fund' => $request->has('scheme_fund') ? $request->input('scheme_fund') : $pv->scheme_fund,
            'payment_mode' => $pv->payment_mode,
            'reference_number' => $pv->reference_number,
            'reference_date' => $pv->reference_date,
            'narration' => $pv->narration,
            'bank' => $request->has('bank') ? $request->input('bank') : $pv->bank,
        ]);

        $ledgerItems = PVoucharDeduct::where('voucher_id', $voucher_id)->get();
        foreach ($ledgerItems as $item) {
            LedgerReport::create([
                'voucher_id' => $unicID,
                'ledger' => $item->ledger_head,
                'amount' => $item->amount,
                'pay_deduct' => 1,
            ]);

            $schemeFund = DetailedHead::where('ledgers_head_code', $request->has('scheme_fund') ? $request->input('scheme_fund') : $pv->scheme_fund)->first();
            if ($schemeFund) {
                $payschemeFund = 0;
                $payschemeFund += $item->amount;
                $newSchemeDebitAmount = $schemeFund->debit_amount - $payschemeFund;
                $schemeFund->update([
                    'debit_amount' => $newSchemeDebitAmount,
                ]);
            }

            $bankFund = DetailedHead::where('ledgers_head_code', $request->has('bank') ? $request->input('bank') : $pv->bank)->first();
            if ($bankFund) {
                $paybankFund = 0;
                $paybankFund += $item->amount;
                $newBankDebitAmount = $bankFund->credit_amount - $paybankFund;
                $bankFund->update([
                    'credit_amount' => $newBankDebitAmount,
                ]);
            }


            $detailedHeads = DetailedHead::where('ledgers_head_code', $item->ledger_head)->get();
            $ledgerFirstDigit = substr($item->ledger_head, 0, 1);
            foreach ($detailedHeads as $detailedHead) {
                $newCreditAmount = $detailedHead->credit_amount;
                $newDebitAmount = $detailedHead->debit_amount;

                if (in_array($ledgerFirstDigit, ['1', '3'])) {
                    $newDebitAmount = $detailedHead->debit_amount - $item->amount;
                } elseif (in_array($ledgerFirstDigit, ['2', '4'])) {
                    $newDebitAmount = $detailedHead->debit_amount + $item->amount;
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
                    'closing_debit' => 0.00, // Set closing_credit to 0.00
                ]);
            } else {
                $newClosingDebit = $totalDebitAmount - $totalCreditAmount;
                $item->update([
                    'closing_debit' => $newClosingDebit,
                    'closing_credit' => 0.00,
                ]);
            }
        }

        PVoucharDeduct::where('voucher_id', $voucher_id)->delete();



        return redirect()->route('superadmin.account.pendingaction.index');
    }

    private function generateBillNo() {
        $currentYear = date('Y');
        $lastVoucher = PaymentVoucher::whereYear('created_at', $currentYear)->orderBy('created_at', 'desc')->first();
        if ($lastVoucher) {
            $lastNumber = (int) substr($lastVoucher->bill_no, 5);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        $newBillNo = "PV-" . str_pad($newNumber, 5, '0', STR_PAD_LEFT);
        return $newBillNo;
    }

    public function unclearedCheque() {
        $pendingvouchar = RvChequeList::orderBy('created_at', 'desc')->get();
        return view("layouts.pages.account.payment_deduction.uncleared_cheque.list", compact('pendingvouchar'));
    }

    public function unclearedChequeShow($voucher_id) {
        $ledgerItem = RvChequeList::where('voucher_id', $voucher_id)->get();
        $bank = DetailedHead::where('ledgers_head_code', 'like', '450%')->orderBy('created_at', 'desc')->get();
        return view("layouts.pages.account.payment_deduction.uncleared_cheque.show", compact('ledgerItem', 'bank'));
    }

    public function unclearedChequeStore(Request $request) {
        $cheque = RvChequeList::where('id', $request->id)->first();

        if ($cheque) {
            // Update the status to 2
            $cheque->status = 2;
            $cheque->cheque_submit_bank = $request->bank_code;
            $cheque->save(); // Save the updated model

            // Check if the amount is greater than 0
            if ($cheque->amount > 0) {
                // Find the detailed head by bank name
                $detailedHead = DetailedHead::where('ledgers_head_code', $request->bank_code)->first();

                // If the detailed head exists, update the credit amount
                if ($detailedHead) {
                    $detailedHead->credit_amount += $cheque->amount;
                    $detailedHead->save();
                }

                $balenceCalculate = DetailedHead::all();
                foreach ($balenceCalculate as $item) {
                    $totalCreditAmount = $item->opening_credit + $item->credit_amount;
                    $totalDebitAmount = $item->opening_debit + $item->debit_amount;
                    if ($totalCreditAmount > $totalDebitAmount) {
                        $newClosingCredit = $totalCreditAmount - $totalDebitAmount;
                        $item->update([
                            'closing_credit' => $newClosingCredit,
                            'closing_debit' => 0.00, // Set closing_credit to 0.00
                        ]);
                    } else {
                        $newClosingDebit = $totalDebitAmount - $totalCreditAmount;
                        $item->update([
                            'closing_debit' => $newClosingDebit,
                            'closing_credit' => 0.00,
                        ]);
                    }
                }
            }

            // Redirect to a relevant page with a success message
            return back()->with('success', 'Cheque status updated successfully!');
        }

        return back()->with('error', 'Cheque not found!');
    }


    public function updateStatus(Request $request) {
        $request->validate([
            'id' => 'required',
            'status' => 'required|in:1,2,3',
        ]);

        $item = RvChequeList::find($request->id);

        if ($item) {
            $item->status = $request->status;
            $item->save();

            return response()->json(['success' => true, 'message' => 'Status updated successfully.']);
        } else {
            return response()->json(['success' => false, 'message' => 'Item not found.']);
        }
    }



}
