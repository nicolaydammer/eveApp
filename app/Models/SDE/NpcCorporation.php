<?php

namespace App\Models\SDE;

use Illuminate\Database\Eloquent\Model;

class NpcCorporation extends Model
{
    protected $table = 'npc_corporations';

    protected $primaryKey = 'id';

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        '_key',
        'ceoID',
        'enemyID',
        'deleted',
        'description',
        'divisions',
        'extent',
        'hasPlayerPersonnelManager',
        'initialPrice',
        'memberLimit',
        'minSecurity',
        'minimumJoinStanding',
        'name',
        'sendCharTerminationMessage',
        'investors',
        'shares',
        'size',
        'stationID',
        'exchangeRates',
        'factionID',
        'friendID',
        'secondaryActivityID',
        'taxRate',
        'tickerName',
        'uniqueName',
        'iconID',
        'raceID',
        'allowedMemberRaces',
        'corporationTrades',
        'lpOfferTables',
        'sizeFactor',
        'solarSystemID',
        'mainActivityID',
        'hash',
    ];

    protected $casts = [

    ];
}
