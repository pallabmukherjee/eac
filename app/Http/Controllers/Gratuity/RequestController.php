<?php

namespace App\Http\Controllers\Gratuity;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GratuityRequest;
use App\Models\Gratuity;

class RequestController extends Controller {
    public function index() {
        $url = route('superadmin.gratuity.request.store');
        $emp = Gratuity::orderBy('created_at', 'desc')->get();
        return view("layouts.pages.gratuity.request.index", compact('url', 'emp'));
    }


    public function store(Request $request) {
        $request->validate([
            'emp_code' => 'required',
            'emp_code.*' => 'required',

            'prayer_no' => 'nullable|array',
            'prayer_no.*' => 'nullable',
            'prayer_date' => 'nullable|array',
            'prayer_date.*' => 'nullable|date',

            'requested_amount' => 'required|array',
            'requested_amount.*' => 'required|numeric|min:1',
        ]);

        $rqID = 'REQ-' . uniqid();

        foreach ($request->emp_code as $key => $empCode) {
            GratuityRequest::create([
                'request_id' => $rqID,
                'emp_id' => $empCode,
                'prayer_no' => $request->prayer_no[$key],
                'prayer_date' => $request->prayer_date[$key],
                'amount' => $request->requested_amount[$key],
                'status' => 1,
            ]);
        }

        return redirect()->back()->with('success', 'Gratuity request submitted successfully.');
    }

    public function pending() {
        $title = "Pending Gratuity Request";
        $gratuityStatus = GratuityRequest::with('empName')->orderBy('created_at', 'desc')->get();
        return view("layouts.pages.gratuity.request.list", compact('gratuityStatus', 'title'));
    }

    public function updateStatus(Request $request, $id) {
        $validStatuses = ['1', '2', '3', '4'];

        $request->validate([
            'status' => 'required|in:' . implode(',', $validStatuses)
        ]);

        try {
            $gratuityRequest = GratuityRequest::findOrFail($id);
            $gratuityRequest->status = $request->status;
            $gratuityRequest->save();

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
