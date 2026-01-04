<?php

namespace App\Models\SDE;

use Illuminate\Database\Eloquent\Model;

class MapAsteroidBelt extends Model
{
    protected $table = 'map_asteroid_belts';

    protected $primaryKey = '_key';

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        '_key',
        'celestialIndex',
        'orbitID',
        'orbitIndex',
        'position',
        'radius',
        'solarSystemID',
        'statistics',
        'uniqueName',
        'typeID',
        'hash',
    ];

    protected $casts = [
        'position' => 'array',
        'radius' => 'double',
    ];
}
