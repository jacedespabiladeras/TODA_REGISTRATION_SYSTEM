<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    protected $fillable = [
        'driver_id',
        'first_name',
        'middle_name',
        'last_name',
        'address',
        'contact_number',
        'license_number',
        'license_expiration',
        'status',
    ];
}