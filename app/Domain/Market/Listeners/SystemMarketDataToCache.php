<?php

namespace App\Domain\Market\Listeners;

use App\Domain\Health\Exceptions\MarketCacheJobFailedException;
use App\Domain\Infrastructure\Configuration\Repositories\ConfigurationRepository;
use App\Domain\Market\Jobs\CacheSystemMarketData;
use App\Domain\Synchronization\Events\RegionMarketOrdersSynchronized;
use Illuminate\Bus\Batch;
use Illuminate\Support\Facades\Bus;
use Throwable;

class SystemMarketDataToCache
{
    /**
     * Create the event listener.
     */
    public function __construct(private ConfigurationRepository $configurationRepository) {}

    /**
     * Handle the event.
     */
    public function handle(RegionMarketOrdersSynchronized $event): void
    {
        if (! $this->configurationRepository->has('market_regions')) {
            return;
        }

        $systems = $this->configurationRepository->get('market_regions')['configuration'];

        $jobs = [];
        foreach ($systems as $systemId) {
            $jobs[] = new CacheSystemMarketData($systemId);
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
