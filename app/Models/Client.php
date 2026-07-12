<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Client extends Model
{
    protected $fillable = [
        'name',
        'address',
        'latitude',
        'longitude',
        'phone',
        'contact_person',
        'capacity',
        'notes',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * Всички събирания
     */
    public function collections()
    {
        return $this->hasMany(Collection::class)
            ->orderByDesc('collection_date');
    }

    /**
     * Маршрути
     */
    public function routePlans()
    {
        return $this->belongsToMany(
            RoutePlan::class,
            'route_plan_client'
        )
            ->withPivot('position', 'visited')
            ->withTimestamps();
    }

    /*
    |--------------------------------------------------------------------------
    | Statistics
    |--------------------------------------------------------------------------
    */

    /**
     * Общо събрано олио
     */
    public function getTotalLitersAttribute()
    {
        return $this->collections()->sum('liters');
    }

    /**
     * Общ приход
     */
    public function getTotalRevenueAttribute()
    {
        return $this->collections()->sum('total_price');
    }

    /**
     * Брой събирания
     */
    public function getCollectionsCountAttribute()
    {
        return $this->collections()->count();
    }

    /**
     * Средно количество
     */
    public function getAverageLitersAttribute()
    {
        return round(
            $this->collections()->avg('liters') ?? 0,
            2
        );
    }

    /**
     * Последно събиране
     */
    public function getLastCollectionAttribute()
    {
        return $this->collections()->first();
    }

    /**
     * Дни от последното събиране
     */
    public function getDaysSinceLastCollectionAttribute()
    {
        if (!$this->lastCollection) {
            return null;
        }

        return Carbon::parse(
            $this->lastCollection->collection_date
        )->diffInDays(now());
    }

    /**
     * Нуждае ли се от посещение
     */
    public function getNeedsCollectionAttribute()
    {
        if (!$this->lastCollection) {
            return true;
        }

        return $this->days_since_last_collection >= 14;
    }

    /**
     * Процент запълване (ориентировъчно)
     */
    public function getFillPercentageAttribute()
    {
        if ($this->capacity <= 0) {
            return 0;
        }

        $average = $this->average_liters;

        return min(
            round(($average / $this->capacity) * 100),
            100
        );
    }
}