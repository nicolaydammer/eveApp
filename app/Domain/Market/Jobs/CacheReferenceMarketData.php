<?php

namespace App\Domain\Market\Jobs;

use App\Domain\Market\External\Esi\Models\ReferencePrices;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;

class CacheReferenceMarketData implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct() {}

    public function handle()
    {
        $marketData = ReferencePrices::query()
            ->get()
            ->keyBy('type_id')
            ->toArray();

        Cache::tags(config('cacheTags.market'))->forever(
            "referencePrices",
            $marketData
        );
    }
}
