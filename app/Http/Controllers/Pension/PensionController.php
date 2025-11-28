<?php

namespace App\Http\Controllers\Pension;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pensioner;
use App\Models\LifeCertificate;
use App\Models\RopaYear;
use App\Models\PensionerReport;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class PensionController extends Controller
{
    public function create() {
        $url = route('superadmin.pension.store');
        $ropaYears = RopaYear::orderBy('created_at', 'desc')->get();
        return view("layouts.pages.pension.add", compact('url', 'ropaYears'));
    }

    public function store(Request $request) {
        // Validate incoming request data
        $validated = $request->validate([
            'pensioner_name'       => 'required|string|max:255',
            'pension_type'         => 'required|in:1,2', // 1 for Self, 2 for Family Member
            'family_name'          => 'required|string|max:255',
            'dob'                  => 'required|date',
            'ppo_date'             => 'required|date',
            'retirement_date'      => 'required|date',
            'alive_status'         => 'required|in:1,2', // 1 for Alive, 2 for Dead
            'no_claimant'          => 'required|boolean',
            'death_date'           => 'nullable|date',
            'life_certificate'     => 'required',
            'employee_code'        => 'required|string|max:255',
            'ppo_number'           => 'required|string|max:255',
            'ropa_year'            => 'required',
            'aadhar_number'        => 'required|string|max:255',
            'savings_account_number'=> 'required|string|max:255',
            'ifsc_code'            => 'required|string|max:255',
            'phone_no'             => 'nullable|string|max:15',
            'alternative_phone_no' => 'nullable|string|max:15',
            'basic_pension'        => 'required|numeric',
            'dr_percentage'        => 'nullable|numeric',
            'medical_allowance'    => 'nullable|numeric',
            'other_allowance'      => 'nullable|numeric',
        ]);

        $five_year_date = Carbon::parse($validated['dob'])->addYears(65)->toDateString();

        if ($validated['alive_status'] == 1) {
            // If Alive, use the values as they are
            $pensioner_name = $validated['pensioner_name'];
            $family_name = $validated['family_name'];
        } else {
            // If Dead, swap values
            $pensioner_name = $validated['family_name'];
            $family_name = "Lt. " . $validated['pensioner_name']; // Prefix 'Lt.' to the pensioner name
        }

        $ropaYear = RopaYear::where('id', $validated['ropa_year'])->first();
        if ($ropaYear) {
            $drValue = $ropaYear->dr; // Get the 'dr' value from the matched RopaYear record
        }
        // Create a new Pensioner record and save it
        $pensioner = Pensioner::create([
            'pensioner_name'      => $pensioner_name,
            'pension_type'         => $validated['pension_type'],
            'family_name'          => $family_name,
            'dob'                  => $validated['dob'],
            'ppo_date'             => $validated['ppo_date'],
            'retirement_date'      => $validated['retirement_date'],
            'alive_status'         => $validated['alive_status'],
            'no_claimant'          => $validated['no_claimant'],
            'death_date'           => $validated['alive_status'] == 2 ? $validated['death_date'] : null,
            'life_certificate'     => $validated['life_certificate'],
            'five_year_date'       => $five_year_date,
            'five_year_completed'  => false, // Set default as false
            'employee_code'        => $validated['employee_code'],
            'ppo_number'           => $validated['ppo_number'],
            'ropa_year'            => $validated['ropa_year'],
            'aadhar_number'        => $validated['aadhar_number'],
            'savings_account_number'=> $validated['savings_account_number'],
            'ifsc_code'            => $validated['ifsc_code'],
            'phone_no'             => $validated['phone_no'],
            'alternative_phone_no' => $validated['alternative_phone_no'],
            'dr_percentage'        => $drValue,
            'basic_pension'        => $validated['basic_pension'] ?? 0.00,
            'medical_allowance'    => $validated['medical_allowance'] ?? 0.00,
            'other_allowance'      => $validated['other_allowance'] ?? 0.00,
        ]);

        // Redirect to a page with a success message (You can modify as per your needs)
        return redirect()->back()->with('success', 'Pensioner created successfully!');
    }

    public function index() {
        $pensioners = Pensioner::orderBy('id', 'asc')->get();
        $totalPensioners = Pensioner::where('no_claimant', 0)->count();
        $totalDead = Pensioner::where('alive_status', 2)->count();
        $total5YearsCompleted = Pensioner::where('five_year_date', '<', now())->count();
        $total80YearsCompleted = Pensioner::where('dob', '<', now()->subYears(80))->count();
        $totalReportsGenerated = PensionerReport::count();
        $totalLifeCertificateYes = Pensioner::where('life_certificate', 1)->count();

        return view("layouts.pages.pension.list", compact(
            'pensioners',
            'totalPensioners',
            'totalDead',
            'total5YearsCompleted',
            'total80YearsCompleted',
            'totalReportsGenerated',
            'totalLifeCertificateYes'
        ));
    }

    public function edit($id) {
        $url = route('superadmin.pension.update', $id);
        $pensioner = Pensioner::where('id', $id)->first();
        $ropaYears = RopaYear::orderBy('created_at', 'desc')->get();
        return view("layouts.pages.pension.edit", compact('pensioner', 'ropaYears', 'url'));
    }

    public function update(Request $request, $id) {
        // Find the existing pensioner by ID
        $pensioner = Pensioner::findOrFail($id);

        // Validate incoming request data
        $validated = $request->validate([
            'pensioner_name'       => 'required|string|max:255',
            'pension_type'         => 'required|in:1,2', // 1 for Self, 2 for Family Member
            'family_name'          => 'required|string|max:255',
            'dob'                  => 'required|date',
            'retirement_date'      => 'required|date',
            'alive_status'         => 'required|in:1,2', // 1 for Alive, 2 for Dead
            'no_claimant'          => 'required|boolean',
            'death_date'           => 'nullable|date',
            'employee_code'        => 'required|string|max:255',
            'ppo_number'           => 'required|string|max:255',
            'ropa_year'            => 'required',
            'aadhar_number'        => 'required|string|max:255',
            'savings_account_number'=> 'required|string|max:255',
            'ifsc_code'            => 'required|string|max:255',
            'phone_no'             => 'nullable|string|max:15',
            'alternative_phone_no' => 'nullable|string|max:15',
            'basic_pension'        => 'required|numeric',
            'dr_percentage'        => 'nullable|numeric',
            'medical_allowance'    => 'nullable|numeric',
            'other_allowance'      => 'nullable|numeric',
        ]);

        $five_year_date = Carbon::parse($validated['retirement_date'])->addYears(5)->toDateString();

        if ($validated['alive_status'] == 2 && $pensioner->alive_status == 1) {
            // If Alive to Dead, swap the names and add 'Lt.'
            $pensioner_name = $validated['family_name'];
            $family_name = "Lt. " . $validated['pensioner_name'];
        } else {
            // If Alive or if no change in status, use the values as they are
            $pensioner_name = $validated['pensioner_name'];
            $family_name = $validated['family_name'];
        }

        $ropaYear = RopaYear::where('id', $validated['ropa_year'])->first();
        if ($ropaYear) {
            $drValue = $ropaYear->dr;
        }

        // Update the existing Pensioner record with the new values
        $pensioner->update([
            'pensioner_name'      => $pensioner_name,
            'pension_type'         => $validated['pension_type'],
            'family_name'          => $family_name,
            'dob'                  => $validated['dob'],
            'retirement_date'      => $validated['retirement_date'],
            'alive_status'         => $validated['alive_status'],
            'no_claimant'          => $validated['no_claimant'],
            'death_date'           => $validated['alive_status'] == 2 ? $validated['death_date'] : null,
            'five_year_date'       => $five_year_date,
            'five_year_completed'  => false, // Set default as false
            'employee_code'        => $validated['employee_code'],
            'ppo_number'           => $validated['ppo_number'],
            'ropa_year'            => $validated['ropa_year'],
            'aadhar_number'        => $validated['aadhar_number'],
            'savings_account_number'=> $validated['savings_account_number'],
            'ifsc_code'            => $validated['ifsc_code'],
            'phone_no'             => $validated['phone_no'],
            'alternative_phone_no' => $validated['alternative_phone_no'],
            'dr_percentage'        => $drValue,
            'basic_pension'        => $validated['basic_pension'] ?? 0.00,
            'medical_allowance'    => $validated['medical_allowance'] ?? 0.00,
            'other_allowance'      => $validated['other_allowance'] ?? 0.00,
        ]);

        // Redirect to a page with a success message
        return redirect()->route('superadmin.pension.index')->with('success', 'Pensioner updated successfully!');
    }

    public function updateLifeCertificate(Request $request) {
        $request->validate([
            'id' => 'required|exists:pensioners,id',
            'life_certificate' => 'required|in:1,2',
        ]);

        $pensioner = Pensioner::find($request->id);
        $pensioner->life_certificate = $request->life_certificate;
        $pensioner->save();

        $lifeCertificate = LifeCertificate::where('user_id', $request->id)->where('status', 1)->first();
        if ($lifeCertificate) {
            $lifeCertificate->status = 2;
            $lifeCertificate->save();
        }


        return response()->json(['success' => true]);
    }

    public function export(Request $request)
    {
        $type = $request->query('type');
        $query = Pensioner::orderBy('id', 'asc');
        $fileName = "pensioners.csv";

        if ($type === 'dead') {
            $query->where('alive_status', 2);
            $fileName = "Total Dead.csv";
            $cardTitle = "Total Dead";
        } elseif ($type === '5years') {
            $query->where('five_year_date', '<', now());
            $fileName = "Total 5 Years Completed.csv";
            $cardTitle = "Total 5 Years Completed";
        } elseif ($type === '80years') {
            $query->where('dob', '<', now()->subYears(80));
            $fileName = "Total 80 Years Completed.csv";
            $cardTitle = "Total 80 Years Completed";
        } elseif ($type === 'life_cert_yes') {
            $query->where('life_certificate', 1);
            $fileName = "Total Life Certificate Yes.csv";
            $cardTitle = "Total Life Certificate Yes";
        } else {
            $query->where('no_claimant', 0); // Exclude pensioners with no_claimant = 1
            $fileName = "Total Active Pensioners.csv";
            $cardTitle = "Total Active Pensioners";
        }

        $pensioners = $query->get();

        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $columns = array('Sl. No.', 'PPO Code', 'Pensioner Name', 'Family Pensioner Name', 'Aadhar No', 'Phone No', 'Type Of Pension', 'Life Certificate', 'Date of Retirement', 'Alive Status', '5 Years Completed', '5 Years Completed Status', '80 Years Completed', '80 Years Completed Status');

        $callback = function() use($pensioners, $columns, $cardTitle) {
            $file = fopen('php://output', 'w');
            fputcsv($file, [$cardTitle]); // Write title first
            fputcsv($file, $columns);

            foreach ($pensioners as $pensioner) {
                $row['PPO Code']  = $pensioner->ppo_number;
                $row['Pensioner Name']    = $pensioner->pensioner_name;
                $row['Family Pensioner Name'] = $pensioner->family_name;
                $row['Aadhar No'] = $pensioner->aadhar_number;
                $row['Phone No'] = $pensioner->phone_no;
                $row['Type Of Pension']  = $pensioner->pension_type == 1 ? 'Self' : 'Family member';
                $row['Life Certificate']  = $pensioner->life_certificate == 1 ? 'Yes' : 'No';
                $row['Date of Retirement']  = \Carbon\Carbon::parse($pensioner->retirement_date)->format('d/m/Y');
                $row['Alive Status']  = $pensioner->alive_status == 1 ? 'Alive' : 'Dead';

                $fiveYearDate = \Carbon\Carbon::parse($pensioner->five_year_date);
                $row['5 Years Completed'] = $fiveYearDate->format('d/m/Y');
                $row['5 Years Completed Status'] = $fiveYearDate->isPast() ? 'Yes' : 'No';

                if ($pensioner->dob) {
                    $eightyYearDate = \Carbon\Carbon::parse($pensioner->dob)->addYears(80);
                    $row['80 Years Completed'] = $eightyYearDate->format('d/m/Y');
                    $row['80 Years Completed Status'] = $eightyYearDate->isPast() ? 'Yes' : 'No';
                } else {
                    $row['80 Years Completed'] = '';
                    $row['80 Years Completed Status'] = '';
                }


                fputcsv($file, array($pensioner->id, $row['PPO Code'], $row['Pensioner Name'], $row['Family Pensioner Name'], $row['Aadhar No'], $row['Phone No'], $row['Type Of Pension'], $row['Life Certificate'], $row['Date of Retirement'], $row['Alive Status'], $row['5 Years Completed'], $row['5 Years Completed Status'], $row['80 Years Completed'], $row['80 Years Completed Status']));
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportPdf()
    {
        $pensioners = Pensioner::orderBy('id', 'asc')->get();
        $pdf = Pdf::loadView('layouts.pages.pension.pdf', compact('pensioners'))
                  ->setPaper('legal', 'landscape');
        return $pdf->stream('pensioners.pdf');
    }

    public function downloadLifeCertificate($id)
    {
        $pensioner = Pensioner::findOrFail($id);
        $pdf = Pdf::loadView('layouts.pages.pension.life_certificate', compact('pensioner'))
                  ->setPaper('legal', 'portrait');
        return $pdf->stream('life_certificate.pdf');
    }

}
