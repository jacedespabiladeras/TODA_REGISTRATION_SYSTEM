<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\Operator;
use App\Models\Vehicle;
use App\Models\Franchise;
use App\Models\FranchiseRenewal;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Display reports summary page.
     */
    public function index()
    {
        $stats = [
            'drivers' => [
                'total' => Driver::count(),
                'active' => Driver::where('status', 'active')->count(),
                'inactive' => Driver::where('status', 'inactive')->count(),
            ],
            'operators' => [
                'total' => Operator::count(),
                'active' => Operator::where('status', 'active')->count(),
                'inactive' => Operator::where('status', 'inactive')->count(),
            ],
            'vehicles' => [
                'total' => Vehicle::count(),
                'registered' => Vehicle::where('status', 'registered')->count(),
                'pending' => Vehicle::where('status', 'pending')->count(),
            ],
            'franchises' => [
                'total' => Franchise::count(),
                'active' => Franchise::where('status', 'active')->count(),
                'expired' => Franchise::where('status', 'expired')->count(),
            ],
            'renewals' => [
                'total' => FranchiseRenewal::count(),
            ]
        ];

        return view('reports.index', compact('stats'));
    }

    /**
     * Display reports output printable page for admin.
     */
    public function output(Request $request)
    {
        $type = $request->query('type', 'all');

        $drivers = Driver::latest()->get();
        $operators = Operator::latest()->get();
        $vehicles = Vehicle::latest()->get();
        $franchises = Franchise::with(['driver', 'operator', 'vehicle'])->latest()->get();
        $renewals = FranchiseRenewal::with(['franchise', 'driver', 'operator', 'vehicle', 'processedBy'])->latest()->get();

        return view('admin.reports-output', compact('drivers', 'operators', 'vehicles', 'franchises', 'renewals', 'type'));
    }
}
