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

    abstract protected function createJobs(array $data): array;

    abstract protected function scheduleNextSync(): Carbon;

    // override this to reconcile after completing the batch
    protected function reconcile(Batch $batch): void {}

    // override this to clean up after every batch disregarding the fail or success state
    protected function cleanUp(Batch $batch): void {}

    final public function run(Synchronization $synchronization): void
    {
        try {
            $data = $this->getData();

            $data = $this->transformData($data);

            $jobs = $this->createJobs($data);

            $nextSync = $this->scheduleNextSync();

            $synchronization->refresh()->load([
                'state',
                'latestRun',
            ]);

            $synchronization->latestRun?->update([
                'expected_jobs' => count($jobs),
            ]);

            $synchronizationId = $synchronization->id;

            Bus::batch($jobs)
                ->then(function (Batch $batch) use ($nextSync, $synchronizationId) {

                    $this->reconcile($batch);
                    app(FinishSynchronization::class)->execute(
                        synchronization: Synchronization::findOrFail($synchronizationId),
                        batch: $batch,
                        finishedAt: now(),
                        nextSyncAt: $nextSync,
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
                        healthCode: 'sync.' . static::name(),
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
                healthCode: 'sync.' . static::name(),
                previous: $exception,
                context: [
                    'message' => $exception->getMessage(),
                ],
            );
        }
    }
}
