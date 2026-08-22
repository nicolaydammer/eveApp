<?php

namespace App\Domain\Market\External\Esi\Jobs;

use App\Domain\Market\External\Esi\Models\ReferencePrices;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SaveReferencePrices implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;
    use Batchable;

    public function __construct(private array $data) {}

    public function handle(): void
    {
        ReferencePrices::query()->upsert($this->data, ['type_id'], ['adjusted_price', 'average_price']);
    }
}
