<?php

namespace App\Models\SDE;

use Illuminate\Database\Eloquent\Model;

class MapMoon extends Model
{
    protected $table = 'map_moons';

    protected $primaryKey = '_key';

    public $incrementing = false;

    public $timestamps = false;

    protected $keyType = 'int';

    protected $fillable = [
        '_key',
        'solarSystemID',
        'orbitID',
        'typeID',
        'celestialIndex',
        'orbitIndex',
        'radius',
        'x',
        'y',
        'z',
        'attributes',
        'statistics',
        'npcStationIDs'
    ];

    protected $casts = [
        'radius' => 'float',
        'x' => 'float',
        'y' => 'float',
        'z' => 'float',
        'attributes' => 'array',
        'statistics' => 'array',
        'npcStationIDs' => 'array',
    ];
}
