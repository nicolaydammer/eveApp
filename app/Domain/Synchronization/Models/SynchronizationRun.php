<?php

namespace App\Domain\Synchronization\Models;

use App\Domain\Synchronization\Enums\SynchronizationStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SynchronizationRun extends Model
{
    protected $fillable = [
        'synchronization_id',
        'status',
        'started_at',
        'finished_at',
        'expected_jobs',
        'completed_jobs',
        'failed_jobs',
    ];

    protected $casts = [
        'status' => SynchronizationStatus::class,

        'started_at' => 'datetime',
        'finished_at' => 'datetime',
    ];

    public function synchronization(): BelongsTo
    {
        return $this->belongsTo(Synchronization::class);
    }
}
