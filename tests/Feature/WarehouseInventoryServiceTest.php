<?php

use App\Models\Client;
use App\Models\Collection;
use App\Models\WarehouseTransaction;
use App\Services\WarehouseInventoryService;

function warehouseTestClient(): Client
{
    return Client::create([
        'name' => 'Warehouse Test Client',
        'address' => '1 Test Street',
        'phone' => '0000000000',
    ]);
}

test('current warehouse stock subtracts withdrawals from collected liters', function () {
    $client = warehouseTestClient();

    Collection::create([
        'client_id' => $client->id,
        'collection_date' => '2026-08-01',
        'liters' => 100,
        'price_per_liter' => 1,
        'total_price' => 100,
    ]);

    WarehouseTransaction::create([
        'date' => '2026-08-02',
        'quantity' => 35,
        'buyer' => 'Recycler',
    ]);

    $inventory = app(WarehouseInventoryService::class);

    expect($inventory->currentStock())->toBe(65.0)
        ->and($inventory->collectedBetween('2026-08-01', '2026-08-31'))->toBe(100.0)
        ->and($inventory->recycledBetween('2026-08-01', '2026-08-31'))->toBe(35.0);
});

test('current warehouse stock is never negative', function () {
    $client = warehouseTestClient();

    Collection::create([
        'client_id' => $client->id,
        'collection_date' => '2026-08-01',
        'liters' => 10,
        'price_per_liter' => 1,
        'total_price' => 10,
    ]);

    WarehouseTransaction::create([
        'date' => '2026-08-02',
        'quantity' => 25,
        'buyer' => 'Recycler',
    ]);

    expect(app(WarehouseInventoryService::class)->currentStock())->toBe(0.0);
});
