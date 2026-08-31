<?php

namespace App\Http\Controllers;

use App\Models\Operator;
use Illuminate\Http\Request;

class OperatorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Operator::query();

        // Search filter
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('operator_id', 'like', "%{$search}%")
                  ->orWhere('first_name', 'like', "%{$search}%")
                  ->orWhere('middle_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('contact_number', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhereRaw("first_name || ' ' || last_name LIKE ?", ["%{$search}%"])
                  ->orWhereRaw("first_name || ' ' || middle_name || ' ' || last_name LIKE ?", ["%{$search}%"]);
            });
        }

        // Status filter
        if ($request->filled('status') && $request->input('status') !== 'all') {
            $status = $request->input('status');
            $query->where('status', $status);
        }

        $operators = $query->paginate(10)->withQueryString();

        return view('operators.index', compact('operators'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('operators.create');
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
            'email' => 'nullable|email|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        // Generate auto-incrementing operator ID: OPR-YYYY-XXXX
        $year = now()->year;
        $prefix = "OPR-{$year}-";
        $latest = Operator::where('operator_id', 'like', $prefix . '%')
            ->orderBy('id', 'desc')
            ->first();

        $nextNumber = 1;
        if ($latest) {
            $parts = explode('-', $latest->operator_id);
            $nextNumber = intval(end($parts)) + 1;
        }
        $operatorId = $prefix . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

        Operator::create(array_merge($request->all(), [
            'operator_id' => $operatorId
        ]));

        return redirect()->route('operators.index')
            ->with('success', 'Operator successfully registered.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Operator $operator)
    {
        // Load associated vehicles
        $operator->load('vehicles');
        return view('operators.show', compact('operator'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Operator $operator)
    {
        return view('operators.edit', compact('operator'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Operator $operator)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'contact_number' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        $operator->update($request->all());

        return redirect()->route('operators.index')
            ->with('success', 'Operator information updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Operator $operator)
    {
        if (auth()->user()->role?->name !== 'admin') {
            abort(403, 'Unauthorized action. Only administrators can delete records.');
        }

        try {
            // Check if operator has vehicles assigned
            if ($operator->vehicles()->count() > 0) {
                return back()->with('error', 'Cannot delete this operator because they are currently assigned to one or more vehicles. Please remove their assignment first.');
            }

            // Check if operator is linked to franchises
            // Operators have a constrained relation in franchises table.
            $franchiseCount = \DB::table('franchises')->where('operator_id', $operator->id)->count();
            if ($franchiseCount > 0) {
                return back()->with('error', 'Cannot delete this operator because they are linked to one or more franchises. Please delete the associated franchises first.');
            }

            $operator->delete();

            return redirect()->route('operators.index')
                ->with('success', 'Operator deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'An error occurred while deleting the operator record: ' . $e->getMessage());
        }
    }
}
