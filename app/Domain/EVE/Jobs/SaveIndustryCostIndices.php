<?php

namespace App\Domain\EVE\Jobs;

use App\Domain\EVE\Models\SystemIndices;
use App\Domain\Health\Exceptions\SynchronizationFailedException;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Throwable;

class SaveIndustryCostIndices implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;
    use Batchable;

    /**
     * @param array<int, array<string, mixed>> $rows
     */
    public function __construct(
        private readonly array $rows,
    ) {}

    public function handle(): void
    {
        try {
            SystemIndices::query()->upsert(
                $this->rows,
                ['solar_system_id'],
                [
                    'manufacturing',
                    'researching_material_efficiency',
                    'researching_time_efficiency',
                    'copying',
                    'invention',
                    'reaction',
                    'reverse_engineering',
                    'duplicating',
                    'synced_at',
                    'updated_at',
                ],
            );
        } catch (Throwable $exception) {
            throw new SynchronizationFailedException(
                healthCode: 'industry.sync.save-indices',
                previous: $exception,
            );
        }
    }
}
