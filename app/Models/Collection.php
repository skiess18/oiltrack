<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Collection extends Model
{
    protected $fillable = [
    'client_id',
    'transport_report_id',
    'collection_date',
    'liters',
    'price_per_liter',
    'total_price',
    'payment_method',
    'notes',
    'image',
    'signature',
    'latitude',
    'longitude',
    'user_id',
    'protocol_path',
    'cash_receipt_path',
    'protocol_sent_at',
];

    protected $casts = [
        'collection_date' => 'date',
        'liters' => 'decimal:2',
        'price_per_liter' => 'decimal:2',
        'total_price' => 'decimal:2',
        'protocol_sent_at' => 'datetime',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transportReport()
    {
        return $this->belongsTo(TransportReport::class);
    }

    public function protocol()
    {
        return $this->hasOne(Protocol::class);
    }
}
