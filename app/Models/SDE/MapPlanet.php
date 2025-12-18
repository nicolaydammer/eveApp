<?php

namespace App\Models\SDE;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MapPlanet extends Model
{
    use HasFactory;

    protected $primaryKey = '_key';
    public $incrementing = false;
    protected $keyType = 'integer';
    public $table = 'map_planets';

    protected $fillable = [
        '_key',
        'solar_system_id',
        'orbit_id',
        'celestial_index',
        'type_id',
        'radius',
        'position',
        'attributes',
        'statistics',
        'moon_ids',
        'asteroid_belt_ids',
        'npc_station_ids',
    ];

    protected $casts = [
        '_key' => 'integer',
        'solar_system_id' => 'integer',
        'orbit_id' => 'integer',
        'celestial_index' => 'integer',
        'type_id' => 'integer',
        'radius' => 'integer',
        'position' => 'array',
        'attributes' => 'array',
        'statistics' => 'array',
        'moon_ids' => 'array',
        'asteroid_belt_ids' => 'array',
        'npc_station_ids' => 'array',
    ];
}
