<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Beneficiary;
use App\Models\PanCard;

class BeneficiaryController extends Controller
{
    public function index(){
        $url = route('superadmin.account.beneficiary.store');
        $beneficiarys = Beneficiary::with('pancard')->get();
        $beneficiary = null;
        return view('layouts.pages.account.beneficiary.index', compact('beneficiarys', 'url', 'beneficiary'));
    }

    public function store(Request $request) {
        $unic_party_code = 'B-' . uniqid(5);

        $request->validate([
            'institute_vendor_beneficiary' => 'required|string|max:255',
            'mobile' => 'nullable|string|max:10',
            'pan_card' => 'nullable|string|max:10',
            'gst' => 'nullable|string|max:15',
            'aadhaar_no' => 'nullable|string|max:12',
            'address' => 'nullable|string|max:255',
            'bank_name' => 'nullable|string|max:255',
            'ifsc_code' => 'nullable|string|max:11',
            'account_no' => 'nullable|string|max:20',
        ]);

        $panCard = $request->pan_card;
        $fourthCharacter = $panCard[3] ?? null;
        $panCardModel = $fourthCharacter ? PanCard::where('code', $fourthCharacter)->first() : null;
        $panCardId = $panCardModel ? $panCardModel->id : null;

        // Create a new record in the database
        Beneficiary::create([
            'name' => $request->institute_vendor_beneficiary,
            'party_code' => $unic_party_code,
            'mobile' => $request->mobile ? $request->mobile : 'NA',
            'pan_card' => $request->pan_card,
            'pan_category' => $panCardId,
            'gst' => $request->gst,
            'aadhaar_no' => $request->aadhaar_no,
            'address' => $request->address,
            'bank_name' => $request->bank_name,
            'ifsc_code' => $request->ifsc_code,
            'account_no' => $request->account_no,
        ]);

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Details saved successfully.');
    }


    public function edit($id) {
        $beneficiary = Beneficiary::findOrFail($id);
        $url = route('superadmin.account.beneficiary.update', $beneficiary->id);
        return view('layouts.pages.account.beneficiary.index', compact('url', 'beneficiary'));
    }

    public function update(Request $request, $id) {
        $request->validate([
            'institute_vendor_beneficiary' => 'required|string|max:255',
            'party_code' => 'nullable|string|max:255',
            'mobile' => 'nullable|string|max:10',
            'pan_card' => 'nullable|string|max:10',
            'gst' => 'nullable|string|max:15',
            'aadhaar_no' => 'nullable|string|max:12',
            'address' => 'nullable|string|max:255',
            'bank_name' => 'nullable|string|max:255',
            'ifsc_code' => 'nullable|string|max:11',
            'account_no' => 'nullable|string|max:20',
        ]);

        $panCard = $request->pan_card;
        $fourthCharacter = $panCard[3] ?? null;
        $panCardModel = $fourthCharacter ? PanCard::where('code', $fourthCharacter)->first() : null;
        $panCardId = $panCardModel ? $panCardModel->id : null;

        $data = [
            'name' => $request->institute_vendor_beneficiary,
            'party_code' => !empty($request->party_code) ? $request->party_code : 'NA',
            'mobile' => !empty($request->mobile) ? $request->mobile : 'NA',
            'pan_card' => !empty($request->pan_card) ? $request->pan_card : 'NA',
            'gst' => !empty($request->gst) ? $request->gst : 'NA',
            'aadhaar_no' => !empty($request->aadhaar_no) ? $request->aadhaar_no : 'NA',
            'address' => !empty($request->address) ? $request->address : 'NA',
            'bank_name' => !empty($request->bank_name) ? $request->bank_name : 'NA',
            'ifsc_code' => !empty($request->ifsc_code) ? $request->ifsc_code : 'NA',
            'account_no' => !empty($request->account_no) ? $request->account_no : 'NA',
            'pan_category' => $panCardId,
        ];

        $beneficiary = Beneficiary::findOrFail($id);
        $beneficiary->update($data);

        return redirect()->route('superadmin.account.beneficiary.index')->with('success', 'Beneficiary updated successfully.');
    }

    public function destroy($id) {
        $beneficiary = Beneficiary::findOrFail($id);
        $beneficiary->delete();
        return redirect()->back()->with('success', 'Beneficiary deleted successfully.');
    }
}
