<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Franchise extends Model
{
    protected $fillable = [
        'franchise_number',
        'operator_id',
        'vehicle_id',
        'franchise_date',
        'expiration_date',
        'status',
    ];

    protected $casts = [
        'franchise_date' => 'date',
        'expiration_date' => 'date',
    ];

    public function operator(): BelongsTo
    {
        return $this->belongsTo(Operator::class);
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function renewals(): HasMany
    {
        return $this->hasMany(FranchiseRenewal::class)->orderBy('id', 'desc');
    }

    /**
     * Get dynamically calculated status based on expiration date.
     * Matches DashboardController logic.
     */
    public function getCalculatedStatusAttribute(): string
    {
        if ($this->status === 'cancelled') {
            return 'Cancelled';
        }

        if ($this->status === 'expired') {
            return 'Inactive';
        }

        if (!$this->expiration_date) {
            return ucfirst($this->status);
        }

        $today = now()->startOfDay();
        $expiration = Carbon::parse($this->expiration_date)->startOfDay();

        if ($expiration->lt($today)) {
            return 'Inactive';
        }

        if ($today->diffInDays($expiration, false) <= 30) {
            return 'Expiring';
        }

        return 'Active';
    }

    /**
     * Get days remaining until expiration.
     */
    public function getDaysRemainingAttribute(): ?int
    {
        if (!$this->expiration_date) {
            return null;
        }

        $today = now()->startOfDay();
        $expiration = Carbon::parse($this->expiration_date)->startOfDay();

        return (int) $today->diffInDays($expiration, false);
    }

    /**
     * Get CSS badge style for status.
     */
    public function getStatusBadgeStyleAttribute(): string
    {
        $status = $this->calculated_status;

        return match ($status) {
            'Active' => 'background-color: #d1e7dd; color: #0f5132;',
            'Expiring' => 'background-color: #fff3cd; color: #664d03;',
            'Inactive', 'Expired' => 'background-color: #f8d7da; color: #842029;',
            'Cancelled' => 'background-color: #e2e3e5; color: #383d41;',
            default => 'background-color: #e2e3e5; color: #383d41;',
        };
    }
}