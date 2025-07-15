<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\MajorHead;
use Illuminate\Http\Request;

class MajorHeadController extends Controller {

    public function index() {
        $url = route('superadmin.account.majorhead.store');
        $majorHeads = MajorHead::orderBy('code', 'asc')->get();
        $majorHead = null;
        return view('layouts.pages.account.major_heads.index', compact('url', 'majorHeads', 'majorHead'));
    }

    // Store a newly created major head in storage
    public function store(Request $request) {
        $request->validate([
            'code' => 'required|string|unique:major_heads,code|max:3|min:1',
            'name' => 'required|string|max:255',
            'schedule_reference_no' => 'required|string|max:255',
        ]);

        MajorHead::create($request->all());

        return redirect()->back()->with('success', 'Major Head created successfully.');
    }

    // Show the form for editing the specified major head
    public function edit($id) {
        $majorHead = MajorHead::findOrFail($id);
        $url = route('superadmin.account.majorhead.update', $majorHead->id);
        return view('layouts.pages.account.major_heads.index', compact('url', 'majorHead'));
    }

    // Update the specified major head in storage
    public function update(Request $request, $id) {
        $request->validate([
            'code' => 'required|string|max:255|unique:major_heads,code,' . $id,
            'name' => 'nullable|string|max:255',
            'schedule_reference_no' => 'nullable|string|max:255',
        ]);

        $majorHead = MajorHead::findOrFail($id);
        $majorHead->update($request->only(['code', 'name', 'schedule_reference_no']));

        return redirect()->route('superadmin.account.majorhead.index')->with('success', 'Major Head updated successfully.');
    }

    // Remove the specified major head from storage
    public function destroy($id) {
        $majorHead = MajorHead::findOrFail($id);
        $majorHead->delete();
        return redirect()->back()->with('success', 'Major Head deleted successfully.');
    }
}
