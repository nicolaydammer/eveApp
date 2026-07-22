<?php

namespace App\Domain\Synchronization\Synchronizations;

use Carbon\Carbon;

class StructureMarketOrders extends AbstractSynchronization
{
    public static function name(): string
    {
        return 'structure-market-orders';
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
