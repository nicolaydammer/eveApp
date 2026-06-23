<?php

namespace App\Domain\Synchronization\Actions;

use App\Domain\Synchronization\Enums\SynchronizationStatus;
use App\Domain\Synchronization\Models\Synchronization;
use Carbon\Carbon;
use Illuminate\Bus\Batch;

class FinishSynchronization
{
    public function execute(
        Synchronization $synchronization,
        Carbon $nextSyncAt,
        Carbon $finishedAt,
        Batch $batch
    ): void {

        $synchronization->state->update([
            'status' => SynchronizationStatus::Success,

            'finished_at' => $finishedAt,
            'last_synced_at' => $finishedAt,
            'next_synced_at' => $nextSyncAt,

            'completed_jobs' => $batch->processedJobs(),
            'failed_jobs' => $batch->failedJobs,
        ]);

        $synchronization->runs()
            ->latest('started_at')
            ->first()
            ?->update([
                'status' => SynchronizationStatus::Success,
                'finished_at' => $finishedAt,

                'completed_jobs' => $batch->processedJobs(),
                'failed_jobs' => $batch->failedJobs,
            ]);
    }
}
