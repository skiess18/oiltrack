<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationSetting extends Model
{
    protected $fillable = ['key', 'recipients'];

    protected $casts = [
        'recipients' => 'array',
    ];
}
