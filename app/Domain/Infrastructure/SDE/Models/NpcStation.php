<?php

namespace App\Domain\Infrastructure\SDE\Models;

use Illuminate\Database\Eloquent\Model;

class NpcStation extends Model
{
    protected $table = 'sde.npc_stations';

    protected $primaryKey = '_key';

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        '_key',
        'celestialIndex',
        'operationID',
        'orbitID',
        'orbitIndex',
        'ownerID',
        'position',
        'reprocessingEfficiency',
        'reprocessingHangarFlag',
        'reprocessingStationsTake',
        'solarSystemID',
        'typeID',
        'useOperationName',
        'hash',
    ];

    protected $casts = [
        'useOperationName' => 'boolean',
        'position' => 'array',
        'reprocessingEfficiency' => 'float',
        'reprocessingStationsTake' => 'float',
    ];
}
