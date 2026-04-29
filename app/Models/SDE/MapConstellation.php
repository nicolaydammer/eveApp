<?php

namespace App\Models\SDE;

use Illuminate\Database\Eloquent\Model;

class MapConstellation extends Model
{
    protected $table = 'sde.map_constellations';

    protected $primaryKey = '_key';

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        '_key',
        'factionID',
        'name',
        'position',
        'regionID',
        'solarSystemIDs',
        'wormholeClassID',
        'hash',
    ];

    protected $casts = [
        'name' => 'array',
        'solarSystemIDs' => 'array',
        'position' => 'array',
    ];
}
