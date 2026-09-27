<?php

namespace App\Http\Controllers;

class ReportController extends Controller
{
    /**
     * Display reports summary page.
     */
    public function index()
    {
        return view('reports.index');
    }

    /**
     * Display reports output printable page for admin.
     */
    public function output()
    {
        return view('admin.reports-output');
    }
}
