<?php

namespace App\Domain\SDE\Models;

use Illuminate\Database\Eloquent\Model;

class ProximityTrap extends Model
{
    protected $table = 'sde.proximity_trap';

    protected $primaryKey = '_key';

    public $incrementing = false;
    public $timestamps = false;

    protected $keyType = 'int';

    protected $fillable = [
        '_key',
        'dbuffDuration',
        'dbuffs',
        'forceDecloakDuration',
        'resetDelay',
        'showPerimeterLights',
        'triggerDelay',
        'triggerFilterTypeListID',
        'triggerRange',
        'hash',
    ];

    protected function casts(): array
    {
        return [
            '_key' => 'integer',
            'dbuffDuration' => 'integer',
            'dbuffs' => 'array',
            'forceDecloakDuration' => 'integer',
            'resetDelay' => 'integer',
            'showPerimeterLights' => 'boolean',
            'triggerDelay' => 'integer',
            'triggerFilterTypeListID' => 'integer',
            'triggerRange' => 'integer',
        ];
    }
}
