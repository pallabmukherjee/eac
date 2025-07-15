<?php

namespace App\Http\Controllers\Gratuity;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Gratuity;
use App\Models\Loan;
use App\Models\GratuityRequest;
use App\Models\GratuityBill;
use App\Models\GratuityBillSummary;
use App\Models\GratuityLoanPay;
use App\Models\WebsiteSetting;
use Carbon\Carbon;
use PDF;

class GratuityBillController extends Controller {
    public function index() {
        $bill = GratuityBill::orderBy('created_at', 'desc')->get();
        return view("layouts.pages.gratuity.bill.index", compact('bill'));
    }

    public function create() {
        $url = route('superadmin.gratuity.bill.store');
        $gratuityRequest = GratuityRequest::whereIn('status', [2, 3])->orderBy('created_at', 'desc')->get();
        return view("layouts.pages.gratuity.bill.create", compact('url', 'gratuityRequest'));
    }

    public function store(Request $request) {
        $bill_no = $this->generateBillNo();
        $bill_id = 'G-' . uniqid(20);

        // Create GratuityBill record
        $paymentVoucher = GratuityBill::create([
            'bill_id' => $bill_id,
            'bill_no' => $bill_no,
        ]);

        foreach ($request->emp_id as $index => $emp_id) {
            $amount = $request->amount[$index];
            $voucher_number = $request->voucher_number[$index];
            $voucher_date = $request->voucher_date[$index];
            $id_no = $request->id_no[$index];
            $reference = $request->reference[$index];

            // Retrieve the ppo_amount from the Gratuity model
            $gratuity = \App\Models\Gratuity::find($emp_id);
            $ppo_amount = $gratuity->ppo_amount;
            $total_loan_amount = 0;

            // Check if there are loan payments to store
            if (isset($request->loan_bank[$emp_id])) {
                $loan_bank_ids = $request->loan_bank[$emp_id]; // This should be an array
                $loan_amounts = $request->loan_amount_to_pay[$emp_id]; // This should also be an array

                // Ensure both loan_bank_ids and loan_amounts are arrays
                if (is_array($loan_bank_ids) && is_array($loan_amounts)) {
                    foreach ($loan_bank_ids as $loan_index => $loan_bank_id) {
                        $loan_amount = $loan_amounts[$loan_index] ?? null; // Use null coalescing to avoid undefined index

                        if ($loan_amount) { // Only create if loan_amount is not null
                            GratuityLoanPay::create([
                                'bill_id' => $bill_id,
                                'bill_no' => $bill_no,
                                'emp_id' => $emp_id,
                                'bank' => $loan_bank_id, // Use the correct bank ID
                                'amount' => $loan_amount,
                            ]);

                            $loan = \App\Models\Loan::find($loan_bank_id);
                            $loan->loan_amount -= $loan_amount; // Decrease the loan amount
                            $loan->save(); // Save the changes

                            // Sum the loan amounts
                            $total_loan_amount += $loan_amount;
                        }
                    }
                }
            }

            // Calculate the total amount after gratuity and loan deductions
            $total_amount = $amount + $total_loan_amount; // Total amount to deduct from ppo_amount

            // Update the ppo_amount in the Gratuity model
            $gratuity->ppo_amount -= $total_amount; // Decrease ppo_amount
            $gratuity->save(); // Save the changes

            // Store GratuityBillSummary record
            GratuityBillSummary::create([
                'bill_id' => $bill_id,
                'bill_no' => $bill_no,
                'emp_id' => $emp_id,
                'voucher_number' => $voucher_number,
                'voucher_date' => $voucher_date,
                'id_number' => $id_no,
                'reference' => $reference,
                'gratuity_amount' => $amount,
                'loan_amount' => $total_loan_amount, // Store the total loan amount
                'total_amount' => $total_amount, // Store the calculated total amount
            ]);
        }

        GratuityRequest::where('status', 2)->delete();

        return redirect()->route('superadmin.gratuity.bill.index')->with('success', 'Gratuity Bill created successfully.');
    }


    private function generateBillNo() {
        $currentYear = date('Y');
        $previousYear = $currentYear - 1;
        $yearRange = "$previousYear-$currentYear";
        //$lastVoucher = GratuityBill::whereYear('created_at', $currentYear)->orderBy('created_at', 'desc')->first();
        $lastVoucher = GratuityBill::where('bill_no', 'like', "%/$yearRange")
        ->orderBy('created_at', 'desc')
        ->first();
        if ($lastVoucher) {
            // Extract number before the slash
            $lastNumber = (int) explode('/', $lastVoucher->bill_no)[0];
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        $newBillNo = str_pad($newNumber, 3, '0', STR_PAD_LEFT) . "/$yearRange";
        return $newBillNo;
    }

    public function show($bill_id) {
        $report = GratuityBill::where('bill_id', $bill_id )->first();
        $gratuityBills = GratuityBillSummary::with('empDetails')->where('bill_id', $bill_id )->orderBy('created_at', 'desc')->get();
        return view("layouts.pages.gratuity.bill.show", compact('report', 'gratuityBills'));
    }

    public function pdf($bill_id) {
        $report = GratuityBill::where('bill_id', $bill_id )->first();
        $gratuityBills = GratuityBillSummary::with('empDetails')->where('bill_id', $bill_id )->orderBy('created_at', 'desc')->get();
        $website = WebsiteSetting::first();
        $pdf = PDF::loadView('layouts.pages.gratuity.bill.pdf', compact('report', 'gratuityBills', 'website'))
            ->setOption('margin-top', 0)
            ->setOption('margin-left', 0)
            ->setOption('margin-right', 0)
            ->setOption('margin-bottom', 0);

        return $pdf->stream('Gratuity-Report.pdf');
    }
}
