<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\Franchise;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TrackingController extends Controller
{
    /**
     * Display the tracking and expiration alerts page.
     */
    public function index(Request $request)
    {
        $today = now()->toDateString();
        $warningDays = 30;
        $warningDate = now()->addDays($warningDays)->toDateString();

        // Expiring Drivers (within 30 days or expired)
        $expiringDrivers = Driver::where('status', 'active')
            ->whereNotNull('license_expiration')
            ->where('license_expiration', '<=', $warningDate)
            ->orderBy('license_expiration', 'asc')
            ->get();

        // Expiring Franchises
        $expiringFranchises = Franchise::with(['driver', 'operator', 'vehicle'])
            ->where('status', 'active')
            ->whereNotNull('valid_until')
            ->where('valid_until', '<=', $warningDate)
            ->orderBy('valid_until', 'asc')
            ->get();

        // Summary counts
        $totalExpiringDrivers = $expiringDrivers->count();
        $totalExpiringFranchises = $expiringFranchises->count();
        $totalAlerts = $totalExpiringDrivers + $totalExpiringFranchises;

        return view('tracking.index', compact(
            'expiringDrivers',
            'expiringFranchises',
            'totalExpiringDrivers',
            'totalExpiringFranchises',
            'totalAlerts',
            'warningDays'
        ));
    }
}
