<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DetailedHead;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Response;

class LedgerHeadController extends Controller {
    public function index(){
        $detailedHeads = DetailedHead::with(['majorHead', 'minorHead'])->orderBy('ledgers_head_code', 'asc')->get();
        return view('layouts.pages.account.ledger_head.index', compact('detailedHeads'));
    }

    public function exportPdf() {
        $detailedHeads = DetailedHead::with(['majorHead', 'minorHead'])->orderBy('ledgers_head_code', 'asc')->get();
        $pdf = Pdf::loadView('layouts.pages.account.ledger_head.pdf', compact('detailedHeads'))
            ->setPaper('a4', 'landscape');
        return $pdf->download('trial_balance.pdf');
    }

    public function exportExcel()
    {
        $detailedHeads = DetailedHead::with(['majorHead', 'minorHead'])->orderBy('ledgers_head_code', 'asc')->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="trial_balance.csv"',
        ];

        $callback = function() use ($detailedHeads) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Head of Account', 'LH Code', 'DH Code', 'Opening Debit', 'Opening Credit', 'Debit Amount', 'Credit Amount', 'Closing Debit', 'Closing Credit']);

            foreach ($detailedHeads as $item) {
                fputcsv($file, [
                    $item->name,
                    $item->ledgers_head_code ?? 'N/A',
                    $item->code,
                    $item->opening_debit,
                    $item->opening_credit,
                    $item->debit_amount,
                    $item->credit_amount,
                    $item->closing_debit,
                    $item->closing_credit
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
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
