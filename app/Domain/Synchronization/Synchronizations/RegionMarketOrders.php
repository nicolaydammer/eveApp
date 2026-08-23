<?php

namespace App\Domain\Synchronization\Synchronizations;

use App\Domain\Health\Exceptions\SynchronizationFailedException;
use App\Domain\Infrastructure\Configuration\Repositories\ConfigurationRepository;
use App\Domain\Infrastructure\Esi\Clients\EsiClient;
use App\Domain\Infrastructure\Esi\Requests\Market\RegionMarketOrdersRequest;
use App\Domain\Market\External\Esi\Jobs\SaveRegionMarketOrders;
use Carbon\Carbon;

class RegionMarketOrders extends AbstractSynchronization
{
    public function __construct(private EsiClient $esiClient, private ConfigurationRepository $configurationRepository) {}

    public static function name(): string
    {
        return 'region-market-orders';
    }

    protected function getData(): array
    {
        if (! $this->configurationRepository->has('market_regions')) {
            throw new SynchronizationFailedException(
                healthCode: 'sync.' . $this->name(),
                context: ['message' => 'market_region configuration has not been found.']
            );
        }

        $data = [];

        logger('data', $this->configurationRepository->get('market_regions'));

        foreach ($this->configurationRepository->get('market_regions')['configuration'] as $region_id) {
            $request = new RegionMarketOrdersRequest($region_id);
            $data[$region_id] = $this->esiClient->get($request);
        }

        return $data;
    }

    protected function transformData(array $data): array
    {
        return $data;
    }

    protected function createJobs(array $data, int $synchronizationRunId): array
    {
        $jobs = [];

        foreach ($data as $region_id => $regionData) {
            foreach ($regionData as $pageData) {
                $jobs[] = new SaveRegionMarketOrders($pageData, $region_id, $synchronizationRunId);
            }
        }

        return $jobs;
    }

    protected function scheduleNextSync(): Carbon
    {
        return now()->addMinutes(15);
    }
}
