<?php

use App\Models\TransportReport;
use App\Models\User;

test('the dashboard displays only the driver name from the report relationship', function () {
    $driver = User::factory()->create([
        'name' => 'Vasilev',
        'role' => 'driver',
    ]);

    TransportReport::create([
        'user_id' => $driver->id,
        'date' => today(),
        'start_km' => 100,
        'start_fuel' => 50,
    ]);

    $this->actingAs($driver)
        ->get(route('dashboard'))
        ->assertOk()
        ->assertSeeText('Шофьор')
        ->assertSeeText('Vasilev')
        ->assertDontSee('{&quot;id&quot;:' . $driver->id)
        ->assertDontSee('{"id":' . $driver->id);
});

test('the dashboard shows not assigned when the driver has no report user', function () {
    $driver = User::factory()->create([
        'role' => 'driver',
    ]);

    $this->actingAs($driver)
        ->get(route('dashboard'))
        ->assertOk()
        ->assertSeeText('Шофьор')
        ->assertSeeText('Not assigned');
});
