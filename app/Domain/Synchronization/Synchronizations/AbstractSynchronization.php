<?php

namespace App\Domain\Synchronization\Synchronizations;

use App\Domain\Health\Exceptions\SynchronizationFailedException;
use App\Domain\Synchronization\Actions\FailSynchronization;
use App\Domain\Synchronization\Actions\FinishSynchronization;
use App\Domain\Synchronization\Helpers\SynchronizationLock;
use App\Domain\Synchronization\Models\Synchronization;
use Carbon\Carbon;
use Illuminate\Bus\Batch;
use Illuminate\Support\Facades\Bus;
use Throwable;

abstract class AbstractSynchronization
{
    abstract public static function name(): string;

    abstract protected function getData(): array;

    abstract protected function transformData(array $data): array;

    abstract protected function createJobs(array $data, int $synchronizationRunId): array;

    abstract protected function scheduleNextSync(): Carbon;

    // override this to reconcile after completing the batch
    protected function reconcile(Batch $batch, int $synchronizationRunId): void {}

    // override this to clean up after every batch disregarding the fail or success state
    protected function cleanUp(Batch $batch): void {}

    final public function run(Synchronization $synchronization): void
    {
        $synchronizationName = static::name();

        try {
            $nextSync = $this->scheduleNextSync();

            $data = $this->getData();

            $synchronization->refresh()->load([
                'state',
                'latestRun',
            ]);

            if (empty($data)) {
                app(FinishSynchronization::class)->execute(
                    synchronization: Synchronization::findOrFail($synchronization->id),
                    batch: null,
                    finishedAt: now(),
                    nextSyncAt: $nextSync,
                );

                return;
            }

            $data = $this->transformData($data);

            $synchronization->refresh()->load([
                'state',
                'latestRun',
            ]);

            $synchronizationRunId = $synchronization->latestRun?->id;
            $jobs = $this->createJobs($data, $synchronizationRunId);

            $synchronization->latestRun?->update([
                'expected_jobs' => count($jobs),
            ]);

            $synchronizationId = $synchronization->id;

            Bus::batch($jobs)
                ->then(function (Batch $batch) use ($nextSync, $synchronizationId, $synchronizationRunId) {

                    $this->reconcile($batch, $synchronizationRunId);
                    app(FinishSynchronization::class)->execute(
                        synchronization: Synchronization::findOrFail($synchronizationId),
                        batch: $batch,
                        finishedAt: now(),
                        nextSyncAt: $nextSync,
                    );
                })
                ->catch(function (Batch $batch, Throwable $exception) use ($synchronizationId, $synchronizationName) {
                    $synchronization = Synchronization::findOrFail($synchronizationId);

                    SynchronizationLock::lock($synchronization, 5);

                    app(FailSynchronization::class)->execute(
                        $synchronization,
                        $batch,
                        now(),
                    );

                    throw new SynchronizationFailedException(
                        healthCode: 'sync.' . $synchronizationName,
                        previous: $exception,
                    );
                })
                ->finally(function (Batch $batch) {
                    $this->cleanUp($batch);
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
                healthCode: 'sync.' . $synchronizationName,
                previous: $exception,
                context: [
                    'message' => $exception->getMessage(),
                ],
            );
        }
    }
}
