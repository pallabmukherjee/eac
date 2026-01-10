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
    public function index(Request $request) {
        $status = $request->get('status') == 'approved' ? 2 : 1;
        $bill = GratuityBill::where('status', $status)->orderBy('created_at', 'desc')->get();
        return view("layouts.pages.gratuity.bill.index", compact('bill', 'status'));
    }

    public function create() {
        $url = route('superadmin.gratuity.bill.store');
        return view("layouts.pages.gratuity.bill.create", compact('url'));
    }

    public function store(Request $request) {
        $bill_no = $this->generateBillNo();
        $bill_id = 'G-' . uniqid(20);

        // Create GratuityBill record
        GratuityBill::create([
            'bill_id' => $bill_id,
            'bill_no' => $bill_no,
        ]);

        foreach ($request->emp_id as $index => $emp_id) {
            $amount = $request->amount[$index];
            $remarks = $request->remarks[$index];
            $prayer_no = $request->prayer_no[$index] ?? null;
            $prayer_date = $request->prayer_date[$index] ?? null;

            // Retrieve the ppo_amount from the Gratuity model
            $gratuity = \App\Models\Gratuity::find($emp_id);
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

                            // Sum the loan amounts
                            $total_loan_amount += $loan_amount;
                        }
                    }
                }
            }

            // Calculate the total amount after gratuity and loan deductions
            $total_amount = $amount + $total_loan_amount; // Total amount to deduct from ppo_amount

            // Store GratuityBillSummary record
            GratuityBillSummary::create([
                'bill_id' => $bill_id,
                'bill_no' => $bill_no,
                'emp_id' => $emp_id,
                'gratuity_amount' => $amount,
                'loan_amount' => $total_loan_amount, // Store the total loan amount
                'total_amount' => $total_amount, // Store the calculated total amount
                'remarks' => $remarks,
                'prayer_no' => $prayer_no,
                'prayer_date' => $prayer_date,
            ]);
        }

        return redirect()->route('superadmin.gratuity.bill.index')->with('success', 'Gratuity Application created successfully.');
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
        $gratuityBills = GratuityBillSummary::with(['empDetails.financialYear', 'empDetails.ropaYear'])->where('bill_id', $bill_id )->orderBy('created_at', 'desc')->get();
        return view("layouts.pages.gratuity.bill.show", compact('report', 'gratuityBills'));
    }

    public function edit($bill_id) {
        $report = GratuityBill::where('bill_id', $bill_id )->firstOrFail();
        $gratuityBills = GratuityBillSummary::with('empDetails')->where('bill_id', $bill_id )->get();
        $url = route('superadmin.gratuity.bill.update', $bill_id);
        
        return view("layouts.pages.gratuity.bill.edit", compact('report', 'gratuityBills', 'url'));
    }

    public function update(Request $request, $bill_id) {
        $bill = GratuityBill::where('bill_id', $bill_id)->firstOrFail();
        
        // We will simplify: Delete old summaries and loan payments and recreate them
        // But we need to handle ppo_amount and loan_amount reversal first ONLY IF it was already approved
        
        if ($bill->status == 2) {
            $oldSummaries = GratuityBillSummary::where('bill_id', $bill_id)->get();
            foreach($oldSummaries as $summary) {
                $gratuity = Gratuity::find($summary->emp_id);
                if($gratuity) {
                    // Revert ppo_amount: add back what was deducted
                    $gratuity->ppo_amount += $summary->total_amount;
                    $gratuity->save();
                }
            }
            
            $oldLoanPays = GratuityLoanPay::where('bill_id', $bill_id)->get();
            foreach($oldLoanPays as $loanPay) {
                $loan = Loan::find($loanPay->bank);
                if($loan) {
                    // Revert loan amount: add back what was paid
                    $loan->loan_amount += $loanPay->amount;
                    $loan->save();
                }
            }
        }
        
        // Delete old records
        GratuityBillSummary::where('bill_id', $bill_id)->delete();
        GratuityLoanPay::where('bill_id', $bill_id)->delete();
        
        // Re-run the storage logic (similar to store method)
        foreach ($request->emp_id as $index => $emp_id) {
            $amount = $request->amount[$index];
            $remarks = $request->remarks[$index];
            $prayer_no = $request->prayer_no[$index] ?? null;
            $prayer_date = $request->prayer_date[$index] ?? null;

            $gratuity = \App\Models\Gratuity::find($emp_id);
            $total_loan_amount = 0;

            if (isset($request->loan_bank[$emp_id])) {
                $loan_bank_ids = $request->loan_bank[$emp_id];
                $loan_amounts = $request->loan_amount_to_pay[$emp_id];

                if (is_array($loan_bank_ids) && is_array($loan_amounts)) {
                    foreach ($loan_bank_ids as $loan_index => $loan_bank_id) {
                        $loan_amount = $loan_amounts[$loan_index] ?? null;

                        if ($loan_amount) {
                            GratuityLoanPay::create([
                                'bill_id' => $bill->bill_id,
                                'bill_no' => $bill->bill_no,
                                'emp_id' => $emp_id,
                                'bank' => $loan_bank_id,
                                'amount' => $loan_amount,
                            ]);

                            // Only deduct if already approved
                            if ($bill->status == 2) {
                                $loan = \App\Models\Loan::find($loan_bank_id);
                                if ($loan) {
                                    $loan->loan_amount -= $loan_amount;
                                    $loan->save();
                                }
                            }

                            $total_loan_amount += $loan_amount;
                        }
                    }
                }
            }

            $total_amount = $amount + $total_loan_amount;
            
            // Only deduct if already approved
            if ($bill->status == 2 && $gratuity) {
                $gratuity->ppo_amount -= $total_amount;
                $gratuity->save();
            }

            GratuityBillSummary::create([
                'bill_id' => $bill->bill_id,
                'bill_no' => $bill->bill_no,
                'emp_id' => $emp_id,
                'gratuity_amount' => $amount,
                'loan_amount' => $total_loan_amount,
                'total_amount' => $total_amount,
                'remarks' => $remarks,
                'prayer_no' => $prayer_no,
                'prayer_date' => $prayer_date,
            ]);
        }

        return redirect()->route('superadmin.gratuity.bill.index')->with('success', 'Gratuity Bill updated successfully.');
    }

    public function pdf($bill_id) {
        $report = GratuityBill::where('bill_id', $bill_id )->first();
        $gratuityBills = GratuityBillSummary::with(['empDetails.financialYear', 'empDetails.ropaYear'])->where('bill_id', $bill_id )->orderBy('created_at', 'desc')->get();
        $website = WebsiteSetting::first();

        // Determine which view to load based on the report status
        $viewName = ($report->status == 2) ? 'layouts.pages.gratuity.bill.pdf' : 'layouts.pages.gratuity.bill.pending_pdf';

        $pdf = PDF::loadView($viewName, compact('report', 'gratuityBills', 'website'))
            ->setPaper('legal', 'landscape');

        return $pdf->stream('Gratuity-Report.pdf');
    }

    public function csv($bill_id) {
        $report = GratuityBill::where('bill_id', $bill_id)->first();
        $gratuityBills = GratuityBillSummary::with(['empDetails.financialYear', 'empDetails.ropaYear'])->where('bill_id', $bill_id)->orderBy('created_at', 'desc')->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="Gratuity-Report.csv"',
        ];

        $callback = function() use ($gratuityBills) {
            $file = fopen('php://output', 'w');

            // Add CSV headers
            fputcsv($file, [
                'Bill No.',
                'Relation Name (Spouse/Warrant)',
                'Name',
                'PPO No.',
                'Bank A/C No.',
                'IFSC',
                'Approved Amount',
                'Financial Year',
                'Ropa Year',
                'Remarks'
            ]);

            foreach ($gratuityBills as $item) {
                $relationName = ($item->empDetails->relation_died == 1) ? ($item->empDetails->warrant_name ?? 'NA') : ($item->empDetails->relation_name ?? 'NA');
                $financialYear = $item->empDetails->financialYear->year ?? '';
                $ropaYear = $item->empDetails->ropaYear->year ?? '';

                fputcsv($file, [
                    $item->bill_no,
                    $relationName,
                    $item->empDetails->name,
                    $item->empDetails->ppo_number ?? 'NA',
                    $item->empDetails->bank_ac_no ?? 'NA',
                    $item->empDetails->ifsc ?? 'NA',
                    $item->gratuity_amount,
                    $financialYear,
                    $ropaYear,
                    $item->remarks
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function destroy($bill_id) {
        $bill = GratuityBill::where('bill_id', $bill_id)->firstOrFail();
        
        // Cleanup related records
        GratuityBillSummary::where('bill_id', $bill_id)->delete();
        GratuityLoanPay::where('bill_id', $bill_id)->delete();
        
        $bill->delete();

        return redirect()->back()->with('success', 'Gratuity Bill deleted successfully.');
    }

    public function approve($bill_id) {
        $bill = GratuityBill::where('bill_id', $bill_id)->firstOrFail();
        $gratuityBills = GratuityBillSummary::with('empDetails')->where('bill_id', $bill_id)->get();
        return view("layouts.pages.gratuity.bill.approve", compact('bill', 'gratuityBills'));
    }

    public function confirmApprove(Request $request, $bill_id) {
        $bill = GratuityBill::where('bill_id', $bill_id)->firstOrFail();
        
        foreach($request->summary_id as $index => $summary_id) {
            $summary = GratuityBillSummary::findOrFail($summary_id);
            $summary->update([
                'voucher_no' => $request->voucher_no[$index],
                'voucher_date' => $request->voucher_date[$index],
                'id_no' => $request->id_no[$index],
                'reference_no' => $request->reference_no[$index],
                'reference_date' => $request->reference_date[$index],
            ]);

            // Deduct from Gratuity ppo_amount
            $gratuity = Gratuity::find($summary->emp_id);
            if ($gratuity) {
                $gratuity->ppo_amount -= $summary->total_amount;
                $gratuity->save();
            }

            // Deduct from Loans
            $loanPays = GratuityLoanPay::where('bill_id', $bill_id)->where('emp_id', $summary->emp_id)->get();
            foreach ($loanPays as $loanPay) {
                $loan = Loan::find($loanPay->bank);
                if ($loan) {
                    $loan->loan_amount -= $loanPay->amount;
                    $loan->save();
                }
            }
        }

        $bill->update(['status' => 2]);
        return redirect()->route('superadmin.gratuity.bill.index', ['status' => 'approved'])->with('success', 'Gratuity Application approved successfully.');
    }
}
