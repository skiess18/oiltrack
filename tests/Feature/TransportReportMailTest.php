<?php

use App\Mail\TransportReportMail;
use App\Models\TransportReport;
use App\Models\User;
use App\Models\Vehicle;

test('transport report mail uses the driver assigned to the vehicle, not the report creator', function () {
    $admin = User::factory()->create([
        'name' => 'Admin',
        'role' => 'admin',
    ]);
    $driver = User::factory()->create([
        'name' => 'Георги',
        'role' => 'driver',
    ]);
    $vehicle = Vehicle::create([
        'brand' => 'Mercedes',
        'model' => 'Sprinter',
        'registration' => '0011',
        'driver' => 'Старо име',
        'driver_id' => $driver->id,
    ]);
    $report = TransportReport::create([
        'user_id' => $admin->id,
        'vehicle_id' => $vehicle->id,
        'date' => today(),
        'start_km' => 100,
        'end_km' => 120,
        'start_fuel' => 50,
        'end_fuel' => 40,
    ]);

    $rendered = (new TransportReportMail($report))->render();

    expect($rendered)
        ->toContain('Шофьор:</strong> Георги')
        ->toContain('Автомобил:</strong> 0011')
        ->not->toContain('Шофьор:</strong> Admin');
});

test('transport report mail falls back to the vehicle driver text when no driver user is assigned', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $vehicle = Vehicle::create([
        'brand' => 'Mercedes',
        'model' => 'Sprinter',
        'registration' => '0012',
        'driver' => 'Георги',
    ]);
    $report = TransportReport::create([
        'user_id' => $admin->id,
        'vehicle_id' => $vehicle->id,
        'date' => today(),
        'start_km' => 100,
        'end_km' => 120,
        'start_fuel' => 50,
        'end_fuel' => 40,
    ]);

    expect((new TransportReportMail($report))->render())
        ->toContain('Шофьор:</strong> Георги');
});
