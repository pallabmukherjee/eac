<?php

namespace App\Http\Controllers\Gratuity;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FinancialYear;

class FinancialYearController extends Controller {
    public function index() {
        $title = "Financial Year";
        $url = route('superadmin.gratuity.financial.store');
        $financialYears = FinancialYear::orderBy('created_at', 'desc')->get();
        $financialYear = null;
        return view("layouts.pages.gratuity.financial_year.add", compact("title", "url", "financialYears", "financialYear"));
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'year' => 'required',
        ]);

        FinancialYear::create([
            'year' => $validated['year'],
        ]);

        return redirect()->back()->with('success', 'Financial Year created successfully!');
    }

    public function edit($id) {
        $title = "Update Financial Year";
        $financialYear = FinancialYear::findOrFail($id);
        $url = route('superadmin.gratuity.financial.update', $financialYear->id);
        return view('layouts.pages.gratuity.financial_year.add', compact('url', 'financialYear', 'title'));
    }

    public function update(Request $request, $id) {
        $request->validate([
            'year' => 'required',
        ]);

        $financialYear = FinancialYear::findOrFail($id);
        $financialYear->update($request->only(['year']));

        return redirect()->route('superadmin.gratuity.financial.index')->with('success', 'Financial Year updated successfully.');
    }
}
