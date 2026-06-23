<?php

namespace App\Console\Commands;

use App\Domain\Synchronization\Actions\GetSynchronizationDebugData;
use Carbon\CarbonInterface;
use Illuminate\Console\Command;

class DebugSynchronizationCommand extends Command
{
    protected $signature = 'debug:sync';

    protected $description = 'Display the synchronization status overview.';

    public function __construct(
        private readonly GetSynchronizationDebugData $getSynchronizationDebugData,
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $synchronizations = $this->getSynchronizationDebugData->execute();

        if ($synchronizations->isEmpty()) {
            $this->info('No synchronizations registered.');

            return self::SUCCESS;
        }

        $this->table([
            'Name',
            'Status',
            'Jobs',
            'Duration',
            'Last Sync',
            'Next Sync',
            'Runs',
        ], $synchronizations->map(function ($synchronization) {
            $state = $synchronization->state;
            $run = $synchronization->latestRun;

            if ($run === null || $run->expected_jobs === null) {
                $jobs = 'N/A';
            } else {
                $jobs = sprintf(
                    '%d/%d (%d failed)',
                    $run->completed_jobs,
                    $run->expected_jobs,
                    $run->failed_jobs,
                );
            }

            $duration = 'N/A';

            if (
                $state?->started_at !== null &&
                $state?->finished_at !== null
            ) {
                $duration = $state->started_at->diffForHumans(
                    $state->finished_at,
                    [
                        'parts' => 2,
                        'short' => true,
                        'syntax' => CarbonInterface::DIFF_ABSOLUTE,
                    ],
                );
            }

            return [
                $synchronization->name,
                $state?->status->value ?? 'N/A',
                $jobs,
                $duration,
                $state?->last_synced_at ?? 'N/A',
                $state?->next_synced_at ?? 'N/A',
                $synchronization->runs_count,
            ];
        }));

        return self::SUCCESS;
    }
}
