<?php

namespace App\Domain\SDE\Models;

use Illuminate\Database\Eloquent\Model;

class MetenoxMoonDrill extends Model
{
    protected $table = 'sde.metenox_moon_drill';

    protected $primaryKey = '_key';

    public $incrementing = false;
    public $timestamps = false;

    protected $keyType = 'int';

    protected $fillable = [
        '_key',
        'miningCycleTime',
        'miningEfficiency',
        'reagentsConsumedPerCycle',
        'hash',
    ];

    protected function casts(): array
    {
        return [
            '_key' => 'integer',
            'miningCycleTime' => 'integer',
            'miningEfficiency' => 'float',
            'reagentsConsumedPerCycle' => 'integer',
        ];
    }
}
