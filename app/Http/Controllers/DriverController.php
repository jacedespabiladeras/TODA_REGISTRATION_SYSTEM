<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DriverController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Driver::query();

        // Search filter
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('driver_id', 'like', "%{$search}%")
                  ->orWhere('first_name', 'like', "%{$search}%")
                  ->orWhere('middle_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('license_number', 'like', "%{$search}%")
                  ->orWhere('contact_number', 'like', "%{$search}%")
                  ->orWhereRaw("first_name || ' ' || last_name LIKE ?", ["%{$search}%"])
                  ->orWhereRaw("first_name || ' ' || middle_name || ' ' || last_name LIKE ?", ["%{$search}%"]);
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
                          $q->whereNull('license_expiration')
                            ->orWhere('license_expiration', '>', $warningDate);
                      });
            } elseif ($status === 'expiring') {
                $query->where('status', 'active')
                      ->whereNotNull('license_expiration')
                      ->whereBetween('license_expiration', [$today, $warningDate]);
            } elseif ($status === 'inactive') {
                $query->where(function($q) use ($today) {
                    $q->where('status', 'inactive')
                      ->orWhere(function($sub) use ($today) {
                          $sub->where('status', 'active')
                              ->whereNotNull('license_expiration')
                              ->where('license_expiration', '<', $today);
                      });
                });
            }
        }

        $drivers = $query->paginate(10)->withQueryString();

        return view('drivers.index', compact('drivers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('drivers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'contact_number' => 'required|string|max:20',
            'license_number' => 'required|string|max:50|unique:drivers,license_number',
            'license_expiration' => 'nullable|date',
            'status' => 'required|in:active,inactive',
        ]);

        // Generate auto-incrementing driver ID: DRV-YYYY-XXXX
        $year = now()->year;
        $prefix = "DRV-{$year}-";
        $latest = Driver::where('driver_id', 'like', $prefix . '%')
            ->orderBy('id', 'desc')
            ->first();

        $nextNumber = 1;
        if ($latest) {
            $parts = explode('-', $latest->driver_id);
            $nextNumber = intval(end($parts)) + 1;
        }
        $driverId = $prefix . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

        Driver::create(array_merge($request->all(), [
            'driver_id' => $driverId
        ]));

        return redirect()->route('drivers.index')
            ->with('success', 'Driver successfully registered.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Driver $driver)
    {
        // Load associated vehicles
        $driver->load('vehicles');
        return view('drivers.show', compact('driver'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Driver $driver)
    {
        return view('drivers.edit', compact('driver'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Driver $driver)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'contact_number' => 'required|string|max:20',
            'license_number' => 'required|string|max:50|unique:drivers,license_number,' . $driver->id,
            'license_expiration' => 'nullable|date',
            'status' => 'required|in:active,inactive',
        ]);

        $driver->update($request->all());

        return redirect()->route('drivers.index')
            ->with('success', 'Driver information updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Driver $driver)
    {
        if (auth()->user()->role?->name !== 'admin') {
            abort(403, 'Unauthorized action. Only administrators can delete records.');
        }

        try {
            // Check if driver has vehicles assigned
            if ($driver->vehicles()->count() > 0) {
                return back()->with('error', 'Cannot delete this driver because they are currently assigned to one or more vehicles. Please remove their assignment first.');
            }

            $driver->delete();

            return redirect()->route('drivers.index')
                ->with('success', 'Driver deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'An error occurred while deleting the driver record: ' . $e->getMessage());
        }
    }
}
