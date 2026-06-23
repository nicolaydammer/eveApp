<?php

namespace App\Domain\Synchronization\Synchronizations;

use App\Domain\EVE\Jobs\SaveIndustryCostIndices;
use App\Domain\Health\Exceptions\SynchronizationFailedException;
use App\Domain\Infrastructure\Esi\Clients\EsiClient;
use App\Domain\Synchronization\Actions\FailSynchronization;
use App\Domain\Synchronization\Actions\FinishSynchronization;
use App\Domain\Synchronization\Contracts\SynchronizationInterface;
use App\Domain\Synchronization\Helpers\SynchronizationLock;
use App\Domain\Synchronization\Helpers\SynchronizationScheduler;
use App\Domain\Synchronization\Models\Synchronization;
use Exception;
use Illuminate\Bus\Batch;
use Illuminate\Support\Facades\Bus;
use Throwable;

class IndustryCostIndices implements SynchronizationInterface
{
    public const NAME = 'industry-cost-indices';

    public function __construct(
        private readonly EsiClient $esiClient,
    ) {}

    public function run(Synchronization $synchronization): void
    {
        try {
            $esiData = $this->esiClient->get('/industry/systems');

            $now = now();

            $rows = collect($esiData)
                ->map(function (array $system) use ($now) {
                    $row = [
                        'solar_system_id' => $system['solar_system_id'],
                        'manufacturing' => null,
                        'researching_material_efficiency' => null,
                        'researching_time_efficiency' => null,
                        'copying' => null,
                        'invention' => null,
                        'reaction' => null,
                        'reverse_engineering' => null,
                        'duplicating' => null,
                        'synced_at' => $now,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];

                    foreach ($system['cost_indices'] as $index) {
                        $row[$index['activity']] = $index['cost_index'];
                    }

                    return $row;
                });

            $jobs = $rows
                ->chunk(500)
                ->map(fn($chunk) => new SaveIndustryCostIndices($chunk->all()))
                ->all();

            $synchronization->refresh()->load([
                'state',
                'latestRun',
            ]);

            $synchronization->latestRun?->update([
                'expected_jobs' => count($jobs),
            ]);

            $synchronizationId = $synchronization->id;

            Bus::batch($jobs)
                ->then(function (Batch $batch) use ($synchronizationId) {
                    app(FinishSynchronization::class)->execute(
                        synchronization: Synchronization::findOrFail($synchronizationId),
                        batch: $batch,
                        finishedAt: now(),
                        nextSyncAt: SynchronizationScheduler::avoidDowntime(
                            now()->addHour(),
                        ),
                    );
                })
                ->catch(function (Batch $batch, Throwable $exception) use ($synchronizationId) {
                    $synchronization = Synchronization::findOrFail($synchronizationId);

                    SynchronizationLock::lock($synchronization, 5);

                    app(FailSynchronization::class)->execute(
                        $synchronization,
                        $batch,
                        now(),
                    );

                    throw new SynchronizationFailedException(
                        healthCode: 'industry.sync.fetch-indices',
                        previous: $exception,
                    );
                })
                ->dispatch();
        } catch (Throwable $exception) {

            SynchronizationLock::lock(
                $synchronization,
                5,
            );

            app(FailSynchronization::class)->execute(
                synchronization: $synchronization,
                batch: null,
                finishedAt: now(),
            );

            throw new SynchronizationFailedException(
                healthCode: 'industry.sync.fetch-indices',
                previous: $exception,
                context: [
                    'message' => $exception->getMessage(),
                ],
            );
        }
    }
}
