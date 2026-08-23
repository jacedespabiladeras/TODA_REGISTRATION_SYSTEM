<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $fillable = [
        'member_id',
        'first_name',
        'middle_name',
        'last_name',
        'address',
        'contact_number',
        'membership_date',
        'status',
    ];

    protected $casts = [
        'membership_date' => 'date',
    ];
}