<?php

namespace App\Domain\Synchronization\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Synchronization extends Model
{
    protected $fillable = [
        'name',
        'enabled',
        'frequency',
        'offset_minutes',
        'downtime_aware',
    ];

    protected $casts = [
        'enabled' => 'boolean',
        'downtime_aware' => 'boolean',
    ];

    public function state(): HasOne
    {
        return $this->hasOne(SynchronizationState::class);
    }

    public function runs(): HasMany
    {
        return $this->hasMany(SynchronizationRun::class);
    }
}
