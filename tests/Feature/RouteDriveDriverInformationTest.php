<?php

use App\Models\Client;
use App\Models\RoutePlan;
use App\Models\TransportReport;
use App\Models\User;
use App\Models\Vehicle;

test('route drive information renders only the driver name', function () {
    $driver = User::factory()->create([
        'name' => 'Vasilev',
        'role' => 'driver',
    ]);
    $vehicle = Vehicle::create([
        'brand' => 'Test',
        'model' => 'Van',
        'registration' => 'TEST-001',
    ]);
    $route = RoutePlan::create([
        'route_date' => today(),
        'driver_id' => $driver->id,
        'vehicle_id' => $vehicle->id,
        'status' => 'in_progress',
    ]);
    $client = Client::create([
        'name' => 'Test Client',
        'address' => '1 Test Street',
        'phone' => '0000000000',
    ]);
    $route->clients()->attach($client->id, [
        'position' => 1,
        'visited' => false,
    ]);
    TransportReport::create([
        'user_id' => $driver->id,
        'vehicle_id' => $vehicle->id,
        'date' => today(),
        'start_km' => 100,
        'start_fuel' => 50,
    ]);

    $this->actingAs($driver)
        ->get(route('routes.drive', $route))
        ->assertOk()
        ->assertSeeText('Информация')
        ->assertSeeText('Шофьор')
        ->assertSeeText('Vasilev')
        ->assertDontSee('{&quot;id&quot;:' . $driver->id)
        ->assertDontSee('{"id":' . $driver->id);
});
