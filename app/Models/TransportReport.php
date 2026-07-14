<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransportReport extends Model
{
    protected $fillable = [
        'user_id',
        'vehicle_id',
        'date',
        'start_km',
        'end_km',
        'start_fuel',
        'end_fuel',
        'receipt',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }
}