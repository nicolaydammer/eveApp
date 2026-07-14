<?php

namespace App\Domain\Synchronization\Synchronizations;

use App\Domain\EVE\Jobs\SaveIndustryCostIndices;
use App\Domain\Infrastructure\Esi\Clients\EsiClient;
use App\Domain\Synchronization\Helpers\SynchronizationScheduler;
use App\Domain\Synchronization\Synchronizations\AbstractSynchronization;
use Carbon\Carbon;

class IndustryCostIndices extends AbstractSynchronization
{
    public function __construct(private EsiClient $esiClient) {}

    public static function name(): string
    {
        return 'industry-cost-indices';
    }

    protected function getData(): array
    {
        return $this->esiClient->get('/industry/systems');
    }

    protected function transformData(array $data): array
    {
        $now = now();

        return collect($data)
            ->map(function (array $system) use ($now) {
                $row = [
                    'solar_system_id' => $system['solar_system_id'],
                    'manufacturing' => null,
                    'researching_material_efficiency' => null,
                    'researching_time_efficiency' => null,
                    'copying' => null,
                    'invention' => null,
                    'reaction' => null,
                    'reverse_engineering' => null,
                    'duplicating' => null,
                    'synced_at' => $now,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];

                foreach ($system['cost_indices'] as $index) {
                    $row[$index['activity']] = $index['cost_index'];
                }

                return $row;
            })->toArray();
    }

    protected function createJobs(array $data): array
    {
        $data = collect($data);

        return $data
            ->chunk(500)
            ->map(fn($chunk) => new SaveIndustryCostIndices($chunk->all()))
            ->all();
    }

    protected function scheduleNextSync(): Carbon
    {
        return SynchronizationScheduler::avoidDowntime(now()->addHour());
    }
}
