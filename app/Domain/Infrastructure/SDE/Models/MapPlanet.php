<?php

namespace App\Domain\Infrastructure\SDE\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MapPlanet extends Model
{
    use HasFactory;

    protected $primaryKey = '_key';

    public $incrementing = false;

    public $timestamps = false;

    protected $keyType = 'integer';

    public $table = 'sde.map_planets';

    protected $fillable = [
        '_key',
        'solarSystemID',
        'uniqueName',
        'orbitID',
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
