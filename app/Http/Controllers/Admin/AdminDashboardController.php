<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserRole;

class AdminDashboardController extends Controller
{
    public function dashboard(){
        $userRole = UserRole::where('user_id', auth()->id())->first();
        return view('layouts.admin.dashboard', compact('userRole'));
    }

    public function account(){
        $userRole = UserRole::where('user_id', auth()->id())->first();
        return view('layouts.admin.account', compact('userRole'));
    }
}
