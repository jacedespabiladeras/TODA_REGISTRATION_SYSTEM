<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\Operator;
use App\Models\Franchise;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display the dashboard with real-time statistics.
     */
    public function index()
    {
        $today = now()->toDateString();
        $warningDays = 30;
        $warningDate = now()->addDays($warningDays)->toDateString();

        // -------------------------------------------------------------
        // DRIVERS STATISTICS
        // -------------------------------------------------------------
        $totalDrivers = Driver::count();

        $activeDrivers = Driver::where('status', 'active')
            ->where(function($query) use ($warningDate) {
                $query->whereNull('license_expiration')
                      ->orWhere('license_expiration', '>', $warningDate);
            })
            ->count();

        $expiringDrivers = Driver::where('status', 'active')
            ->whereNotNull('license_expiration')
            ->whereBetween('license_expiration', [$today, $warningDate])
            ->count();

        $inactiveDrivers = Driver::where(function($query) use ($today) {
            $query->where('status', 'inactive')
                  ->orWhere(function($q) use ($today) {
                      $q->where('status', 'active')
                        ->whereNotNull('license_expiration')
                        ->where('license_expiration', '<', $today);
                  });
        })->count();

        // -------------------------------------------------------------
        // OPERATORS STATISTICS
        // -------------------------------------------------------------
        $totalOperators = Operator::count();
        $activeOperators = Operator::where('status', 'active')->count();
        $expiringOperators = 0; // Operators table doesn't have an expiration date
        $inactiveOperators = Operator::where('status', 'inactive')->count();

        // -------------------------------------------------------------
        // VEHICLES STATISTICS
        // -------------------------------------------------------------
        $totalVehicles = Vehicle::count();

        $activeVehicles = Vehicle::where('status', 'active')
            ->where(function($query) use ($warningDate) {
                $query->whereNull('registration_expiration')
                      ->orWhere('registration_expiration', '>', $warningDate);
            })
            ->count();

        $expiringVehicles = Vehicle::where('status', 'active')
            ->whereNotNull('registration_expiration')
            ->whereBetween('registration_expiration', [$today, $warningDate])
            ->count();

        $inactiveVehicles = Vehicle::where(function($query) use ($today) {
            $query->where('status', 'inactive')
                  ->orWhere(function($q) use ($today) {
                      $q->where('status', 'active')
                        ->whereNotNull('registration_expiration')
                        ->where('registration_expiration', '<', $today);
                  });
        })->count();

        // -------------------------------------------------------------
        // FRANCHISES STATISTICS
        // -------------------------------------------------------------
        $totalFranchises = Franchise::count();

        $activeFranchises = Franchise::where('status', 'active')
            ->where('expiration_date', '>', $warningDate)
            ->count();

        $expiringFranchises = Franchise::where('status', 'active')
            ->whereBetween('expiration_date', [$today, $warningDate])
            ->count();

        $inactiveFranchises = Franchise::where(function($query) use ($today) {
            $query->whereIn('status', ['expired', 'cancelled'])
                  ->orWhere(function($q) use ($today) {
                      $q->where('status', 'active')
                        ->where('expiration_date', '<', $today);
                  });
        })->count();

        // -------------------------------------------------------------
        // UPCOMING EXPIRATIONS LIST (Drivers, Vehicles & Franchises)
        // -------------------------------------------------------------
        $expiringDriversList = Driver::where('status', 'active')
            ->whereNotNull('license_expiration')
            ->whereBetween('license_expiration', [$today, $warningDate])
            ->get()
            ->map(function($driver) {
                $expiration = Carbon::parse($driver->license_expiration);
                $daysRemaining = now()->startOfDay()->diffInDays($expiration->startOfDay(), false);
                return [
                    'type' => 'Driver',
                    'name_id' => $driver->first_name . ' ' . $driver->last_name . ' (' . $driver->driver_id . ')',
                    'expiration_date' => $expiration->format('M. d, Y'),
                    'days_remaining' => $daysRemaining,
                    'status' => 'Expiring'
                ];
            });

        $expiringVehiclesList = Vehicle::where('status', 'active')
            ->whereNotNull('registration_expiration')
            ->whereBetween('registration_expiration', [$today, $warningDate])
            ->get()
            ->map(function($vehicle) {
                $expiration = Carbon::parse($vehicle->registration_expiration);
                $daysRemaining = now()->startOfDay()->diffInDays($expiration->startOfDay(), false);
                return [
                    'type' => 'Vehicle',
                    'name_id' => $vehicle->plate_number . ' (' . $vehicle->vehicle_id . ')',
                    'expiration_date' => $expiration->format('M. d, Y'),
                    'days_remaining' => $daysRemaining,
                    'status' => 'Expiring'
                ];
            });

        $expiringFranchisesList = Franchise::where('status', 'active')
            ->whereBetween('expiration_date', [$today, $warningDate])
            ->get()
            ->map(function($franchise) {
                $expiration = Carbon::parse($franchise->expiration_date);
                $daysRemaining = now()->startOfDay()->diffInDays($expiration->startOfDay(), false);
                return [
                    'type' => 'Franchise',
                    'name_id' => $franchise->franchise_number,
                    'expiration_date' => $expiration->format('M. d, Y'),
                    'days_remaining' => $daysRemaining,
                    'status' => 'Expiring'
                ];
            });

        // Combine and sort by days remaining ascending
        $upcomingExpirations = $expiringDriversList
            ->concat($expiringVehiclesList)
            ->concat($expiringFranchisesList)
            ->sortBy('days_remaining')
            ->values();

        // Bundle data for compact passing to the view
        $stats = [
            'drivers' => [
                'total' => $totalDrivers,
                'active' => $activeDrivers,
                'expiring' => $expiringDrivers,
                'inactive' => $inactiveDrivers,
            ],
            'operators' => [
                'total' => $totalOperators,
                'active' => $activeOperators,
                'expiring' => $expiringOperators,
                'inactive' => $inactiveOperators,
            ],
            'vehicles' => [
                'total' => $totalVehicles,
                'active' => $activeVehicles,
                'expiring' => $expiringVehicles,
                'inactive' => $inactiveVehicles,
            ],
            'franchises' => [
                'total' => $totalFranchises,
                'active' => $activeFranchises,
                'expiring' => $expiringFranchises,
                'inactive' => $inactiveFranchises,
            ],
            'upcomingExpirations' => $upcomingExpirations,
        ];

        // Determine user dashboard based on role
        $user = auth()->user();
        if ($user->role?->name === 'admin') {
            return view('admin.dashboard', compact('stats'));
        } elseif ($user->role?->name === 'staff') {
            return view('staff.dashboard', compact('stats'));
        }

        abort(403, 'Unauthorized dashboard role access.');
    }
}
