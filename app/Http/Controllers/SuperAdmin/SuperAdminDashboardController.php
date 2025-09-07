<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PVoucharDeduct;
use App\Models\RvChequeList;
use App\Models\VoucharEditRequest;
use App\Models\WebsiteSetting;

class SuperAdminDashboardController extends Controller
{
    public function dashboard(){
        $websiteSetting = WebsiteSetting::first();
        return view('layouts.super-admin.dashboard', compact('websiteSetting'));
    }

    public function account(){
        $deductCount = PVoucharDeduct::count();
        $unclearedCheque = RvChequeList::where('status', 'not like', '2')->count();
        $editRequest = VoucharEditRequest::where('edit_status', 'like', '0')->count();
        return view('layouts.super-admin.account_dashboard', compact('deductCount', 'unclearedCheque', 'editRequest'));
    }
}
