<?php

namespace App\Models\SDE;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faction extends Model
{
    use HasFactory;

    protected $table = 'factions';

    protected $primaryKey = '_key';

    public $incrementing = false;

    protected $keyType = 'integer';

    protected $fillable = [
        '_key',
        'corporationID',
        'description',
        'uniqueName',
        'name',
        'solarSystemID',
        'flatLogo',
        'flatLogoWithName',
        'iconID',
        'memberRaces',
        'sizeFactor',
        'stationCount',
        'stationSystemCount',
        'militiaCorporationID',
        'hash',
    ];

    protected $casts = [
        '_key' => 'integer',
        'corporationID' => 'integer',
        'description' => 'array',
        'isUnique' => 'boolean',
        'name' => 'array',
        'solarSystemID' => 'integer',
        'stationCount' => 'integer',
        'stationSystemCount' => 'integer',
        'militiaCorporationID' => 'integer',
    ];
}
