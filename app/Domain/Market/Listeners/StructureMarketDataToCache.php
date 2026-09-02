<?php

namespace App\Domain\Market\Listeners;

use App\Domain\Health\Exceptions\MarketCacheJobFailedException;
use App\Domain\Infrastructure\Configuration\Repositories\ConfigurationRepository;
use App\Domain\Market\Jobs\CacheStructureMarketData;
use App\Domain\Synchronization\Events\StructureMarketOrdersSynchronized;
use Illuminate\Bus\Batch;
use Illuminate\Support\Facades\Bus;
use Throwable;

class StructureMarketDataToCache
{
    /**
     * Create the event listener.
     */
    public function __construct(private ConfigurationRepository $configurationRepository) {}

    /**
     * Handle the event.
     */
    public function handle(StructureMarketOrdersSynchronized $event): void
    {
        if (! $this->configurationRepository->has('structure_markets')) {
            return;
        }

        $structures = $this->configurationRepository->get('structure_markets')['configuration'];

        $jobs = [];
        foreach ($structures as $structure) {
            $jobs[] = new CacheStructureMarketData($structure['structure']);
        }

        if (empty($jobs)) {
            return;
        }

        Bus::batch($jobs)
            ->allowFailures()
            ->catch(function (Batch $batch, Throwable $throwable) {
                throw new MarketCacheJobFailedException(healthCode: 'market.cache.system', previous: $throwable);
            })
            ->dispatch();
    }
}
