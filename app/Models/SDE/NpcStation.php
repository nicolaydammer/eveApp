<?php

namespace App\Models\SDE;

use Illuminate\Database\Eloquent\Model;

class NpcStation extends Model
{
    protected $table = 'npc_stations';

    protected $primaryKey = 'id';

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
