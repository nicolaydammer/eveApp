<?php

namespace App\Domain\SDE\Models;

use Illuminate\Database\Eloquent\Model;

class FighterAbility extends Model
{
    protected $table = 'sde.fighter_abilities';

    protected $primaryKey = '_key';

    public $incrementing = false;

    public $timestamps = false;

    protected $keyType = 'int';

    protected $fillable = [
        '_key',
        'disallowInHighSec',
        'disallowInLowSec',
        'displayName',
        'iconID',
        'targetMode',
        'tooltipText',
        'turretGraphicID',
        'hash',
    ];

    protected function casts(): array
    {
        return [
            '_key' => 'integer',
            'disallowInHighSec' => 'boolean',
            'disallowInLowSec' => 'boolean',
            'displayName' => 'array',
            'iconID' => 'integer',
            'tooltipText' => 'array',
            'turretGraphicID' => 'integer',
        ];
    }
}
