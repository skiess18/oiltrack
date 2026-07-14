<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    protected $fillable = [

        'brand',
        'model',
        'year',

        'registration',

        'vin',

        'color',

        'photo',

        'driver',

        'fuel_consumption',

        'current_km',

        'last_service',

        'next_service_km',

        'inspection_date',

        'insurance_date',

        'status',

        'notes',

    ];

    protected $casts = [

        'last_service' => 'date',

        'inspection_date' => 'date',

        'insurance_date' => 'date',

    ];
}