<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WebsiteSetting;
use Illuminate\Support\Facades\Storage;

class WebsiteSettingController extends Controller {

    public function setting(){
        $title = "Settings";
        $url = route('superadmin.update.website.settings');
        $websiteSetting = WebsiteSetting::first();
        return view('layouts.super-admin.setting', compact('title', 'url', 'websiteSetting'));
    }

    public function store(Request $request) {
        $request->validate([
            'name' => 'nullable|string|max:255',
            'organization' => 'nullable|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if (!$request->has('name')) {
            return redirect()->back()->withErrors(['name' => 'The name field is required.']);
        }

        $websiteSetting = WebsiteSetting::first();

        if ($request->hasFile('logo')) {
            if ($websiteSetting && $websiteSetting->logo) {
                Storage::disk('public')->delete($websiteSetting->logo);
            }

            $path = $request->file('logo')->store('logos', 'public');

            if ($websiteSetting) {
                $websiteSetting->update([
                    'name' => $request->input('name'),
                    'organization' => $request->input('organization'),
                    'logo' => $path,
                ]);
            } else {
                WebsiteSetting::create([
                    'name' => $request->input('name'),
                    'organization' => $request->input('organization'),
                    'logo' => $path,
                ]);
            }
        } else {
            if ($websiteSetting) {
                $websiteSetting->update([
                    'name' => $request->input('name'),
                    'organization' => $request->input('organization'),
                ]);
            } else {
                WebsiteSetting::create([
                    'name' => $request->input('name'),
                    'organization' => $request->input('organization'),
                ]);
            }
        }

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Website settings saved successfully.');
    }


}
