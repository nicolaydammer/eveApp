<?php

namespace App\Domain\SDE\Models;

use Illuminate\Database\Eloquent\Model;

class AppliedProximityEffect extends Model
{
    protected $table = 'sde.applied_proximity_effects';

    protected $fillable = [
        '_key',
        'hash',
        'dbuffs',
        'delaySeconds',
        'radius',
    ];

    public $timestamps = false;
    public $incrementing = false;

    protected $keyType = 'int';

    protected function casts(): array
    {
        return [
            '_key' => 'integer',
            'dbuffs' => 'array',
            'delaySeconds' => 'integer',
            'radius' => 'integer',
        ];
    }
}
