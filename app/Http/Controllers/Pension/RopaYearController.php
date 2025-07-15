<?php

namespace App\Http\Controllers\Pension;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RopaYear;

class RopaYearController extends Controller
{
    public function index() {
        $title = "Add ROPA Year";
        $url = route('superadmin.pension.ropa.store');
        $ropaYears = RopaYear::orderBy('created_at', 'desc')->get();
        $ropaYear = null;
        return view("layouts.pages.pension.ropa.add", compact('url', 'ropaYears', 'ropaYear', 'title'));
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'year' => 'required',
            'dr' => 'required|numeric',
        ]);

        RopaYear::create([
            'year' => $validated['year'],
            'dr' => $validated['dr'],
        ]);

        return redirect()->back()->with('success', 'Ropa Year created successfully!');
    }

    public function edit($id) {
        $title = "Update ROPA Year";
        $ropaYear = RopaYear::findOrFail($id);
        $url = route('superadmin.pension.ropa.update', $ropaYear->id);
        return view('layouts.pages.pension.ropa.add', compact('url', 'ropaYear', 'title'));
    }

    public function update(Request $request, $id) {
        $request->validate([
            'year' => 'required',
            'dr' => 'required|numeric',
        ]);

        $ropaYear = RopaYear::findOrFail($id);
        $ropaYear->update($request->only(['year', 'dr']));

        return redirect()->route('superadmin.pension.ropa.index')->with('success', 'Ropa Year updated successfully.');
    }
}
