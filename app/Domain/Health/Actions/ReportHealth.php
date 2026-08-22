<?php

namespace App\Domain\Health\Actions;

use App\Domain\Health\Contracts\HealthException;
use App\Domain\Health\Models\HealthEvent;
use Throwable;

class ReportHealth
{
    public function execute(HealthException $exception): void
    {
        if (! app()->isProduction()) {
            return;
        }

        $rootException = $exception->getPrevious() ?? $exception;

        $healthEvent = HealthEvent::query()
            ->where('code', $exception->code())
            ->where('exception', class_basename($rootException))
            ->first();

        if ($healthEvent !== null) {
            $this->updateHealthEvent(
                $healthEvent,
                $exception,
                $rootException
            );

            return;
        }

        $this->createHealthEvent($exception, $rootException);
    }

    private function createHealthEvent(HealthException $exception, Throwable $rootException): void
    {
        HealthEvent::query()->create([
            'code' => $exception->code(),
            'source' => $exception->source(),
            'exception' => class_basename($rootException),
            'context' => $exception->context(),
            'occurrences' => 1,
            'first_seen_at' => now(),
            'last_seen_at' => now(),
        ]);
    }

    private function updateHealthEvent(
        HealthEvent $healthEvent,
        HealthException $exception,
        Throwable $rootException
    ): void {

        $healthEvent->increment('occurrences');

        $healthEvent->update([
            'exception' => class_basename($rootException),
            'context' => $exception->context(),
            'last_seen_at' => now(),
        ]);
    }
}
