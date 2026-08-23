<?php

use App\Models\Client;
use App\Models\Collection;
use App\Models\User;

function collectionClient(array $attributes = []): Client
{
    return Client::create(array_merge([
        'name' => 'Test Client',
        'address' => '1 Test Street',
        'phone' => '0000000000',
        'payment_method' => 'cash',
        'price_per_liter' => 1.75,
    ], $attributes));
}

test('a collection copies the configured client commercial fields', function () {
    $user = User::factory()->create();
    $client = collectionClient();

    $this->actingAs($user)
        ->post(route('collections.store', $client), [
            'collection_date' => '2026-08-05',
            'liters' => 10,
            'price_per_liter' => 999,
        ])
        ->assertRedirect(route('collections.index', $client));

    $this->assertDatabaseHas('collections', [
        'client_id' => $client->id,
        'liters' => 10,
        'price_per_liter' => 1.75,
        'total_price' => 17.50,
        'payment_method' => 'cash',
    ]);
});

test('a collection cannot be created when the client has no price per liter', function () {
    $user = User::factory()->create();
    $client = collectionClient(['price_per_liter' => null]);

    $this->actingAs($user)
        ->from(route('collections.create', $client))
        ->post(route('collections.store', $client), [
            'collection_date' => '2026-08-05',
            'liters' => 10,
        ])
        ->assertRedirect(route('collections.create', $client))
        ->assertSessionHasErrors([
            'price_per_liter' => 'Client price per liter is not configured.',
        ]);

    $this->assertDatabaseCount('collections', 0);
});

test('updating a collection refreshes its client commercial fields and total', function () {
    $user = User::factory()->create();
    $client = collectionClient();
    $collection = Collection::create([
        'client_id' => $client->id,
        'user_id' => $user->id,
        'collection_date' => '2026-08-04',
        'liters' => 5,
        'price_per_liter' => 1.75,
        'total_price' => 8.75,
        'payment_method' => 'cash',
    ]);

    $client->update([
        'price_per_liter' => 2.25,
        'payment_method' => 'bank_transfer',
    ]);

    $this->actingAs($user)
        ->put(route('collections.update', $collection), [
            'collection_date' => '2026-08-05',
            'liters' => 5,
        ])
        ->assertSessionHasNoErrors();

    $this->assertDatabaseHas('collections', [
        'id' => $collection->id,
        'price_per_liter' => 2.25,
        'total_price' => 11.25,
        'payment_method' => 'bank_transfer',
    ]);
});
