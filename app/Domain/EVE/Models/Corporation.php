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
        'faction_id',
        'home_station_id',
        'member_count',
        'name',
        'shares',
        'tax_rate',
        'ticker',
        'url',
        'war_eligible',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'date_founded' => 'datetime'
    ];
}
