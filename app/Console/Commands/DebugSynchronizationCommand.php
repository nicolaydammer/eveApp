<?php

namespace App\Console\Commands;

use App\Domain\Health\Exceptions\SynchronizationFailedException;
use App\Domain\Synchronization\Actions\GetSynchronizationDebugData;
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
            'Last Sync',
            'Next Sync',
            'Started',
            'Finished',
            'Runs',
        ], $synchronizations->map(function ($synchronization) {
            return [
                $synchronization->name,
                $synchronization->state->status->value,
                $synchronization->state->last_synced_at,
                $synchronization->state->next_synced_at,
                $synchronization->state->started_at,
                $synchronization->state->finished_at,
                $synchronization->runs->count(),
            ];
        }));

        return self::SUCCESS;
    }
}
