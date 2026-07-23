<?php

namespace App\Domain\EVE\Models;

use Illuminate\Database\Eloquent\Model;

class Corporation extends Model
{
    protected $table = 'cache.corporations';

    protected $fillable = [
        'corporation_id',
        'alliance_id',
        'ceo_id',
        'creator_id',
        'date_founded',
        'description',
        'enlisted_faction_id',
        'friendly_fire',
        'home_station_id',
        'member_count',
        'name',
        'palette',
        'shares',
        'state',
        'tax_rates',
        'ticker',
        'type',
        'url',
        'war_eligible',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'date_founded' => 'datetime',
        'palette' => 'array',
        'tax_rates' => 'array',
    ];
}
