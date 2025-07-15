<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MajorHead;
use App\Models\MinorHead;

class MinorHeadController extends Controller
{
    public function index() {
        $url = route('superadmin.account.minorhead.store');
        $minorHeads = MinorHead::with('majorHead')->get();
        $majorHeads = MajorHead::all();
        $minorHead = null;
        return view('layouts.pages.account.minor_heads.index', compact('url', 'minorHeads', 'minorHead', 'majorHeads'));
    }

    // Store a newly created major head in storage
    public function store(Request $request) {
        $request->validate([
            'major_head_code' => 'required|string|max:3|min:1',
            'code' => 'required|string|max:2|min:1',
            'name' => 'required|string|max:255',
        ]);

        $existingMinorHead = MinorHead::where('major_head_code', $request->major_head_code)->where('code', $request->code)->first();

        if ($existingMinorHead) {
            return redirect()->back()->with('error', 'The combination of Major Head Code and Code already exists.');
        }

        MinorHead::create($request->all());

        return redirect()->back()->with('success', 'Major Head created successfully.');
    }

    // Show the form for editing the specified major head
    public function edit($id) {
        $minorHead = MinorHead::findOrFail($id);
        $majorHeads = MajorHead::all();
        $url = route('superadmin.account.minorhead.update', $minorHead->id);
        return view('layouts.pages.account.minor_heads.index', compact('url', 'minorHead', 'majorHeads'));
    }

    // Update the specified major head in storage
    public function update(Request $request, $id) {
        $request->validate([
            'major_head_code' => 'required|string|max:3|min:1',
            'code' => 'required|string|max:255' . $id,
            'name' => 'nullable|string|max:255',
        ]);

        $existingMinorHead = MinorHead::where('major_head_code', $request->major_head_code)->where('code', $request->code)->where('id', '!=', $id)->first();

        if ($existingMinorHead) {
            return redirect()->back()->with('error', 'The combination of Major Head Code and Code already exists.');
        }

        $minorHead = MinorHead::findOrFail($id);
        $minorHead->update($request->only(['code', 'name', 'major_head_code']));

        return redirect()->route('superadmin.account.minorhead.index')->with('success', 'Major Head updated successfully.');
    }

    // Remove the specified major head from storage
    public function destroy($id) {
        $minorHead = MinorHead::findOrFail($id);
        $minorHead->delete();
        return redirect()->back()->with('success', 'Major Head deleted successfully.');
    }
}
