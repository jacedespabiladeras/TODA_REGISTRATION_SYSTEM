<?php

namespace App\Http\Controllers;

use App\Models\Franchise;
use App\Models\Operator;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Carbon\Carbon;

class FranchiseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $today = now()->toDateString();
        $warningDays = 30;
        $warningDate = now()->addDays($warningDays)->toDateString();

        // -------------------------------------------------------------
        // STATS SUMMARY (Aligned with DashboardController)
        // -------------------------------------------------------------
        $totalFranchises = Franchise::count();

        $activeFranchises = Franchise::where('status', 'active')
            ->where('expiration_date', '>', $warningDate)
            ->count();

        $expiringFranchises = Franchise::where('status', 'active')
            ->whereBetween('expiration_date', [$today, $warningDate])
            ->count();

        $inactiveFranchises = Franchise::where(function ($query) use ($today) {
            $query->whereIn('status', ['expired', 'cancelled'])
                ->orWhere(function ($q) use ($today) {
                    $q->where('status', 'active')
                        ->where('expiration_date', '<', $today);
                });
        })->count();

        $stats = [
            'total' => $totalFranchises,
            'active' => $activeFranchises,
            'expiring' => $expiringFranchises,
            'inactive' => $inactiveFranchises,
        ];

        // -------------------------------------------------------------
        // UPCOMING EXPIRATIONS (Next 30 Days, sorted closest first)
        // -------------------------------------------------------------
        $upcomingExpirations = Franchise::with(['operator', 'vehicle'])
            ->where('status', 'active')
            ->whereBetween('expiration_date', [$today, $warningDate])
            ->orderBy('expiration_date', 'asc')
            ->take(5)
            ->get();

        // -------------------------------------------------------------
        // MAIN QUERY BUILDER WITH SEARCH & FILTERS
        // -------------------------------------------------------------
        $query = Franchise::with(['operator', 'vehicle.driver']);

        // Search Filter
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('franchise_number', 'like', "%{$search}%")
                    ->orWhereHas('operator', function ($sub) use ($search) {
                        $sub->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%")
                            ->orWhere('operator_id', 'like', "%{$search}%")
                            ->orWhereRaw("first_name || ' ' || last_name LIKE ?", ["%{$search}%"])
                            ->orWhereRaw("first_name || ' ' || middle_name || ' ' || last_name LIKE ?", ["%{$search}%"]);
                    })
                    ->orWhereHas('vehicle', function ($sub) use ($search) {
                        $sub->where('plate_number', 'like', "%{$search}%")
                            ->orWhere('vehicle_id', 'like', "%{$search}%")
                            ->orWhere('make', 'like', "%{$search}%")
                            ->orWhere('model', 'like', "%{$search}%");
                    })
                    ->orWhereHas('renewals', function ($sub) use ($search) {
                        $sub->where('reference_number', 'like', "%{$search}%");
                    });
            });
        }

        // Status Filter
        if ($request->filled('status') && $request->input('status') !== 'all') {
            $status = $request->input('status');

            if ($status === 'active') {
                $query->where('status', 'active')
                    ->where('expiration_date', '>', $warningDate);
            } elseif ($status === 'expiring') {
                $query->where('status', 'active')
                    ->whereBetween('expiration_date', [$today, $warningDate]);
            } elseif ($status === 'inactive') {
                $query->where(function ($q) use ($today) {
                    $q->whereIn('status', ['expired', 'cancelled'])
                        ->orWhere(function ($sub) use ($today) {
                            $sub->where('status', 'active')
                                ->where('expiration_date', '<', $today);
                        });
                });
            }
        }

        // Expiration Period Filter
        if ($request->filled('expiration_filter') && $request->input('expiration_filter') !== 'all') {
            $expFilter = $request->input('expiration_filter');
            if ($expFilter === '7_days') {
                $sevenDays = now()->addDays(7)->toDateString();
                $query->where('status', 'active')
                    ->whereBetween('expiration_date', [$today, $sevenDays]);
            } elseif ($expFilter === '30_days') {
                $query->where('status', 'active')
                    ->whereBetween('expiration_date', [$today, $warningDate]);
            } elseif ($expFilter === 'expired') {
                $query->where(function ($q) use ($today) {
                    $q->whereIn('status', ['expired', 'cancelled'])
                        ->orWhere('expiration_date', '<', $today);
                });
            }
        }

        $franchises = $query->orderBy('id', 'desc')->paginate(10)->withQueryString();

        return view('franchises.index', compact('franchises', 'stats', 'upcomingExpirations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $operators = Operator::where('status', 'active')
            ->orderBy('last_name')
            ->get();

        $vehicles = Vehicle::with(['operator', 'driver'])
            ->where('status', 'active')
            ->orderBy('plate_number')
            ->get();

        // Generate auto-incrementing Franchise Number: FR-YYYY-XXXX
        $year = now()->year;
        $prefix = "FR-{$year}-";
        $latest = Franchise::where('franchise_number', 'like', $prefix . '%')
            ->orderBy('id', 'desc')
            ->first();

        $nextNumber = 1;
        if ($latest) {
            $parts = explode('-', $latest->franchise_number);
            $nextNumber = intval(end($parts)) + 1;
        }
        $suggestedFranchiseNumber = $prefix . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

        return view('franchises.create', compact('operators', 'vehicles', 'suggestedFranchiseNumber'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'franchise_number' => 'required|string|max:50|unique:franchises,franchise_number',
            'operator_id' => 'required|exists:operators,id',
            'vehicle_id' => 'required|exists:vehicles,id',
            'franchise_date' => 'required|date',
            'expiration_date' => 'required|date|after_or_equal:franchise_date',
            'status' => 'required|in:active,expired,cancelled',
        ]);

        Franchise::create($request->all());

        return redirect()->route('franchises.index')
            ->with('success', 'Franchise successfully registered.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Franchise $franchise)
    {
        $franchise->load([
            'operator',
            'vehicle.driver',
            'renewals.processedBy',
        ]);

        return view('franchises.show', compact('franchise'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Franchise $franchise)
    {
        $operators = Operator::orderBy('last_name')->get();
        $vehicles = Vehicle::with(['operator', 'driver'])->orderBy('plate_number')->get();

        return view('franchises.edit', compact('franchise', 'operators', 'vehicles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Franchise $franchise)
    {
        $request->validate([
            'franchise_number' => 'required|string|max:50|unique:franchises,franchise_number,' . $franchise->id,
            'operator_id' => 'required|exists:operators,id',
            'vehicle_id' => 'required|exists:vehicles,id',
            'franchise_date' => 'required|date',
            'expiration_date' => 'required|date',
            'status' => 'required|in:active,expired,cancelled',
        ]);

        $franchise->update($request->all());

        return redirect()->route('franchises.show', $franchise->id)
            ->with('success', 'Franchise information updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Franchise $franchise)
    {
        if (auth()->user()->role?->name !== 'admin') {
            abort(403, 'Unauthorized action. Only administrators can delete records.');
        }

        try {
            $franchise->delete();

            return redirect()->route('franchises.index')
                ->with('success', 'Franchise deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'An error occurred while deleting the franchise record: ' . $e->getMessage());
        }
    }
}
