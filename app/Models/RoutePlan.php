<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoutePlan extends Model
{
    protected $fillable = [

        'route_date',

        'driver_id',

        'vehicle_id',

        'notes',

        'status',

    ];

    /**
     * Шофьор
     */
    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    /**
     * Автомобил
     */
    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    /**
     * Обекти в маршрута
     */
    public function clients()
    {
        return $this->belongsToMany(
            Client::class,
            'route_plan_client'
        )
        ->withPivot([
            'position',
            'visited',
            'arrived_at',
            'latitude',
            'longitude'
        ])
        ->withTimestamps()
        ->orderBy('pivot_position');
    }
}