<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Collection extends Model
{
    protected $fillable = [
    'client_id',
    'collection_date',
    'liters',
    'price_per_liter',
    'total_price',
    'notes',
    'image',
    'signature',
    'latitude',
    'longitude',
];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}