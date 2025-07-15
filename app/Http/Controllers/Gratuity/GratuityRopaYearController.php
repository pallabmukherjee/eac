<?php

namespace App\Http\Controllers\Gratuity;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GratuityRopaYear;

class GratuityRopaYearController extends Controller {
    public function index() {
        $title = "Ropa Year";
        $url = route('superadmin.gratuity.ropa.store');
        $ropaYears = GratuityRopaYear::orderBy('created_at', 'desc')->get();
        $ropaYear = null;
        return view("layouts.pages.gratuity.ropa.add", compact("title", "url", "ropaYears", "ropaYear"));
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'year' => 'required',
        ]);

        GratuityRopaYear::create([
            'year' => $validated['year'],
        ]);

        return redirect()->back()->with('success', 'Ropa Year created successfully!');
    }

    public function edit($id) {
        $title = "Update Ropa Year";
        $ropaYear = GratuityRopaYear::findOrFail($id);
        $url = route('superadmin.gratuity.ropa.update', $ropaYear->id);
        return view('layouts.pages.gratuity.ropa.add', compact('url', 'ropaYear', 'title'));
    }

    public function update(Request $request, $id) {
        $request->validate([
            'year' => 'required',
        ]);

        $ropaYear = GratuityRopaYear::findOrFail($id);
        $ropaYear->update($request->only(['year']));

        return redirect()->route('superadmin.gratuity.ropa.index')->with('success', 'Ropa Year updated successfully.');
    }
}
