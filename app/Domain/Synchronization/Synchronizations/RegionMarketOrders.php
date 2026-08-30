<?php

namespace App\Domain\Synchronization\Synchronizations;

use App\Domain\Health\Exceptions\SynchronizationFailedException;
use App\Domain\Infrastructure\Configuration\Repositories\ConfigurationRepository;
use App\Domain\Infrastructure\Esi\Clients\EsiClient;
use App\Domain\Infrastructure\Esi\Requests\Market\RegionMarketOrdersRequest;
use App\Domain\Market\External\Esi\Jobs\SaveRegionMarketOrders;
use App\Domain\Market\External\Esi\Models\RegionMarketOrder;
use App\Domain\SDE\Services\Actions\ResolveRegionIdAction;
use App\Domain\Synchronization\Events\RegionMarketOrdersSynchronized;
use Carbon\Carbon;
use Illuminate\Bus\Batch;
use Override;

class RegionMarketOrders extends AbstractSynchronization
{
    public function __construct(
        private ConfigurationRepository $configurationRepository,
        private ResolveRegionIdAction $resolveRegionIdAction
    ) {}

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

        $esiClient = app(EsiClient::class);
        $systemIds = $this->configurationRepository->get('market_regions')['configuration'];
        $regionIds = $this->resolveRegionIdAction->fromSystemIds($systemIds);

        $data = [];

        foreach ($regionIds as $region_id) {
            $request = new RegionMarketOrdersRequest($region_id);
            $data[$region_id] = $esiClient->get($request);
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

    #[Override]
    protected function reconcile(Batch $batch, int $synchronizationRunId): void
    {
        $configurationRepository = new ConfigurationRepository();
        $regionIds = $configurationRepository
            ->get('market_regions')['configuration'];

        RegionMarketOrder::query()
            ->whereIn('region_id', $regionIds)
            ->where('last_sync_run_id', '!=', $synchronizationRunId)
            ->delete();
    }

    #[Override]
    protected function afterFinishEvents(): array
    {
        return [
            new RegionMarketOrdersSynchronized(),
        ];
    }
}
