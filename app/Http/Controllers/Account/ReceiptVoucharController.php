<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MajorHead;
use App\Models\DetailedHead;
use App\Models\ReceiptVoucher;
use App\Models\RvChequeList;
use App\Models\RvLedgerHead;
use App\Models\WebsiteSetting;
use App\Models\VoucharEditRequest;
use Illuminate\Support\Facades\Auth;
use PDF;

class ReceiptVoucharController extends Controller{

    public function index() {
        $url = route('superadmin.account.receiptvoucher.store');
        $selectHead = DetailedHead::where(function($query) {
            $query->where('ledgers_head_code', 'not like', '450%');
        })->orderBy('ledgers_head_code', 'asc')->get();

        $ledger = DetailedHead::orderBy('created_at', 'desc')->get();
        $bank = DetailedHead::where('ledgers_head_code', 'like', '450%')->orderBy('created_at', 'desc')->get();
        return view("layouts.pages.account.receipt_voucher.index", compact('url', 'selectHead', 'ledger', 'bank'));
    }

    public function store(Request $request){
        $unicID = 'RV-' . uniqid(20);
        $billNo = $this->generateBillNo();

        $receiptVoucher = ReceiptVoucher::create([
            'voucher_id' => $unicID,
            'bill_no' => $billNo,
            'date' => $request->date,
        ]);


        $ledgerData = $request->input('head');
        $cashAmountData = $request->input('cash_amount');
        $onlineHeadData = $request->input('online_head');
        $onlineAmountData = $request->input('online_amount');
        $remarksData = $request->input('remarks');
        $chequeTotalAmountData = $request->input('cheque_total_amount');

        foreach ($ledgerData as $index => $head) {
            $cashAmount = $cashAmountData[$index] ?? 0.00;
            $onlineAmount = $onlineAmountData[$index] ?? 0.00;
            $chequeAmount = $chequeTotalAmountData[$index] ?? 0.00;
            $totalAmount = $cashAmount + $onlineAmount + $chequeAmount;

            RvLedgerHead::create([
                'voucher_id' => $unicID,
                'ledger_head' => $head,
                'cash_head' => $cashAmountData[$index] ? 4501001 : null,
                'cash_amount' => $cashAmountData[$index] ?? 0.00,
                'cheque_amount' => $chequeTotalAmountData[$index] ?? 0.00,
                'online_head' => $onlineHeadData[$index] ?? null,
                'online_amount' => $onlineAmountData[$index] ?? 0.00,
                'remarks' => $remarksData[$index] ?? null,
            ]);

            if ($cashAmount > 0) {
                $detailedHead = DetailedHead::where('ledgers_head_code', 4501001)->first();

                if ($detailedHead) {
                    $detailedHead->debit_amount += $cashAmount;
                    $detailedHead->save();
                }
            }

            if ($onlineAmount > 0) {
                $detailedHead = DetailedHead::where('ledgers_head_code', $onlineHeadData[$index] ?? null)->first();
                if ($detailedHead) {
                    $detailedHead->credit_amount += $onlineAmount;
                    $detailedHead->save();
                }
            }

            $detailedHead = DetailedHead::where('ledgers_head_code', $head)->first();
            if ($detailedHead) {
                $detailedHead->credit_amount += $totalAmount;
                $detailedHead->save();
            }
        }


        if ($request->has('cheque_checkbox')) {
            $depositorNames = $request->input('depositor_name');
            $bankData = $request->input('bank');
            $chequeNumbers = $request->input('cheque_no');
            $chequeBank = $request->input('cheque_bank');
            $chequeAmounts = $request->input('cheque_amount');


            foreach ($depositorNames as $index => $depositor) {
                RvChequeList::create([
                    'voucher_id' => $unicID,
                    'bill_no' => $billNo,
                    'depositor_name' => $depositor['name'],
                    'bank_name' => $bankData[$index]['head_code'],
                    'cheque_no' => $chequeNumbers[$index]['cheque_number'],
                    'cheque_submit_bank' => $chequeBank[$index]['cheque_bank'],
                    'amount' => $chequeAmounts[$index]['amount'],
                    'status' => 1,
                ]);

                $detailedHeadRecord = DetailedHead::where('ledgers_head_code', $chequeBank)->first();
                if ($detailedHeadRecord) {
                    $detailedHeadRecord->credit_amount += $chequeAmounts[$index]['amount'];
                    $detailedHeadRecord->save();
                }
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

        return redirect()->back()->with('success', 'Receipt Voucher created successfully.');
    }

    private function generateBillNo() {
        $currentYear = date('Y');
        $lastVoucher = ReceiptVoucher::whereYear('created_at', $currentYear)->orderBy('created_at', 'desc')->first();
        if ($lastVoucher) {
            $lastNumber = (int) substr($lastVoucher->bill_no, 5);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        $newBillNo = "RV-" . str_pad($newNumber, 5, '0', STR_PAD_LEFT);
        return $newBillNo;
    }

    public function list() {
        $receiptVoucher = ReceiptVoucher::orderBy('created_at', 'desc')->get();
        return view("layouts.pages.account.receipt_voucher.list", compact('receiptVoucher'));
    }

    public function show($voucher_id) {
        $receiptVoucher = ReceiptVoucher::where('voucher_id', $voucher_id)->firstOrFail();
        $ledgerItems = RvLedgerHead::where('voucher_id', $voucher_id)->get();

        $website = WebsiteSetting::first();

        $ledgerItemTotalAmount = 0;
        foreach ($ledgerItems as $ledgerItem) {
            $ledgerItemTotalAmount += $ledgerItem->cash_amount + $ledgerItem->online_amount + $ledgerItem->cheque_amount;
        }

        return view("layouts.pages.account.receipt_voucher.show", compact('website', 'receiptVoucher', 'ledgerItems', 'ledgerItemTotalAmount'));
    }

    public function generatePDF($voucher_id){
        $receiptVoucher = ReceiptVoucher::where('voucher_id', $voucher_id)->firstOrFail();
        $ledgerItem = RvLedgerHead::with('ledgerHead')->where('voucher_id', $voucher_id)->get();

        $website = WebsiteSetting::first();

        $totalAmount = $ledgerItem->sum(function ($item) {
            return ($item->cash_amount ?? 0) + ($item->cheque_amount ?? 0) + ($item->online_amount ?? 0);
        });

        $totalAmountInWords = 'Rupees ' . numberToWords($totalAmount) . ' Only';


        $pdf = PDF::loadView('layouts.pages.account.receipt_voucher.pdf', compact('website', 'receiptVoucher', 'ledgerItem', 'totalAmount', 'totalAmountInWords'))
        ->setOption('margin-top', 0)
        ->setOption('margin-left', 0)
        ->setOption('margin-right', 0)
        ->setOption('margin-bottom', 0);

        // Open the PDF in a new tab
        return $pdf->stream('receipt_voucher_' . $receiptVoucher->bill_no . '.pdf');
        // return view("layouts.pages.account.receipt_voucher.pdf", compact('website', 'receiptVoucher', 'ledgerItem', 'totalAmount', 'totalAmountInWords'));
    }


    public function editRequest($voucher_id) {
        $voucher = ReceiptVoucher::where('voucher_id', $voucher_id)->firstOrFail();

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
        $url = route('superadmin.account.receiptvoucher.update', $voucher_id);
        $receiptVoucher = ReceiptVoucher::where('voucher_id', $voucher_id)->firstOrFail();
        $rvLedgerHeads = RvLedgerHead::where('voucher_id', $voucher_id)->get();
        $rvChequeList = RvChequeList::where('voucher_id', $voucher_id)->get();

        $selectHead = DetailedHead::where(function($query) {
            $query->where('ledgers_head_code', 'not like', '450%');
        })->orderBy('ledgers_head_code', 'asc')->get();

        $ledger = DetailedHead::orderBy('created_at', 'desc')->get();
        $bank = DetailedHead::where('ledgers_head_code', 'like', '450%')->orderBy('created_at', 'desc')->get();

        return view("layouts.pages.account.receipt_voucher.edit", compact(
            'url', 'receiptVoucher', 'rvLedgerHeads', 'rvChequeList', 'selectHead', 'ledger', 'bank'
        ));
    }


    public function update(Request $request, $voucher_id) {
        $receiptVoucher = ReceiptVoucher::where('voucher_id', $voucher_id)->firstOrFail();
        $receiptVoucher->update([
            'date' => $request->date,
            'edit_status' => 0,
        ]);

        // Step 1: Reverse old balances
        $oldLedgerHeads = RvLedgerHead::where('voucher_id', $voucher_id)->get();
        foreach ($oldLedgerHeads as $ledger) {
            // Reverse cash
            if ($ledger->cash_amount > 0) {
                $head = DetailedHead::where('ledgers_head_code', 4501001)->first();
                if ($head) {
                    $head->debit_amount -= $ledger->cash_amount;
                    $head->save();
                }
            }

            // Reverse online
            if ($ledger->online_amount > 0) {
                $head = DetailedHead::where('ledgers_head_code', $ledger->online_head)->first();
                if ($head) {
                    $head->credit_amount -= $ledger->online_amount;
                    $head->save();
                }
            }

            // Reverse total from ledger head
            $total = $ledger->cash_amount + $ledger->cheque_amount + $ledger->online_amount;
            $head = DetailedHead::where('ledgers_head_code', $ledger->ledger_head)->first();
            if ($head) {
                $head->credit_amount -= $total;
                $head->save();
            }
        }

        RvLedgerHead::where('voucher_id', $voucher_id)->delete();

        // Step 2: Delete and reverse cheques
        $oldCheques = RvChequeList::where('voucher_id', $voucher_id)->get();
        foreach ($oldCheques as $cheque) {
            $head = DetailedHead::where('ledgers_head_code', $cheque->cheque_submit_bank)->first();
            if ($head) {
                $head->credit_amount -= $cheque->amount;
                $head->save();
            }
        }
        RvChequeList::where('voucher_id', $voucher_id)->delete();

        // Step 3: Insert new ledger heads
        foreach ($request->head as $index => $head) {
            $cash = $request->cash_amount[$index] ?? 0;
            $online = $request->online_amount[$index] ?? 0;
            $cheque = $request->cheque_total_amount[$index] ?? 0;
            $total = $cash + $online + $cheque;

            RvLedgerHead::create([
                'voucher_id' => $voucher_id,
                'ledger_head' => $head,
                'cash_head' => $cash > 0 ? 4501001 : null,
                'cash_amount' => $cash,
                'cheque_amount' => $cheque,
                'online_head' => $request->online_head[$index] ?? null,
                'online_amount' => $online,
                'remarks' => $request->remarks[$index] ?? null,
            ]);

            // Update balances
            if ($cash > 0) {
                $dh = DetailedHead::where('ledgers_head_code', 4501001)->first();
                if ($dh) {
                    $dh->debit_amount += $cash;
                    $dh->save();
                }
            }

            if ($online > 0) {
                $dh = DetailedHead::where('ledgers_head_code', $request->online_head[$index] ?? null)->first();
                if ($dh) {
                    $dh->credit_amount += $online;
                    $dh->save();
                }
            }

            $dh = DetailedHead::where('ledgers_head_code', $head)->first();
            if ($dh) {
                $dh->credit_amount += $total;
                $dh->save();
            }
        }

        // Step 4: Handle cheques if present
        if ($request->has('cheque_checkbox')) {
            foreach ($request->depositor_name as $index => $depositor) {
                RvChequeList::create([
                    'voucher_id' => $voucher_id,
                    'bill_no' => $receiptVoucher->bill_no,
                    'depositor_name' => $depositor['name'],
                    'bank_name' => $request->bank[$index]['head_code'],
                    'cheque_no' => $request->cheque_no[$index]['cheque_number'],
                    'cheque_submit_bank' => $request->cheque_bank[$index]['cheque_bank'],
                    'amount' => $request->cheque_amount[$index]['amount'],
                    'status' => 1,
                ]);

                $dh = DetailedHead::where('ledgers_head_code', $request->cheque_bank[$index]['cheque_bank'])->first();
                if ($dh) {
                    $dh->credit_amount += $request->cheque_amount[$index]['amount'];
                    $dh->save();
                }
            }
        }

        // Step 5: Recalculate closing balances
        foreach (DetailedHead::all() as $item) {
            $credit = $item->opening_credit + $item->credit_amount;
            $debit = $item->opening_debit + $item->debit_amount;

            if ($credit > $debit) {
                $item->update([
                    'closing_credit' => $credit - $debit,
                    'closing_debit' => 0.00,
                ]);
            } else {
                $item->update([
                    'closing_debit' => $debit - $credit,
                    'closing_credit' => 0.00,
                ]);
            }
        }

        return redirect()->route('superadmin.account.receiptvoucher.list')->with('success', 'Receipt Voucher updated successfully.');
    }

}
