<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Operator extends Model
{
    protected $fillable = [
        'operator_id',
        'first_name',
        'middle_name',
        'last_name',
        'address',
        'contact_number',
        'email',
        'status',
    ];

    public function vehicles()
    {
        return $this->hasMany(Vehicle::class);
    }
}