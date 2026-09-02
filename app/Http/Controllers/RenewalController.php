<?php

namespace App\Http\Controllers;

use App\Models\Franchise;
use App\Models\FranchiseRenewal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RenewalController extends Controller
{
    /**
     * Display Franchise Renewal Management Hub.
     */
    public function index(Request $request)
    {
        $today = now()->toDateString();
        $warningDate = now()->addDays(30)->toDateString();

        // Franchises due or approaching expiration (Expiring within 30 days or already Inactive)
        $dueQuery = Franchise::with(['operator', 'vehicle'])
            ->where(function ($q) use ($today, $warningDate) {
                $q->where('status', 'active')
                    ->whereBetween('expiration_date', [$today, $warningDate])
                    ->orWhere(function ($sub) use ($today) {
                        $sub->whereIn('status', ['expired', 'cancelled'])
                            ->orWhere('expiration_date', '<', $today);
                    });
            });

        if ($request->filled('due_search')) {
            $dueSearch = $request->input('due_search');
            $dueQuery->where(function ($q) use ($dueSearch) {
                $q->where('franchise_number', 'like', "%{$dueSearch}%")
                    ->orWhereHas('operator', function ($sub) use ($dueSearch) {
                        $sub->where('first_name', 'like', "%{$dueSearch}%")
                            ->orWhere('last_name', 'like', "%{$dueSearch}%");
                    })
                    ->orWhereHas('vehicle', function ($sub) use ($dueSearch) {
                        $sub->where('plate_number', 'like', "%{$dueSearch}%");
                    });
            });
        }

        $dueFranchises = $dueQuery->orderBy('expiration_date', 'asc')->paginate(10, ['*'], 'due_page')->withQueryString();

        // All Renewal Transaction History Records
        $historyQuery = FranchiseRenewal::with(['franchise.operator', 'franchise.vehicle', 'processedBy']);

        if ($request->filled('history_search')) {
            $histSearch = $request->input('history_search');
            $historyQuery->where(function ($q) use ($histSearch) {
                $q->where('reference_number', 'like', "%{$histSearch}%")
                    ->orWhere('remarks', 'like', "%{$histSearch}%")
                    ->orWhereHas('franchise', function ($sub) use ($histSearch) {
                        $sub->where('franchise_number', 'like', "%{$histSearch}%")
                            ->orWhereHas('operator', function ($op) use ($histSearch) {
                                $op->where('first_name', 'like', "%{$histSearch}%")
                                    ->orWhere('last_name', 'like', "%{$histSearch}%");
                            })
                            ->orWhereHas('vehicle', function ($veh) use ($histSearch) {
                                $veh->where('plate_number', 'like', "%{$histSearch}%");
                            });
                    })
                    ->orWhereHas('processedBy', function ($user) use ($histSearch) {
                        $user->where('name', 'like', "%{$histSearch}%");
                    });
            });
        }

        $renewalHistory = $historyQuery->orderBy('id', 'desc')->paginate(10, ['*'], 'history_page')->withQueryString();

        $totalRenewalsCount = FranchiseRenewal::count();
        $thisMonthRenewalsCount = FranchiseRenewal::whereMonth('renewal_date', now()->month)
            ->whereYear('renewal_date', now()->year)
            ->count();
        $totalExpiringCount = Franchise::where('status', 'active')
            ->whereBetween('expiration_date', [$today, $warningDate])
            ->count();

        return view('renewals.index', compact(
            'dueFranchises',
            'renewalHistory',
            'totalRenewalsCount',
            'thisMonthRenewalsCount',
            'totalExpiringCount'
        ));
    }

    /**
     * Show the renewal form for a specific franchise.
     */
    public function renew(Franchise $franchise)
    {
        $franchise->load(['operator', 'vehicle.driver']);

        $today = now()->startOfDay();
        $currentExpiration = $franchise->expiration_date ? Carbon::parse($franchise->expiration_date)->startOfDay() : $today;

        // Suggested new expiration date: 1 year from current expiration if still valid, otherwise 1 year from today
        $suggestedNewExpiration = $currentExpiration->gt($today)
            ? $currentExpiration->copy()->addYear()->toDateString()
            : $today->copy()->addYear()->toDateString();

        return view('franchises.renew', compact('franchise', 'suggestedNewExpiration'));
    }

    /**
     * Process the renewal of a franchise.
     */
    public function processRenewal(Request $request, Franchise $franchise)
    {
        $request->validate([
            'renewal_date' => 'required|date',
            'new_expiration_date' => 'required|date|after:' . ($franchise->expiration_date ? $franchise->expiration_date->toDateString() : 'today'),
            'reference_number' => 'nullable|string|max:100',
            'renewal_fee' => 'nullable|numeric|min:0',
            'remarks' => 'nullable|string|max:1000',
        ], [
            'new_expiration_date.after' => 'The new expiration date must be later than the current expiration date (' . ($franchise->expiration_date ? $franchise->expiration_date->format('M d, Y') : 'today') . ').',
        ]);

        DB::transaction(function () use ($request, $franchise) {
            // 1. Record in renewal history
            FranchiseRenewal::create([
                'franchise_id' => $franchise->id,
                'previous_expiration_date' => $franchise->expiration_date ? $franchise->expiration_date->toDateString() : null,
                'new_expiration_date' => $request->new_expiration_date,
                'renewal_date' => $request->renewal_date,
                'reference_number' => $request->reference_number,
                'renewal_fee' => $request->renewal_fee,
                'remarks' => $request->remarks,
                'processed_by' => auth()->id(),
            ]);

            // 2. Update franchise expiration date and active status
            $franchise->update([
                'expiration_date' => $request->new_expiration_date,
                'status' => 'active',
            ]);
        });

        return redirect()->route('franchises.show', $franchise->id)
            ->with('success', 'Franchise successfully renewed.');
    }
}
