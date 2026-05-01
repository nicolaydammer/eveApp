<?php

namespace App\Domain\Infrastructure\SDE\Models;

use Illuminate\Database\Eloquent\Model;

class MapSolarSystem extends Model
{
    protected $table = 'sde.map_solar_systems';

    protected $primaryKey = '_key';

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        '_key',
        'border',
        'constellationID',
        'hub',
        'international',
        'luminosity',
        'factionID',
        'name',
        'planetIDs',
        'position',
        'position2D',
        'radius',
        'regionID',
        'regional',
        'securityClass',
        'securityStatus',
        'starID',
        'stargateIDs',
        'corridor',
        'fringe',
        'wormholeClassID',
        'disallowedAnchorCategories',
        'disallowedAnchorGroups',
        'visualEffect',
        'hash',
    ];

    protected $casts = [
        'border' => 'boolean',
        'hub' => 'boolean',
        'international' => 'boolean',
        'luminosity' => 'float',
        'name' => 'array',
        'planetIDs' => 'array',
        'position' => 'array',
        'position2D' => 'array',
        'regionID' => 'integer',
        'regional' => 'boolean',
        'securityStatus' => 'float',
        'stargateIDs' => 'array',
        'corridor' => 'boolean',
        'fringe' => 'boolean',
    ];
}
