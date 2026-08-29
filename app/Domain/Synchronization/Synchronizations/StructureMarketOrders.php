<?php

namespace App\Domain\Synchronization\Synchronizations;

use App\Domain\Auth\State\CharacterRepository;
use App\Domain\Health\Exceptions\SynchronizationFailedException;
use App\Domain\Infrastructure\Configuration\Repositories\ConfigurationRepository;
use App\Domain\Infrastructure\Esi\Clients\EsiClient;
use App\Domain\Infrastructure\Esi\Requests\Market\StructureMarketOrdersRequest;
use App\Domain\Market\External\Esi\Jobs\SaveStructureMarketOrders;
use App\Domain\Market\External\Esi\Models\StructureMarketOrder;
use App\Domain\Synchronization\Events\StructureMarketOrdersSynchronized;
use Carbon\Carbon;
use Illuminate\Bus\Batch;
use Override;

class StructureMarketOrders extends AbstractSynchronization
{
    public function __construct(private EsiClient $esiClient, private ConfigurationRepository $configurationRepository, private CharacterRepository $characterRepository) {}

    public static function name(): string
    {
        return 'structure-market-orders';
    }

    protected function getData(): array
    {
        if (! $this->configurationRepository->has('structure_markets')) {
            throw new SynchronizationFailedException(
                healthCode: 'sync.' . $this->name(),
                context: ['message' => 'structure_markets configuration has not been found.']
            );
        }

        $data = [];

        foreach ($this->configurationRepository->get('structure_markets')['configuration'] as $marketStructure) {

            $character = $this->characterRepository->find($marketStructure['char']);

            if (!is_null($character)) {
                $request = new StructureMarketOrdersRequest($character, $marketStructure['structure']);
                $data[$marketStructure['structure']] = $this->esiClient->get($request);
            }
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

        foreach ($data as $structureId => $structureData) {
            foreach ($structureData as $pageData) {
                $jobs[] = new SaveStructureMarketOrders($pageData, $structureId, $synchronizationRunId);
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
        $structures = $this->configurationRepository
            ->get('market_structures')['configuration'];

        $structureIds = [];

        foreach ($structures as $structure) {
            $structureIds[] = $structure['structure'];
        }

        StructureMarketOrder::query()
            ->whereIn('structure_id', $structureIds)
            ->where('last_sync_run_id', '!=', $synchronizationRunId)
            ->delete();
    }

    #[Override]
    protected function afterFinishEvents(): array
    {
        return [
            new StructureMarketOrdersSynchronized(),
        ];
    }
}
