<?php

namespace App\Domain\Health\Actions;

use App\Domain\Health\Contracts\HealthException;
use App\Domain\Health\Models\HealthEvent;

class ReportHealth
{
    public function execute(HealthException $exception): void
    {
        if (! app()->isProduction()) {
            return;
        }

        $healthEvent = HealthEvent::query()
            ->where('code', $exception->code())
            ->first();

        if ($healthEvent !== null) {
            $this->updateHealthEvent(
                $healthEvent,
                $exception,
            );

            return;
        }

        $this->createHealthEvent($exception);
    }

    private function createHealthEvent(HealthException $exception): void
    {
        $rootException = $exception->getPrevious() ?? $exception;

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
    ): void {
        $rootException = $exception->getPrevious() ?? $exception;

        $healthEvent->increment('occurrences');

        $healthEvent->update([
            'exception' => class_basename($rootException),
            'context' => $exception->context(),
            'last_seen_at' => now(),
        ]);
    }
}
