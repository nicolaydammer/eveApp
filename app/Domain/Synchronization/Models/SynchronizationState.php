<?php

namespace App\Domain\Synchronization\Models;

use App\Domain\Synchronization\Enums\SynchronizationStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SynchronizationState extends Model
{
    protected $fillable = [
        'synchronization_id',
        'status',
        'started_at',
        'finished_at',
        'last_synced_at',
        'next_synced_at',
        'expected_jobs',
        'completed_jobs',
        'failed_jobs',
    ];

    protected $casts = [
        'status' => SynchronizationStatus::class,

        'started_at' => 'datetime',
        'finished_at' => 'datetime',
        'last_synced_at' => 'datetime',
        'next_synced_at' => 'datetime',
    ];

    public function synchronization(): BelongsTo
    {
        return $this->belongsTo(Synchronization::class);
    }
}
