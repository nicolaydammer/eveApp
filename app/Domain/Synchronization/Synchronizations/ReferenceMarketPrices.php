<?php

namespace App\Domain\Synchronization\Synchronizations;

use App\Domain\Infrastructure\Esi\Clients\EsiClient;
use App\Domain\Infrastructure\Esi\Requests\Market\ListMarketPricesRequest;
use App\Domain\Market\External\Esi\Jobs\SaveReferencePrices;
use Carbon\Carbon;

class ReferenceMarketPrices extends AbstractSynchronization
{
    public function __construct(private EsiClient $esiClient) {}

    public static function name(): string
    {
        return 'reference-market-prices';
    }

    protected function getData(): array
    {
        $request = new ListMarketPricesRequest();
        return $this->esiClient->get($request);
    }

    protected function transformData(array $data): array
    {
        return array_values($data);
    }

    protected function createJobs(array $data): array
    {
        $data = collect($data)
            ->map(fn(array $price) => [
                'adjusted_price' => $price['adjusted_price'] ?? null,
                'average_price' => $price['average_price'] ?? null,
                'type_id' => $price['type_id'],
            ]);

        return $data
            ->chunk(500)
            ->map(fn($chunk) => new SaveReferencePrices($chunk->values()->all()))
            ->all();
    }

    protected function scheduleNextSync(): Carbon
    {
        return now()->addHours(1);
    }
}
