<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
        'representative',
        'email',
        'capacity',
        'notes',
        'company_name',
        'bulstat',
        'payment_method',
        'price_per_liter',
        'visit_interval_days',
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

    public function emailRecipients(): HasMany
    {
        return $this->hasMany(ClientEmailRecipient::class);
    }

    public function syncEmailRecipients(array $emails): void
    {
        $emails = collect($emails)
            ->flatMap(fn ($email) => preg_split('/[\s,;]+/', (string) $email, -1, PREG_SPLIT_NO_EMPTY))
            ->map(fn ($email) => strtolower(trim((string) $email)))
            ->filter(fn ($email) => filter_var($email, FILTER_VALIDATE_EMAIL))
            ->unique()
            ->values();

        $this->emailRecipients()->whereNotIn('email', $emails->all())->delete();
        $this->emailRecipients()->upsert(
            $emails->map(fn ($email) => ['client_id' => $this->id, 'email' => $email, 'created_at' => now(), 'updated_at' => now()])->all(),
            ['client_id', 'email'],
            ['updated_at']
        );

        $this->forceFill(['email' => $emails->first()])->save();
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
