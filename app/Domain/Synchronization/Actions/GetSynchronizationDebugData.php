<?php

namespace App\Domain\Synchronization\Actions;

use App\Domain\Synchronization\Models\Synchronization;
use Illuminate\Database\Eloquent\Collection;

class GetSynchronizationDebugData
{
    public function execute(): Collection
    {
        return Synchronization::query()
            ->with([
                'state',
                'latestRun',
            ])
            ->withCount('runs')
            ->orderBy('name')
            ->get();
    }
}
