<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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

    public function operator()
    {
        return $this->belongsTo(Operator::class);
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }
}