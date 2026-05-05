<?php

namespace App\Domain\EVE\Models;

use Illuminate\Database\Eloquent\Model;

class Alliance extends Model
{
    protected $table = 'cache.alliances';

    protected $fillable = [
        'alliance_id',
        'creator_corporation_id',
        'creator_id',
        'date_founded',
        'executor_corporation_id',
        'faction_id',
        'name',
        'ticker',
        'expires_at'
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'date_founded' => 'datetime'
    ];
}
