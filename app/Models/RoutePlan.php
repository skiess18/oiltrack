<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoutePlan extends Model
{
    protected $fillable = [
        'route_date',
        'driver',
        'notes',
        'status',
    ];

    /**
     * Обектите в маршрута
     */
    public function clients()
    {
        return $this->belongsToMany(
            Client::class,
            'route_plan_client'
        )
        ->withPivot('position', 'visited')
        ->withTimestamps()
        ->orderBy('pivot_position');
    }
}