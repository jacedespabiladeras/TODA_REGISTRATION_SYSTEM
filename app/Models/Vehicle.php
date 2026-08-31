<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    protected $fillable = [
        'vehicle_id',
        'plate_number',
        'make',
        'model',
        'color',
        'motor_number',
        'chassis_number',
        'vehicle_type',
        'registration_expiration',
        'status',
        'driver_id',
        'operator_id',
    ];

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function operator()
    {
        return $this->belongsTo(Operator::class);
    }
}