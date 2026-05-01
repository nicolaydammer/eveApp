<?php

namespace App\Domain\Infrastructure\SDE\Models;

use Illuminate\Database\Eloquent\Model;

class MapMoon extends Model
{
    protected $table = 'sde.map_moons';

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
        'position',
        'attributes',
        'statistics',
        'npcStationIDs',
        'hash',
    ];

    protected $casts = [
        'radius' => 'float',
        'position' => 'array',
        'attributes' => 'array',
        'statistics' => 'array',
        'npcStationIDs' => 'array',
    ];
}
