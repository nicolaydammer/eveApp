<?php

namespace App\Domain\Synchronization\Actions;

use App\Domain\Synchronization\Enums\SynchronizationStatus;
use App\Domain\Synchronization\Models\Synchronization;
use Carbon\Carbon;
use Illuminate\Bus\Batch;

class FailSynchronization
{
    public function execute(
        Synchronization $synchronization,
        Batch $batch,
        Carbon $finishedAt,
    ): void {
        $synchronization->state->update([
            'status' => SynchronizationStatus::Failed,

            'finished_at' => $finishedAt,

            'completed_jobs' => $batch->processedJobs(),
            'failed_jobs' => $batch->failedJobs,
        ]);

        $synchronization->runs()
            ->latest('started_at')
            ->first()
            ?->update([
                'status' => SynchronizationStatus::Failed,
                'finished_at' => $finishedAt,

                'completed_jobs' => $batch->processedJobs(),
                'failed_jobs' => $batch->failedJobs,
            ]);
    }
}
