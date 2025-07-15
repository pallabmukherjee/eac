<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PanCard;

class PanCardController extends Controller
{

    public function index(){
        $url = route('superadmin.account.pan.store');
        $panCards = PanCard::all();
        $panCard = null;
        return view('layouts.pages.account.pan_card.index', compact('url', 'panCard', 'panCards'));
    }

    public function store(Request $request) {
        $request->validate([
            'code' => 'required|string|max:255',
            'label' => 'required|string|max:255',
            'tax' => 'required|numeric|min:0',
        ]);

        $panCard = new PanCard();
        $panCard->code = $request->input('code');
        $panCard->label = $request->input('label');
        $panCard->tax = $request->input('tax');

        $panCard->save();

        return redirect()->back()->with('success', 'PanCard created successfully!');
    }

    public function edit($id) {
        $panCard = PanCard::findOrFail($id);
        $url = route('superadmin.account.pan.update', $panCard->id);
        return view('layouts.pages.account.pan_card.index', compact('url', 'panCard'));
    }

    public function update(Request $request, $id) {
        $request->validate([
            'code' => 'required|string|max:255',
            'label' => 'required|string|max:255',
            'tax' => 'required|numeric|min:0',
        ]);

        $panCard = PanCard::findOrFail($id);
        $panCard->update($request->only(['code', 'label', 'tax']));

        return redirect()->route('superadmin.account.pan.index')->with('success', 'Pan Card updated successfully.');
    }

    public function destroy($id) {
        $panCard = PanCard::findOrFail($id);
        $panCard->delete();
        return redirect()->back()->with('success', 'Pan Card deleted successfully.');
    }
}
