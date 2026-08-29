<?php

namespace App\Domain\Market\External\Esi\Jobs;

use App\Domain\Market\External\Esi\Models\StructureMarketOrder;
use App\Domain\Market\External\Esi\Models\StructureMarketOrderHistory;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SaveStructureMarketOrders implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;
    use Batchable;

    public function __construct(private array $data, private int $structureId, private int $synchronizationRunId) {}

    public function handle(): void
    {
        if (empty($this->data)) {
            return;
        }

        $orders = array_map(function (array $order): array {
            return [
                'order_id' => $order['order_id'],

                'last_sync_run_id' => $this->synchronizationRunId,

                'structure_id' => $this->structureId,
                'location_id' => $order['location_id'],

                'type_id' => $order['type_id'],
                'system_id' => $order['system_id'] ?? null,

                'is_buy_order' => $order['is_buy_order'],

                'price' => $order['price'],

                'volume_total' => $order['volume_total'],
                'volume_remain' => $order['volume_remain'],
                'min_volume' => $order['min_volume'],

                'duration' => $order['duration'],

                'issued' => $order['issued'],
            ];
        }, $this->data);

        StructureMarketOrder::query()->upsert(
            values: $orders,
            uniqueBy: ['order_id'],
            update: [
                'last_sync_run_id',

                'structure_id',
                'location_id',

                'type_id',
                'system_id',

                'is_buy_order',

                'price',

                'volume_total',
                'volume_remain',
                'min_volume',

                'duration',

                'issued',

                'updated_at',
            ],
        );

        $now = now();

        $history = array_map(function (array $order) use ($now): array {
            return [
                'synchronization_run_id' => $this->synchronizationRunId,

                'order_id' => $order['order_id'],

                'structure_id' => $order['structure_id'],
                'location_id' => $order['location_id'],

                'type_id' => $order['type_id'],
                'system_id' => $order['system_id'],

                'is_buy_order' => $order['is_buy_order'],

                'price' => $order['price'],

                'volume_total' => $order['volume_total'],
                'volume_remain' => $order['volume_remain'],
                'min_volume' => $order['min_volume'],

                'duration' => $order['duration'],

                'issued' => $order['issued'],

                'created_at' => $now,
                'updated_at' => $now
            ];
        }, $orders);

        StructureMarketOrderHistory::query()->insert(
            $history
        );
    }
}
