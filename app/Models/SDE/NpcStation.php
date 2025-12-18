<?php

namespace App\Models\SDE;

use Illuminate\Database\Eloquent\Model;

class NpcStation extends Model
{
    protected $table = 'npc_stations';
    protected $primaryKey = 'id';
    public $incrementing = false;
    public $timestamps = false;
    protected $guarded = [];

    protected $casts = [
        'useOperationName' => 'boolean',
        'position_x' => 'double',
        'position_y' => 'double',
        'position_z' => 'double',
        'reprocessingEfficiency' => 'float',
        'reprocessingStationsTake' => 'float',
    ];
}
