<?php

namespace App\Http\Controllers\Gratuity;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GratuityReportController extends Controller
{
    public function index() {
        return view("layouts.pages.gratuity.report.index");
    }
}
