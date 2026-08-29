<?php

namespace App\Domain\Market\External\Esi\Jobs;

use App\Domain\Market\External\Esi\Models\RegionMarketOrder;
use App\Domain\Market\External\Esi\Models\RegionMarketOrderHistory;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SaveRegionMarketOrders implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;
    use Batchable;

    public function __construct(private array $data, private int $region_id, private int $synchronizationRunId) {}

    public function handle(): void
    {
        if (empty($this->data)) {
            return;
        }

        $orders = [];

        foreach ($this->data as $order) {
            $orders[] = [
                'order_id' => $order['order_id'],
                'last_sync_run_id' => $this->synchronizationRunId,
                'region_id' => $this->region_id,

                'location_id' => $order['location_id'],
                'system_id' => $order['system_id'],

                'type_id' => $order['type_id'],

                'is_buy_order' => $order['is_buy_order'],

                'price' => $order['price'],

                'range' => $order['range'],

                'volume_total' => $order['volume_total'],
                'volume_remain' => $order['volume_remain'],
                'min_volume' => $order['min_volume'],

                'duration' => $order['duration'],

                'issued' => $order['issued'],
            ];
        }

        RegionMarketOrder::upsert(
            $orders,
            ['order_id'],
            [
                'last_sync_run_id',
                'region_id',
                'location_id',
                'system_id',
                'type_id',
                'is_buy_order',
                'price',
                'range',
                'volume_total',
                'volume_remain',
                'min_volume',
                'duration',
                'issued',
            ],
        );

        $history = [];
        $now = now();

        foreach ($orders as $order) {
            $history[] = [
                'synchronization_run_id' => $this->synchronizationRunId,

                'order_id' => $order['order_id'],
                'region_id' => $order['region_id'],

                'location_id' => $order['location_id'],
                'system_id' => $order['system_id'],

                'type_id' => $order['type_id'],

                'is_buy_order' => $order['is_buy_order'],

                'price' => $order['price'],

                'range' => $order['range'],

                'volume_total' => $order['volume_total'],
                'volume_remain' => $order['volume_remain'],
                'min_volume' => $order['min_volume'],

                'duration' => $order['duration'],

                'issued' => $order['issued'],

                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        RegionMarketOrderHistory::insert($history);
    }
}
