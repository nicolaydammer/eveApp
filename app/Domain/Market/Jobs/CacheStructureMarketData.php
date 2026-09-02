<?php

namespace App\Domain\Market\Jobs;

use App\Domain\Market\External\Esi\Models\StructureMarketOrder;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;

class CacheStructureMarketData implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(private int $structureId) {}

    public function handle()
    {
        $marketData = StructureMarketOrder::query()
            ->selectRaw('
        type_id,
        MAX(price) FILTER (WHERE is_buy_order = true) AS buy,
        MIN(price) FILTER (WHERE is_buy_order = false) AS sell,
        ROUND(
            (
                MAX(price) FILTER (WHERE is_buy_order = true)
                + MIN(price) FILTER (WHERE is_buy_order = false)
            ) / 2,
            2
        ) AS split
    ')
            ->where('structure_id', $this->structureId)
            ->groupBy('type_id')
            ->get()
            ->keyBy('type_id')
            ->toArray();

        Cache::tags(config('cacheTags.market'))->forever(
            "structure.{$this->structureId}",
            $marketData
        );
    }
}
