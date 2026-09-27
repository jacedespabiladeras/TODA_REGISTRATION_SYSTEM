<?php

namespace App\Http\Controllers;

class TrackingController extends Controller
{
    /**
     * Display the tracking and expiration alerts page.
     */
    public function index()
    {
        return view('tracking.index');
    }
}
