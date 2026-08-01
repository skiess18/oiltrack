<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        'driver_id',
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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'driver_id');
    }
}