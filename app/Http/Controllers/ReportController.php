<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Shift, User};
use Illuminate\View\View;

class ReportController extends Controller
{
    /**
    * Display the reports
    * @return View
    */
    public function show() : View
    {
        return view('report', compact('users', 'activityTime'));
    }
}
