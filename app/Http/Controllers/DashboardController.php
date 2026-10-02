<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\Operator;
use App\Models\Franchise;
use App\Models\Vehicle;
use App\Models\Todo;
use App\Models\User;
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
                    'status' => 'Expiring',
                    'link' => route('drivers.show', $driver->id)
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
                    'status' => 'Expiring',
                    'link' => route('vehicles.show', $vehicle->id)
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
                    'status' => 'Expiring',
                    'link' => route('franchises.show', $franchise->id)
                ];
            });

        // Combine and sort by days remaining ascending
        $upcomingExpirations = $expiringDriversList
            ->concat($expiringVehiclesList)
            ->concat($expiringFranchisesList)
            ->sortBy('days_remaining')
            ->values();

        // -------------------------------------------------------------
        // MONTHLY TRENDS (LAST 6 MONTHS)
        // -------------------------------------------------------------
        $monthLabels = [];
        $driverTrend = [];
        $franchiseTrend = [];
        $vehicleTrend = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthStart = $date->copy()->startOfMonth();
            $monthEnd = $date->copy()->endOfMonth();
            $label = $date->format('M Y');
            $monthLabels[] = $label;

            $driverTrend[] = Driver::whereBetween('created_at', [$monthStart, $monthEnd])->count();
            $franchiseTrend[] = Franchise::whereBetween('created_at', [$monthStart, $monthEnd])->count();
            $vehicleTrend[] = Vehicle::whereBetween('created_at', [$monthStart, $monthEnd])->count();
        }

        // Active rate calculation
        $totalEntities = $totalFranchises + $totalDrivers + $totalVehicles;
        $totalActive = $activeFranchises + $activeDrivers + $activeVehicles;
        $activeRatePercentage = $totalEntities > 0 ? (int) round(($totalActive / $totalEntities) * 100) : 100;

        // -------------------------------------------------------------
        // RECENT REGISTRATIONS
        // -------------------------------------------------------------
        $recentDrivers = Driver::latest()->take(3)->get();
        $recentFranchises = Franchise::with(['operator', 'vehicle'])->latest()->take(3)->get();
        $recentVehicles = Vehicle::latest()->take(3)->get();

        // -------------------------------------------------------------
        // TO DO'S / WEEKLY TASKS
        // -------------------------------------------------------------
        $user = auth()->user();
        $weekStart = now()->startOfWeek(Carbon::MONDAY);
        $weekEnd = now()->endOfWeek(Carbon::SUNDAY);
        $currentWeekFormatted = $weekStart->format('F d') . ' – ' . $weekEnd->format('F d, Y');

        $staffMembers = User::whereHas('role', function($q) {
            $q->where('name', 'staff');
        })->orWhere('role_id', 2)->orderBy('name')->get();

        if ($user->role?->name === 'admin') {
            $todos = Todo::with(['assignedUser', 'creator'])
                ->orderByRaw("CASE WHEN status = 'pending' THEN 1 ELSE 2 END")
                ->orderBy('deadline', 'asc')
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            $todos = Todo::with('creator')
                ->where('assigned_to', $user->id)
                ->orderByRaw("CASE WHEN status = 'pending' THEN 1 ELSE 2 END")
                ->orderBy('deadline', 'asc')
                ->orderBy('created_at', 'desc')
                ->get();
        }

        $todoStats = [
            'total' => $todos->count(),
            'pending' => $todos->where('status', 'pending')->count(),
            'completed' => $todos->where('status', 'completed')->count(),
        ];

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
            'activeRate' => $activeRatePercentage,
            'trends' => [
                'labels' => $monthLabels,
                'drivers' => $driverTrend,
                'franchises' => $franchiseTrend,
                'vehicles' => $vehicleTrend,
            ],
            'recent' => [
                'drivers' => $recentDrivers,
                'franchises' => $recentFranchises,
                'vehicles' => $recentVehicles,
            ],
            'todos' => $todos,
            'todoStats' => $todoStats,
            'currentWeekFormatted' => $currentWeekFormatted,
            'staffMembers' => $staffMembers,
        ];

        // Determine user dashboard based on role
        if ($user->role?->name === 'admin') {
            return view('admin.dashboard', compact('stats'));
        } elseif ($user->role?->name === 'staff') {
            return view('staff.dashboard', compact('stats'));
        }

        abort(403, 'Unauthorized dashboard role access.');
    }
}
