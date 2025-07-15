<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Account\PaymentVoucherRequest;
use App\Models\PaymentVoucher;
use App\Models\VoucharEditRequest;
use App\Models\PVoucharBank;
use App\Models\LedgerReport;
use App\Models\Beneficiary;
use App\Models\DetailedHead;
use App\Models\MajorHead;
use App\Models\PVoucharDeduct;
use App\Models\WebsiteSetting;
use Illuminate\Support\Facades\Auth;
use PDF;

class PaymentVoucherController extends Controller
{
    public function index() {
        $url = route('superadmin.account.paymentvoucher.store');
        $majorHeadIds = MajorHead::where(function($query) {
            $query->where('code', 'like', '310')
                  ->orWhere('code', 'like', '320')
                  ->orWhere('code', 'like', '341');
        })->pluck('id');
        $beneficiarys = Beneficiary::orderBy('created_at', 'desc')->get();
        $schemefund = DetailedHead::whereIn('major_head_code', $majorHeadIds)->orderBy('created_at', 'desc')->get();
        $ledger = DetailedHead::orderBy('created_at', 'desc')->get();
        $bank = DetailedHead::where('ledgers_head_code', 'like', '450%')->orderBy('created_at', 'desc')->get();
        return view("layouts.pages.account.payment_voucher.index", compact('url', 'beneficiarys', 'schemefund', 'ledger', 'bank'));
    }

    public function store(PaymentVoucherRequest  $request) {
        $billNo = $this->generateBillNo();
        $unicID = 'PV-' . uniqid(20);

        $paymentVoucher = PaymentVoucher::create([
            'p_voucher_id' => $unicID,
            'bill_no' => $billNo,
            'date' => $request->date,
            'payee' => $request->payee,
            'scheme_fund' => $request->scheme_fund,
            'payment_mode' => $request->payment_mode,
            'reference_number' => $request->reference_number,
            'reference_date' => $request->reference_date,
            'narration' => $request->narration,
            'bank' => $request->payment_mode == 1 ? '4501001' : $request->bank,
        ]);

        if ($request->payment_mode == 2) {
            PVoucharBank::create([
                'voucher_id' => $unicID,
                'cheque_no' => $request->cheque_no,
                'date' => $request->cheque_date,
                'bank' => $request->bank,
            ]);
        }

        foreach ($request->ledger as $index => $ledger) {
            LedgerReport::create([
                'voucher_id' => $unicID,
                'ledger' => $ledger,
                'amount' => $request->amount[$index],
                'pay_deduct' => $request->pay_deduct[$index],
            ]);

            if ($request->pay_deduct[$index] == 2) {
                PVoucharDeduct::create([
                    'voucher_id' => $unicID,
                    'ledger_head' => $ledger,
                    'bill_no' => $billNo,
                    'amount' => $request->amount[$index],
                ]);
            }


            $schemeFund = DetailedHead::where('ledgers_head_code', $request->scheme_fund)->first();
            if ($schemeFund) {
                $payschemeFund = 0;
                $deductschemeFund = 0;
                if ($request->pay_deduct[$index] == 1) {
                    $payschemeFund += $request->amount[$index];
                } elseif ($request->pay_deduct[$index] == 2) {
                    $deductschemeFund += $request->amount[$index];
                }
                $totalschemeFund = $payschemeFund - $deductschemeFund;
                $newSchemeDebitAmount = $schemeFund->debit_amount + $totalschemeFund;
                $schemeFund->update([
                    'debit_amount' => $newSchemeDebitAmount,
                ]);
            }


            $bankFund = DetailedHead::where('ledgers_head_code', $request->payment_mode == 1 ? '4501001' : $request->bank)->first();
            if ($bankFund) {
                $paybankFund = 0;
                $deductbankFund = 0;
                if ($request->pay_deduct[$index] == 1) {
                    $paybankFund += $request->amount[$index];
                } elseif ($request->pay_deduct[$index] == 2) {
                    $deductbankFund += $request->amount[$index];
                }
                $totalbankFund = $paybankFund - $deductbankFund;
                $newBankDebitAmount = $bankFund->credit_amount + $totalbankFund;

                $bankFund->update([
                    'credit_amount' => $newBankDebitAmount,
                ]);
            }


            $detailedHeads = DetailedHead::where('ledgers_head_code', $ledger)->get();
            //$ledgerFirstDigit = substr($ledger, 0, 1);
            foreach ($detailedHeads as $detailedHead) {
                $newCreditAmount = $detailedHead->credit_amount;
                $newDebitAmount = $detailedHead->debit_amount;

                if ($request->pay_deduct[$index] == 1) {
                    $newDebitAmount = $detailedHead->debit_amount + $request->amount[$index];
                } elseif ($request->pay_deduct[$index] == 2) {
                    $newCreditAmount = $detailedHead->credit_amount + $request->amount[$index];
                }

                // if (in_array($ledgerFirstDigit, ['1', '3'])) {
                //     if ($request->pay_deduct[$index] == 1) {
                //         $newDebitAmount = $detailedHead->debit_amount - $request->amount[$index];
                //     } elseif ($request->pay_deduct[$index] == 2) {
                //         $newCreditAmount = $detailedHead->credit_amount + $request->amount[$index];
                //     }
                // } elseif (in_array($ledgerFirstDigit, ['2', '4'])) {
                //     if ($request->pay_deduct[$index] == 1) {
                //         $newDebitAmount = $detailedHead->debit_amount + $request->amount[$index];
                //     } elseif ($request->pay_deduct[$index] == 2) {
                //         $newCreditAmount = $detailedHead->credit_amount - $request->amount[$index];
                //     }
                // }
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



        return redirect()->back()->with('success', 'Payment Voucher created successfully.');
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

    public function list() {
        $paymentVouchers = PaymentVoucher::with(['beneficiary', 'schemeFund', 'voucherBank'])->orderBy('created_at', 'desc')->get();
        return view("layouts.pages.account.payment_voucher.list", compact('paymentVouchers'));
    }

    public function show($p_voucher_id) {
        $paymentVoucher = PaymentVoucher::where('p_voucher_id', $p_voucher_id)->with(['beneficiary', 'schemeFund'])->firstOrFail();
        $ledgerItem = LedgerReport::with('ledgername')->where('voucher_id', $p_voucher_id)->get();

        $website = WebsiteSetting::first();

        $pay = LedgerReport::where('voucher_id', $p_voucher_id)->where('pay_deduct', 1)->sum('amount');
        $deduct = LedgerReport::where('voucher_id', $p_voucher_id)->where('pay_deduct', 2)->sum('amount');

        $totalAmount = $pay - $deduct;
        $totalAmountInWords = 'Rupees ' . numberToWords($totalAmount) . ' Only';

        return view("layouts.pages.account.payment_voucher.show", compact('website', 'paymentVoucher', 'ledgerItem','pay', 'deduct', 'totalAmount', 'totalAmountInWords'));
    }

    public function generatePDF($p_voucher_id){
        $paymentVoucher = PaymentVoucher::where('p_voucher_id', $p_voucher_id)->with('beneficiary')->firstOrFail();
        $ledgerItem = LedgerReport::with('ledgername')->where('voucher_id', $p_voucher_id)->get();

        $website = WebsiteSetting::first();

        $pay = LedgerReport::where('voucher_id', $p_voucher_id)->where('pay_deduct', 1)->sum('amount');
        $deduct = LedgerReport::where('voucher_id', $p_voucher_id)->where('pay_deduct', 2)->sum('amount');
        $totalAmount = $pay - $deduct;
        $totalAmountInWords = 'Rupees ' . numberToWords($totalAmount) . ' Only';


        $pdf = PDF::loadView('layouts.pages.account.payment_voucher.pdf', compact('website', 'paymentVoucher', 'ledgerItem', 'pay', 'deduct', 'totalAmount', 'totalAmountInWords'))
        ->setOption('margin-top', 0)
        ->setOption('margin-left', 0)
        ->setOption('margin-right', 0)
        ->setOption('margin-bottom', 0);

        // Open the PDF in a new tab
        return $pdf->stream('payment_voucher_' . $paymentVoucher->bill_no . '.pdf');
    }

    public function editRequest($p_voucher_id) {
        $voucher = PaymentVoucher::where('p_voucher_id', $p_voucher_id)->firstOrFail();

        // Update edit_status to 1
        $voucher->edit_status = 3;
        $voucher->save();

        VoucharEditRequest::create([
            'vouchar_id' => $p_voucher_id,
            'bill_no'    => $voucher->bill_no,
            'user_id'    => Auth::id(),
            'edit_status' => 0,
        ]);

        // Redirect back or to another page with success message
        return redirect()->back()->with('success', 'Edit request submitted successfully.');
    }

    public function edit($p_voucher_id) {
        $paymentVoucher = PaymentVoucher::where('p_voucher_id', $p_voucher_id)->with(['beneficiary', 'schemeFund'])->firstOrFail();
        $bankcheque = PVoucharBank::where('voucher_id', $p_voucher_id)->first();
        $ledgerItem = LedgerReport::with('ledgername')->where('voucher_id', $p_voucher_id)->get();

        $majorHeadIds = MajorHead::where(function($query) {
            $query->where('code', 'like', '310')
                  ->orWhere('code', 'like', '320')
                  ->orWhere('code', 'like', '341');
        })->pluck('id');

        $url = route('superadmin.account.paymentvoucher.update', ['p_voucher_id' => $paymentVoucher->p_voucher_id]);
        $beneficiarys = Beneficiary::orderBy('created_at', 'desc')->get();
        $schemefund = DetailedHead::whereIn('major_head_code', $majorHeadIds)->orderBy('created_at', 'desc')->get();
        $bank = DetailedHead::where('ledgers_head_code', 'like', '450%')->orderBy('created_at', 'desc')->get();
        $ledger = DetailedHead::orderBy('ledgers_head_code', 'asc')->get();
        $website = WebsiteSetting::first();

        $pay = LedgerReport::where('voucher_id', $p_voucher_id)->where('pay_deduct', 1)->sum('amount');
        $deduct = LedgerReport::where('voucher_id', $p_voucher_id)->where('pay_deduct', 2)->sum('amount');

        $totalAmount = $pay - $deduct;
        $totalAmountInWords = 'Rupees ' . numberToWords($totalAmount) . ' Only';

        return view("layouts.pages.account.payment_voucher.edit", compact('url', 'bankcheque', 'beneficiarys', 'ledger', 'majorHeadIds', 'bank', 'schemefund', 'website', 'paymentVoucher', 'ledgerItem', 'pay', 'deduct', 'totalAmount', 'totalAmountInWords'));
    }


    public function update(PaymentVoucherRequest $request, $p_voucher_id) {
        $paymentVoucher = PaymentVoucher::where('p_voucher_id', $p_voucher_id)->firstOrFail();

        // Fetch old ledger entries BEFORE deleting
        $oldLedgers = LedgerReport::where('voucher_id', $p_voucher_id)->get();

        // Revert all previous amounts from DetailedHead
        foreach ($oldLedgers as $old) {
            // Revert scheme fund
            $schemeFund = DetailedHead::where('ledgers_head_code', $paymentVoucher->scheme_fund)->first();
            if ($schemeFund) {
                $adjustAmount = $old->pay_deduct == 1 ? -$old->amount : $old->amount;
                $schemeFund->update([
                    'debit_amount' => $schemeFund->debit_amount + $adjustAmount
                ]);
            }

            // Revert bank fund
            $bankCode = $paymentVoucher->payment_mode == 1 ? '4501001' : $paymentVoucher->bank;
            $bankFund = DetailedHead::where('ledgers_head_code', $bankCode)->first();
            if ($bankFund) {
                $adjustAmount = $old->pay_deduct == 1 ? -$old->amount : $old->amount;
                $bankFund->update([
                    'credit_amount' => $bankFund->credit_amount + $adjustAmount
                ]);
            }

            // Revert main ledger
            $detailedHeads = DetailedHead::where('ledgers_head_code', $old->ledger)->get();
            foreach ($detailedHeads as $detailedHead) {
                $detailedHead->update([
                    'debit_amount' => $detailedHead->debit_amount - ($old->pay_deduct == 1 ? $old->amount : 0),
                    'credit_amount' => $detailedHead->credit_amount - ($old->pay_deduct == 2 ? $old->amount : 0),
                ]);
            }
        }

        // Delete old entries AFTER reversal
        LedgerReport::where('voucher_id', $p_voucher_id)->delete();
        PVoucharBank::where('voucher_id', $p_voucher_id)->delete();
        PVoucharDeduct::where('voucher_id', $p_voucher_id)->delete();

        // Update main voucher
        $paymentVoucher->update([
            'date' => $request->date,
            'payee' => $request->payee,
            'scheme_fund' => $request->scheme_fund,
            'payment_mode' => $request->payment_mode,
            'reference_number' => $request->reference_number,
            'reference_date' => $request->reference_date,
            'narration' => $request->narration,
            'bank' => $request->payment_mode == 1 ? '4501001' : $request->bank,
            'edit_status' => 0,
        ]);

        // Insert new bank cheque info
        if ($request->payment_mode == 2) {
            PVoucharBank::create([
                'voucher_id' => $p_voucher_id,
                'cheque_no' => $request->cheque_no,
                'date' => $request->cheque_date,
                'bank' => $request->bank,
            ]);
        }

        // Loop through new entries and update
        foreach ($request->ledger as $index => $ledger) {
            $amount = $request->amount[$index];
            $pay_deduct = $request->pay_deduct[$index];

            LedgerReport::create([
                'voucher_id' => $p_voucher_id,
                'ledger' => $ledger,
                'amount' => $amount,
                'pay_deduct' => $pay_deduct,
            ]);

            if ($pay_deduct == 2) {
                PVoucharDeduct::create([
                    'voucher_id' => $p_voucher_id,
                    'ledger_head' => $ledger,
                    'amount' => $amount,
                ]);
            }

            // Update scheme fund
            $schemeFund = DetailedHead::where('ledgers_head_code', $request->scheme_fund)->first();
            if ($schemeFund) {
                $adjustAmount = $pay_deduct == 1 ? $amount : -$amount;
                $schemeFund->update([
                    'debit_amount' => $schemeFund->debit_amount + $adjustAmount
                ]);
            }

            // Update bank
            $bankLedgerCode = $request->payment_mode == 1 ? '4501001' : $request->bank;
            $bankFund = DetailedHead::where('ledgers_head_code', $bankLedgerCode)->first();
            if ($bankFund) {
                $adjustAmount = $pay_deduct == 1 ? $amount : -$amount;
                $bankFund->update([
                    'credit_amount' => $bankFund->credit_amount + $adjustAmount
                ]);
            }

            // Update main ledger
            $detailedHeads = DetailedHead::where('ledgers_head_code', $ledger)->get();
            foreach ($detailedHeads as $detailedHead) {
                $detailedHead->update([
                    'debit_amount' => $detailedHead->debit_amount + ($pay_deduct == 1 ? $amount : 0),
                    'credit_amount' => $detailedHead->credit_amount + ($pay_deduct == 2 ? $amount : 0),
                ]);
            }
        }

        // Recalculate closing balances
        foreach (DetailedHead::all() as $item) {
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

        return redirect()->route('superadmin.account.paymentvoucher.list')->with('success', 'Payment Voucher updated successfully.');
    }


}
