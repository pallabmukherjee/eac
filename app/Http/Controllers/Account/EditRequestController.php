<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\VoucharEditRequest;
use App\Models\PaymentVoucher;
use App\Models\ReceiptVoucher;
use App\Models\ContraVoucher;
use App\Models\JournalVoucher;

class EditRequestController extends Controller {

    public function index() {
        $voucahrRequest = VoucharEditRequest::with('user')->orderBy('created_at', 'desc')->get();
        return view("layouts.pages.account.edit_request.list", compact('voucahrRequest'));
    }

    public function updateStatus(Request $request) {
        $request->validate([
            'id' => 'required|integer|exists:vouchar_edit_requests,id',
            'status' => 'required|in:1,2', // 1 = approve, 2 = reject
        ]);

        $editRequest = VoucharEditRequest::findOrFail($request->id);
        $editRequest->edit_status = $request->status;
        $editRequest->save();

        $voucharId = $editRequest->vouchar_id;
        $prefix = strtoupper(substr($voucharId, 0, 2));

        if ($prefix === 'PV') {
            $voucher = PaymentVoucher::where('p_voucher_id', $voucharId)->firstOrFail();
        } elseif ($prefix === 'RV') {
            $voucher = ReceiptVoucher::where('voucher_id', $voucharId)->firstOrFail();
        } elseif ($prefix === 'CV') {
            $voucher = ContraVoucher::where('voucher_id', $voucharId)->firstOrFail();
        } elseif ($prefix === 'JV') {
            $voucher = JournalVoucher::where('voucher_id', $voucharId)->firstOrFail();
        } else {
            return response()->json(['success' => false, 'message' => 'Invalid voucher prefix.'], 400);
        }

        $voucher->edit_status = $request->status;
        $voucher->save();

        return response()->json(['success' => true, 'message' => 'Status updated successfully.']);
    }
}
