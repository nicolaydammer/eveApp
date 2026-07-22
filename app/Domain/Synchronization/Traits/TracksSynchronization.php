<?php

namespace App\Domain\Synchronization\Traits;

use Illuminate\Database\Eloquent\Builder;

trait TracksSynchronization
{
    public function markAsSeen(string $syncId): void
    {
        $this->last_sync_id = $syncId;
    }

    public function scopeSeenInSync(Builder $query, string $syncId): Builder
    {
        return $query->where('last_sync_id', $syncId);
    }

    public function scopeNotSeenInSync(Builder $query, string $syncId): Builder
    {
        return $query->where('last_sync_id', '!=', $syncId);
    }
}
