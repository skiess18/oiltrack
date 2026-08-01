<?php

namespace App\Services;

use App\Models\Collection;

class WarehouseInventoryService
{
    public function currentStock(): float
    {
        return (float) Collection::sum('liters');
    }

    public function collectedBetween($from, $to): float
    {
        return (float) Collection::whereBetween('collection_date', [$from, $to])
            ->sum('liters');
    }

    public function recycledBetween($from, $to): float
    {
        return 0.0;
    }
}
