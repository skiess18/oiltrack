<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClientEmailRecipient extends Model
{
    protected $fillable = ['email'];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }
}
