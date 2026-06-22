<?php

namespace App\Domain\Synchronization\Actions;

use App\Domain\Synchronization\Models\Synchronization;

class RunSynchronizations
{
    public function __construct(
        private readonly RunSynchronization $runSynchronization,
    ) {}

    public function execute(): void
    {
        $synchronizations = Synchronization::query()
            ->with('state')
            ->where('enabled', true)
            ->get();

        foreach ($synchronizations as $synchronization) {
            $this->runSynchronization->execute($synchronization);
        }
    }
}
