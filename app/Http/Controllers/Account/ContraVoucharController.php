<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ContraVoucher;
use App\Models\DetailedHead;
use App\Models\WebsiteSetting;
use App\Models\VoucharEditRequest;
use Illuminate\Support\Facades\Auth;
use PDF;

class ContraVoucharController extends Controller {
    public function index() {
        $url = route('superadmin.account.contravoucher.store');
        $selectHead = DetailedHead::where(function($query) {
            $query->where('ledgers_head_code', 'like', '310%')->orWhere('ledgers_head_code', 'like', '320%')->orWhere('ledgers_head_code', 'like', '341%');
        })->orderBy('created_at', 'desc')->get();
        $bank = DetailedHead::where('ledgers_head_code', 'like', '450%')->orderBy('created_at', 'desc')->get();
        $cashinhandbalence = DetailedHead::where("ledgers_head_code", 4501001)->firstOrFail();
        return view("layouts.pages.account.contra_voucher.index", compact('url', 'selectHead', 'bank', 'cashinhandbalence'));
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'date' => 'required|date',
            'from_head.*' => 'required|string',
            'from_bank.*' => 'required|string',
            'to_head.*' => 'required|string',
            'to_bank.*' => 'required|string',
            'cash_amount.*' => 'nullable|numeric',
            'cheque_amount.*' => 'nullable|numeric',
            'cheque_no.*' => 'nullable|string',
            'cheque_date.*' => 'nullable|date',
            'online_amount.*' => 'nullable|numeric',
            'online_remarks.*' => 'nullable|string',
        ]);

        $billNo = $this->generateBillNo();
        $unicVoucharID = 'CV-' . uniqid(20);

        foreach ($request->from_head as $key => $from_head) {
            $cashAmount = $request->cash_amount[$key] ?? null;
            $onlineAmount = $request->online_amount[$key] ?? null;
            $chequeAmount = $request->cheque_amount[$key] ?? null;
            $totalAmount = $cashAmount + $onlineAmount + $chequeAmount;

            ContraVoucher::create([
                'voucher_id' => $unicVoucharID,
                'bill_no' => $billNo,
                'date' => $request->date,
                'from_head' => $from_head,
                'from_bank' => $request->from_bank[$key],
                'to_head' => $request->to_head[$key],
                'to_bank' => $request->to_bank[$key],
                'cash_amount' => $request->cash_amount[$key] ?? null,
                'cheque_amount' => $request->cheque_amount[$key] ?? null,
                'cheque_no' => $request->cheque_no[$key] ?? null,
                'cheque_date' => $request->cheque_date[$key] ?? null,
                'online_amount' => $request->online_amount[$key] ?? null,
                'online_remarks' => $request->online_remarks[$key] ?? null,
            ]);

            $fromHead = DetailedHead::where('ledgers_head_code', $from_head)->first();
            if ($fromHead) {
                $fromHead->debit_amount += $totalAmount;
                $fromHead->save();
            }

            $fromBank = DetailedHead::where('ledgers_head_code', $request->from_bank[$key])->first();
            if ($fromBank) {
                $fromBank->credit_amount += $totalAmount;
                $fromBank->save();
            }

            $toHead = DetailedHead::where('ledgers_head_code', $request->to_head[$key])->first();
            if ($toHead) {
                $toHead->credit_amount += $totalAmount;
                $toHead->save();
            }
            $toBank = DetailedHead::where('ledgers_head_code', $request->to_bank[$key])->first();
            if ($toBank) {
                $toBank->debit_amount += $totalAmount;
                $toBank->save();
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

        return redirect()->back()->with('success', 'Contra Voucher successfully stored!');
    }

    private function generateBillNo() {
        $currentYear = date('Y');
        $lastVoucher = ContraVoucher::whereYear('created_at', $currentYear)->orderBy('created_at', 'desc')->first();
        if ($lastVoucher) {
            $lastNumber = (int) substr($lastVoucher->bill_no, 5);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        $newBillNo = "CV-" . str_pad($newNumber, 5, '0', STR_PAD_LEFT);
        return $newBillNo;
    }

    public function list() {
        $contraVouchers = ContraVoucher::orderBy('created_at', 'desc')->get();
        $groupedVouchers = $contraVouchers->groupBy('voucher_id');
        $mergedVouchers = $groupedVouchers->map(function($voucherGroup) {
            $firstRow = $voucherGroup->first();
            return $firstRow;
        });
        return view("layouts.pages.account.contra_voucher.list", compact('mergedVouchers'));
    }

    public function show($voucher_id) {
        $contraVoucher = ContraVoucher::where('voucher_id', $voucher_id)->firstOrFail();
        $contraVouchers = ContraVoucher::where('voucher_id', $voucher_id)->orderBy('created_at', 'desc')->get();
        return view("layouts.pages.account.contra_voucher.show", compact('contraVoucher', 'contraVouchers'));
    }

    public function generatePDF($voucher_id){
        $contraVoucher = ContraVoucher::where('voucher_id', $voucher_id)->firstOrFail();
        $contraVouchers = ContraVoucher::where('voucher_id', $voucher_id)->get();

        $website = WebsiteSetting::first();

        $totalCash = $contraVouchers->sum('cash_amount');
        $totalCheque = $contraVouchers->sum('cheque_amount');
        $totalOnline = $contraVouchers->sum('online_amount');
        $totalAmount = $totalCash + $totalCheque + $totalOnline;
        $totalAmountInWords = 'Rupees ' . numberToWords($totalAmount) . ' Only';

        $pdf = PDF::loadView('layouts.pages.account.contra_voucher.pdf', compact(
            'website',
            'contraVoucher',
            'contraVouchers',
            'totalCash',
            'totalCheque',
            'totalOnline',
            'totalAmount',
            'totalAmountInWords'
        ))
        ->setOption('margin-top', 0)
        ->setOption('margin-left', 0)
        ->setOption('margin-right', 0)
        ->setOption('margin-bottom', 0);

        return $pdf->stream('Contra_Voucher_' . $contraVoucher->bill_no . '.pdf');
    }

    public function editRequest($voucher_id) {
        $voucher = ContraVoucher::where('voucher_id', $voucher_id)->firstOrFail();

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
        $url = route('superadmin.account.contravoucher.update', $voucher_id);
        $voucherEntries = ContraVoucher::where('voucher_id', $voucher_id)->get();

        if ($voucherEntries->isEmpty()) {
            abort(404, 'Voucher not found.');
        }

        $selectHead = DetailedHead::where(function($query) {
            $query->where('ledgers_head_code', 'like', '310%')
                ->orWhere('ledgers_head_code', 'like', '320%')
                ->orWhere('ledgers_head_code', 'like', '341%');
        })->orderBy('created_at', 'desc')->get();

        $bank = DetailedHead::where('ledgers_head_code', 'like', '450%')->orderBy('created_at', 'desc')->get();

        $cashinhandbalence = DetailedHead::where("ledgers_head_code", 4501001)->firstOrFail();

        return view("layouts.pages.account.contra_voucher.edit", compact(
            'url',
            'voucherEntries',
            'selectHead',
            'bank',
            'cashinhandbalence'
        ));
    }


    public function update(Request $request, $voucher_id) {
        $validated = $request->validate([
            'date' => 'required|date',
            'from_head.*' => 'required|string',
            'from_bank.*' => 'required|string',
            'to_head.*' => 'required|string',
            'to_bank.*' => 'required|string',
            'cash_amount.*' => 'nullable|numeric',
            'cheque_amount.*' => 'nullable|numeric',
            'cheque_no.*' => 'nullable|string',
            'cheque_date.*' => 'nullable|date',
            'online_amount.*' => 'nullable|numeric',
            'online_remarks.*' => 'nullable|string',
        ]);

        $existingEntries = ContraVoucher::where('voucher_id', $voucher_id)->get();

        if ($existingEntries->isEmpty()) {
            return redirect()->back()->with('error', 'Voucher not found.');
        }

        // Reverse previous calculations
        foreach ($existingEntries as $entry) {
            $totalAmount = ($entry->cash_amount ?? 0) + ($entry->online_amount ?? 0) + ($entry->cheque_amount ?? 0);

            $fromHead = DetailedHead::where('ledgers_head_code', $entry->from_head)->first();
            if ($fromHead) {
                $fromHead->debit_amount -= $totalAmount;
                $fromHead->save();
            }

            $fromBank = DetailedHead::where('ledgers_head_code', $entry->from_bank)->first();
            if ($fromBank) {
                $fromBank->credit_amount -= $totalAmount;
                $fromBank->save();
            }

            $toHead = DetailedHead::where('ledgers_head_code', $entry->to_head)->first();
            if ($toHead) {
                $toHead->credit_amount -= $totalAmount;
                $toHead->save();
            }

            $toBank = DetailedHead::where('ledgers_head_code', $entry->to_bank)->first();
            if ($toBank) {
                $toBank->debit_amount -= $totalAmount;
                $toBank->save();
            }
        }

        // Delete old entries
        ContraVoucher::where('voucher_id', $voucher_id)->delete();

        $billNo = $existingEntries->first()->bill_no;

        // Insert new data
        foreach ($request->from_head as $key => $from_head) {
            $cashAmount = $request->cash_amount[$key] ?? 0;
            $onlineAmount = $request->online_amount[$key] ?? 0;
            $chequeAmount = $request->cheque_amount[$key] ?? 0;
            $totalAmount = $cashAmount + $onlineAmount + $chequeAmount;

            ContraVoucher::create([
                'voucher_id' => $voucher_id,
                'bill_no' => $billNo,
                'date' => $request->date,
                'from_head' => $from_head,
                'from_bank' => $request->from_bank[$key],
                'to_head' => $request->to_head[$key],
                'to_bank' => $request->to_bank[$key],
                'cash_amount' => $cashAmount,
                'cheque_amount' => $chequeAmount,
                'cheque_no' => $request->cheque_no[$key] ?? null,
                'cheque_date' => $request->cheque_date[$key] ?? null,
                'online_amount' => $onlineAmount,
                'online_remarks' => $request->online_remarks[$key] ?? null,
                'edit_status' => 0,
            ]);

            // Re-apply amounts to respective accounts
            $fromHead = DetailedHead::where('ledgers_head_code', $from_head)->first();
            if ($fromHead) {
                $fromHead->debit_amount += $totalAmount;
                $fromHead->save();
            }

            $fromBank = DetailedHead::where('ledgers_head_code', $request->from_bank[$key])->first();
            if ($fromBank) {
                $fromBank->credit_amount += $totalAmount;
                $fromBank->save();
            }

            $toHead = DetailedHead::where('ledgers_head_code', $request->to_head[$key])->first();
            if ($toHead) {
                $toHead->credit_amount += $totalAmount;
                $toHead->save();
            }

            $toBank = DetailedHead::where('ledgers_head_code', $request->to_bank[$key])->first();
            if ($toBank) {
                $toBank->debit_amount += $totalAmount;
                $toBank->save();
            }
        }

        // Recalculate all closing balances
        $balanceHeads = DetailedHead::all();
        foreach ($balanceHeads as $item) {
            $totalCreditAmount = $item->opening_credit + $item->credit_amount;
            $totalDebitAmount = $item->opening_debit + $item->debit_amount;
            if ($totalCreditAmount > $totalDebitAmount) {
                $item->update([
                    'closing_credit' => $totalCreditAmount - $totalDebitAmount,
                    'closing_debit' => 0.00,
                ]);
            } else {
                $item->update([
                    'closing_debit' => $totalDebitAmount - $totalCreditAmount,
                    'closing_credit' => 0.00,
                ]);
            }
        }

        return redirect()->route('superadmin.account.contravoucher.list')->with('success', 'Contra Voucher updated successfully!');
    }


}
