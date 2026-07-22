<?php

namespace App\Domain\Synchronization\Synchronizations;

use Carbon\Carbon;

class ReferenceMarketPrices extends AbstractSynchronization
{
    public static function name(): string
    {
        return 'reference-market-prices';
    }

    protected function getData(): array
    {
        // TODO: Implement getData() method.
    }

    protected function transformData(array $data): array
    {
        // TODO: Implement transformData() method.
    }

    protected function createJobs(array $data): array
    {
        // TODO: Implement createJobs() method.
    }

    protected function scheduleNextSync(): Carbon
    {
        // TODO: Implement scheduleNextSync() method.
    }
}
