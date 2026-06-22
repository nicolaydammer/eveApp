<?php

namespace App\Domain\Synchronization\Actions;

use App\Domain\Synchronization\Enums\SynchronizationStatus;
use App\Domain\Synchronization\Mappers\SynchronizationMap;
use App\Domain\Synchronization\Models\Synchronization;

class RunSynchronization
{
    public function execute(Synchronization $synchronization): void
    {
        if (! $this->canRun($synchronization)) {
            return;
        }

        $this->markAsRunning($synchronization);

        $this->createRun($synchronization);

        $this->run($synchronization);
    }

    private function canRun(Synchronization $synchronization): bool
    {
        if (! SynchronizationMap::has($synchronization->name)) {
            return false;
        }

        $state = $synchronization->state;

        if ($state->status->isRunning()) {
            return false;
        }

        if (
            $state->next_synced_at !== null &&
            $state->next_synced_at->isFuture()
        ) {
            return false;
        }

        return true;
    }

    private function markAsRunning(Synchronization $synchronization): void
    {
        $synchronization->state->update([
            'status' => SynchronizationStatus::Running,
            'started_at' => now(),
            'finished_at' => null,
        ]);
    }

    private function createRun(Synchronization $synchronization): void
    {
        $synchronization->runs()->create([
            'status' => SynchronizationStatus::Running,
            'started_at' => now(),
        ]);
    }

    private function run(Synchronization $synchronization): void
    {
        SynchronizationMap::resolve($synchronization->name)
            ->run($synchronization);
    }
}
