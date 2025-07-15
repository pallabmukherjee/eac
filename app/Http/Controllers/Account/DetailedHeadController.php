<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Account;
use App\Models\MajorHead;
use App\Models\MinorHead;
use App\Models\DetailedHead;

class DetailedHeadController extends Controller
{
    public function index(){
        $url = route('superadmin.account.detailedhead.store');
        $minorHeads = MinorHead::with('majorHead')->get();
        $detailedHeads = DetailedHead::with(['majorHead', 'minorHead'])->get();
        $detailedHead = null;
        return view('layouts.pages.account.detailed_heads.index', compact('url', 'detailedHeads', 'detailedHead', 'minorHeads'));
    }

    public function store(Request $request) {
        $request->validate([
            'major_head_code' => 'required|string|max:3|min:1',
            'minor_head_code' => 'required|string|max:3|min:1',
            'code' => 'required|string|max:2|min:1',
            'name' => 'required|string|max:255',
        ]);

        $majorHead = MajorHead::findOrFail($request->major_head_code);
        $minorHead = MinorHead::findOrFail($request->minor_head_code);

        $majorHeadCode = $majorHead->code;
        $minorHeadCode = $minorHead->code;


        $existingMinorHead = DetailedHead::where('major_head_code', $request->major_head_code)->where('minor_head_code', $request->minor_head_code)->where('code', $request->code)->first();

        if ($existingMinorHead) {
            return redirect()->back()->with('error', 'The combination of Minor Head Code and Code already exists.');
        }

        $ledgersHeadCode = $majorHeadCode . $minorHeadCode . $request->code;

        DetailedHead::create([
            'major_head_code' => $request->major_head_code,
            'minor_head_code' => $request->minor_head_code,
            'code' => $request->code,
            'name' => $request->name,
            'ledgers_head_code' => $ledgersHeadCode,
        ]);

        return redirect()->back()->with('success', 'Detailed Heads created successfully.');
    }

    public function edit($id) {
        $detailedHead = DetailedHead::findOrFail($id);
        $minorHeads = MinorHead::all();
        $url = route('superadmin.account.detailedhead.update', $detailedHead->id);

        return view('layouts.pages.account.detailed_heads.index', compact('url', 'detailedHead', 'minorHeads'));
    }

    public function update(Request $request, $id) {
        $request->validate([
            'major_head_code' => 'required|string|max:3|min:1',
            'minor_head_code' => 'required|string|max:3|min:1',
            'code' => 'required|string|max:255' . $id,
            'name' => 'required|string|max:255',
        ]);

        $majorHead = MajorHead::findOrFail($request->major_head_code);
        $minorHead = MinorHead::findOrFail($request->minor_head_code);

        $majorHeadCode = $majorHead->code;
        $minorHeadCode = $minorHead->code;

        $existingMinorHead = DetailedHead::where('major_head_code', $request->major_head_code)->where('minor_head_code', $request->minor_head_code)->where('code', $request->code)->where('id', '!=', $id)->first();

        if ($existingMinorHead) {
            return redirect()->back()->with('error', 'The combination of Minor Head Code and Code already exists.');
        }

        $detailedHead = DetailedHead::findOrFail($id);

        $ledgersHeadCode = $majorHeadCode . $minorHeadCode . $request->code;

        $detailedHead->update([
        'major_head_code' => $request->major_head_code,
        'minor_head_code' => $request->minor_head_code,
        'code' => $request->code,
        'name' => $request->name,
        'ledgers_head_code' => $ledgersHeadCode,
    ]);

        return redirect()->route("superadmin.account.detailedhead.index")->with('success', 'Detailed Heads updated successfully.');
    }

    public function destroy($id) {
        $detailedHead = DetailedHead::findOrFail($id);
        $detailedHead->delete();

        return redirect()->back()->with('success', 'Detailed Heads deleted successfully.');
    }
}
