<?php

namespace App\Domain\Synchronization\Synchronizations;

use App\Domain\Infrastructure\Esi\Clients\EsiClient;
use Carbon\Carbon;

class RegionMarketOrders extends AbstractSynchronization
{
    public function __construct(private EsiClient $esiClient) {}

    public static function name(): string
    {
        return 'structure-market-orders';
    }

    protected function getData(): array
    {
        // TODO: Implement getData() method.
        return [];
    }

    protected function transformData(array $data): array
    {
        // TODO: Implement transformData() method.
        return [];
    }

    protected function createJobs(array $data): array
    {
        // TODO: Implement createJobs() method.
        return [];
    }

    protected function scheduleNextSync(): Carbon
    {
        return now()->addHours(1);
    }
}
