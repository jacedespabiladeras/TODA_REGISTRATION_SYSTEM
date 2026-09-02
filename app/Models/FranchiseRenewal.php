<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FranchiseRenewal extends Model
{
    protected $fillable = [
        'franchise_id',
        'previous_expiration_date',
        'new_expiration_date',
        'renewal_date',
        'reference_number',
        'renewal_fee',
        'remarks',
        'processed_by',
    ];

    protected $casts = [
        'previous_expiration_date' => 'date',
        'new_expiration_date' => 'date',
        'renewal_date' => 'date',
        'renewal_fee' => 'decimal:2',
    ];

    public function franchise(): BelongsTo
    {
        return $this->belongsTo(Franchise::class);
    }

    public function processor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    public function processedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }
}
