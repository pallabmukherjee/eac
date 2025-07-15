<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DetailedHead;

class LedgerHeadController extends Controller {
    public function index(){
        $detailedHeads = DetailedHead::with(['majorHead', 'minorHead'])->orderBy('ledgers_head_code', 'asc')->get();
        return view('layouts.pages.account.ledger_head.index', compact('detailedHeads'));
    }

    public function updateDetailedHead(Request $request) {
        // Validate the incoming data
        $request->validate([
            'id' => 'required|exists:detailed_heads,id',  // Ensure the record exists
            'opening_debit' => 'nullable|numeric',
            'opening_credit' => 'nullable|numeric',
        ]);

        // Find the DetailedHead model by ID
        $detailedHead = DetailedHead::find($request->id);

        // Update the fields
        if ($detailedHead) {
            $detailedHead->opening_debit = $request->opening_debit ?? $detailedHead->opening_debit;
            $detailedHead->opening_credit = $request->opening_credit ?? $detailedHead->opening_credit;

            // Save the changes
            $detailedHead->save();

            return response()->json(['success' => true, 'message' => 'Updated successfully']);
        }

        return response()->json(['success' => false, 'message' => 'Record not found']);
    }
}
