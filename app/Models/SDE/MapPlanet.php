<?php

namespace App\Models\SDE;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MapPlanet extends Model
{
    use HasFactory;

    protected $primaryKey = '_key';

    public $incrementing = false;

    public $timestamps = false;

    protected $keyType = 'integer';

    public $table = 'map_planets';

    protected $fillable = [
        '_key',
        'solarSystemID',
        'uniqueName',
        'orbit_id',
        'celestialIndex',
        'typeID',
        'radius',
        'position',
        'attributes',
        'statistics',
        'moonIDs',
        'asteroidBeltIDs',
        'npcStationIDs',
        'hash',
    ];

    protected $casts = [

    ];
}
