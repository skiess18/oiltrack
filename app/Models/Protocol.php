<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Protocol extends Model
{
    protected $fillable = [
        'collection_id', 'client_id', 'user_id', 'pdf_path',
        'email_sent_to_owner', 'email_sent_to_client', 'sent_at',
    ];

    protected $casts = [
        'email_sent_to_owner' => 'boolean',
        'email_sent_to_client' => 'boolean',
        'sent_at' => 'datetime',
    ];

    public function collection(): BelongsTo { return $this->belongsTo(Collection::class); }
    public function client(): BelongsTo { return $this->belongsTo(Client::class); }
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
}
