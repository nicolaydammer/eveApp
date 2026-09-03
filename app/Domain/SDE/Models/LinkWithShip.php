<?php

namespace App\Domain\SDE\Models;

use Illuminate\Database\Eloquent\Model;

class LinkWithShip extends Model
{
    protected $table = 'sde.link_with_ship';

    protected $primaryKey = '_key';

    public $incrementing = false;
    public $timestamps = false;

    protected $keyType = 'int';

    protected $fillable = [
        '_key',
        'applyPvpFlag',
        'canRelink',
        'characterEnergyCost',
        'dbuffPostLinkDuration',
        'dbuffs',
        'generateCynoInhibitor',
        'keepDbuffDurationOnLinkBreak',
        'linkDuration',
        'linkEffectGraphicIDOverride',
        'linkableShipTypeListID',
        'maxLinkRange',
        'omegaOnly',
        'solarsystemInterferenceCost',
        'hash',
    ];

    protected function casts(): array
    {
        return [
            '_key' => 'integer',
            'applyPvpFlag' => 'boolean',
            'canRelink' => 'boolean',
            'characterEnergyCost' => 'float',
            'dbuffPostLinkDuration' => 'integer',
            'dbuffs' => 'array',
            'generateCynoInhibitor' => 'boolean',
            'keepDbuffDurationOnLinkBreak' => 'boolean',
            'linkDuration' => 'integer',
            'linkEffectGraphicIDOverride' => 'integer',
            'linkableShipTypeListID' => 'integer',
            'maxLinkRange' => 'integer',
            'omegaOnly' => 'boolean',
            'solarsystemInterferenceCost' => 'float',
        ];
    }
}
