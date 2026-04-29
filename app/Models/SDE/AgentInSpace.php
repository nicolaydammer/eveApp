<?php

namespace App\Models\SDE;

use Illuminate\Database\Eloquent\Model;

class AgentInSpace extends Model
{
    protected $table = 'sde.agents_in_space';

    protected $primaryKey = '_key';

    public $incrementing = false;

    public $timestamps = false;

    protected $keyType = 'int';

    protected $fillable = [
        '_key',
        'dungeonID',
        'solarSystemID',
        'spawnPointID',
        'typeID',
        'hash',
    ];
}
