<?php

namespace App\Domain\SDE\Models;

use Illuminate\Database\Eloquent\Model;

class Mission extends Model
{
    protected $table = 'sde.missions';

    protected $primaryKey = '_key';

    public $incrementing = false;

    public $timestamps = false;

    protected $keyType = 'int';

    protected $fillable = [
        '_key',
        'hash',
        'agentTypeID',
        'corporationID',
        'courierMission',
        'expirationTime',
        'extraStandings',
        'factionID',
        'hasStandingRewards',
        'initialAgentGiftQuantity',
        'initialAgentGiftTypeID',
        'killMission',
        'messages',
        'missionRewards',
        'name',
    ];

    protected $casts = [
        '_key' => 'integer',
        'agentTypeID' => 'integer',
        'corporationID' => 'integer',
        'courierMission' => 'array',
        'expirationTime' => 'integer',
        'extraStandings' => 'array',
        'factionID' => 'integer',
        'hasStandingRewards' => 'boolean',
        'initialAgentGiftQuantity' => 'integer',
        'initialAgentGiftTypeID' => 'integer',
        'killMission' => 'array',
        'messages' => 'array',
        'missionRewards' => 'array',
        'name' => 'array',
    ];
}
