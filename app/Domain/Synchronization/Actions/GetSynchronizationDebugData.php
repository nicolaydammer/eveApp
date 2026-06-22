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
                'runs' => fn($query) => $query
                    ->latest('started_at')
                    ->limit(10),
            ])
            ->orderBy('name')
            ->get();
    }
}
