<?php

namespace App\Http\Controllers\Pension;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SigningAuthorityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $signs = \App\Models\SigningAuthority::all();
        return view('layouts.pages.pension.signs.index', compact('signs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('layouts.pages.pension.signs.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'designation' => 'required|string|max:255',
        ]);

        \App\Models\SigningAuthority::create($request->all());

        return redirect()->route('superadmin.pension.signs.index')
                        ->with('success','Signing authority created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $sign = \App\Models\SigningAuthority::findOrFail($id);
        return view('layouts.pages.pension.signs.edit', compact('sign'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'designation' => 'required|string|max:255',
        ]);

        $sign = \App\Models\SigningAuthority::findOrFail($id);
        $sign->update($request->all());

        return redirect()->route('superadmin.pension.signs.index')
                        ->with('success','Signing authority updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $sign = \App\Models\SigningAuthority::findOrFail($id);
        $sign->delete();

        return redirect()->route('superadmin.pension.signs.index')
                        ->with('success','Signing authority deleted successfully.');
    }
}
