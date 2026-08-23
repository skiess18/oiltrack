<?php

namespace App\Services;

use App\Models\Collection;
use App\Models\WarehouseTransaction;

class WarehouseInventoryService
{
    public function currentStock(): float
    {
        $collectedLiters = (float) Collection::sum('liters');
        $withdrawnLiters = (float) WarehouseTransaction::sum('quantity');

        return max(0.0, $collectedLiters - $withdrawnLiters);
    }

    public function collectedBetween($from, $to): float
    {
        return (float) Collection::whereBetween('collection_date', [$from, $to])
            ->sum('liters');
    }

    public function recycledBetween($from, $to): float
    {
        return (float) WarehouseTransaction::whereBetween('date', [$from, $to])
            ->sum('quantity');
    }
}
