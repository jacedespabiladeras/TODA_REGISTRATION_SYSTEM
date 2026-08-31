<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\Driver;
use App\Models\Operator;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Vehicle::with(['driver', 'operator']);

        // Search filter
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('plate_number', 'like', "%{$search}%")
                  ->orWhere('vehicle_id', 'like', "%{$search}%")
                  ->orWhere('motor_number', 'like', "%{$search}%")
                  ->orWhere('chassis_number', 'like', "%{$search}%")
                  ->orWhere('vehicle_type', 'like', "%{$search}%")
                  ->orWhereHas('driver', function($sub) use ($search) {
                      $sub->where('first_name', 'like', "%{$search}%")
                          ->orWhere('last_name', 'like', "%{$search}%")
                          ->orWhereRaw("first_name || ' ' || last_name LIKE ?", ["%{$search}%"]);
                  })
                  ->orWhereHas('operator', function($sub) use ($search) {
                      $sub->where('first_name', 'like', "%{$search}%")
                          ->orWhere('last_name', 'like', "%{$search}%")
                          ->orWhereRaw("first_name || ' ' || last_name LIKE ?", ["%{$search}%"]);
                  });
            });
        }

        // Status filter
        if ($request->filled('status') && $request->input('status') !== 'all') {
            $status = $request->input('status');
            $today = now()->toDateString();
            $warningDate = now()->addDays(30)->toDateString();

            if ($status === 'active') {
                $query->where('status', 'active')
                      ->where(function($q) use ($warningDate) {
                          $q->whereNull('registration_expiration')
                            ->orWhere('registration_expiration', '>', $warningDate);
                      });
            } elseif ($status === 'expiring') {
                $query->where('status', 'active')
                      ->whereNotNull('registration_expiration')
                      ->whereBetween('registration_expiration', [$today, $warningDate]);
            } elseif ($status === 'inactive') {
                $query->where(function($q) use ($today) {
                    $q->where('status', 'inactive')
                      ->orWhere(function($sub) use ($today) {
                          $sub->where('status', 'active')
                              ->whereNotNull('registration_expiration')
                              ->where('registration_expiration', '<', $today);
                      });
                });
            }
        }

        $vehicles = $query->paginate(10)->withQueryString();

        return view('vehicles.index', compact('vehicles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $drivers = Driver::where('status', 'active')->orderBy('last_name')->get();
        $operators = Operator::where('status', 'active')->orderBy('last_name')->get();
        return view('vehicles.create', compact('drivers', 'operators'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'plate_number' => 'required|string|max:50|unique:vehicles,plate_number',
            'make' => 'nullable|string|max:255',
            'model' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:255',
            'motor_number' => 'nullable|string|max:255',
            'chassis_number' => 'nullable|string|max:255',
            'vehicle_type' => 'nullable|string|max:255',
            'registration_expiration' => 'nullable|date',
            'status' => 'required|in:active,inactive',
            'driver_id' => 'nullable|exists:drivers,id',
            'operator_id' => 'nullable|exists:operators,id',
        ]);

        // Generate auto-incrementing vehicle ID: VEH-YYYY-XXXX
        $year = now()->year;
        $prefix = "VEH-{$year}-";
        $latest = Vehicle::where('vehicle_id', 'like', $prefix . '%')
            ->orderBy('id', 'desc')
            ->first();

        $nextNumber = 1;
        if ($latest) {
            $parts = explode('-', $latest->vehicle_id);
            $nextNumber = intval(end($parts)) + 1;
        }
        $vehicleId = $prefix . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

        Vehicle::create(array_merge($request->all(), [
            'vehicle_id' => $vehicleId
        ]));

        return redirect()->route('vehicles.index')
            ->with('success', 'Vehicle successfully registered.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Vehicle $vehicle)
    {
        $vehicle->load(['driver', 'operator']);
        return view('vehicles.show', compact('vehicle'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Vehicle $vehicle)
    {
        $drivers = Driver::where('status', 'active')->orderBy('last_name')->get();
        $operators = Operator::where('status', 'active')->orderBy('last_name')->get();
        return view('vehicles.edit', compact('vehicle', 'drivers', 'operators'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Vehicle $vehicle)
    {
        $request->validate([
            'plate_number' => 'required|string|max:50|unique:vehicles,plate_number,' . $vehicle->id,
            'make' => 'nullable|string|max:255',
            'model' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:255',
            'motor_number' => 'nullable|string|max:255',
            'chassis_number' => 'nullable|string|max:255',
            'vehicle_type' => 'nullable|string|max:255',
            'registration_expiration' => 'nullable|date',
            'status' => 'required|in:active,inactive',
            'driver_id' => 'nullable|exists:drivers,id',
            'operator_id' => 'nullable|exists:operators,id',
        ]);

        $vehicle->update($request->all());

        return redirect()->route('vehicles.index')
            ->with('success', 'Vehicle information updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vehicle $vehicle)
    {
        if (auth()->user()->role?->name !== 'admin') {
            abort(403, 'Unauthorized action. Only administrators can delete records.');
        }

        try {
            // Check if vehicle has any franchises linked to it
            $franchiseCount = \DB::table('franchises')->where('vehicle_id', $vehicle->id)->count();
            if ($franchiseCount > 0) {
                return back()->with('error', 'Cannot delete this vehicle because it is currently linked to one or more franchises. Please delete the associated franchises first.');
            }

            $vehicle->delete();

            return redirect()->route('vehicles.index')
                ->with('success', 'Vehicle deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'An error occurred while deleting the vehicle record: ' . $e->getMessage());
        }
    }
}
