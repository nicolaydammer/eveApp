<?php

namespace App\Domain\Market\External\Esi\Models;

use App\Domain\Synchronization\Models\SynchronizationRun;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RegionMarketOrderHistory extends Model
{
    protected $table = 'market.region_market_order_history';

    protected $fillable = [
        'synchronization_run_id',
        'order_id',
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
    ];

    protected function casts(): array
    {
        return [
            'synchronization_run_id' => 'integer',

            'order_id' => 'integer',
            'region_id' => 'integer',

            'location_id' => 'integer',
            'system_id' => 'integer',
            'type_id' => 'integer',

            'is_buy_order' => 'boolean',

            'price' => 'decimal:2',

            'volume_total' => 'integer',
            'volume_remain' => 'integer',
            'min_volume' => 'integer',

            'duration' => 'integer',

            'issued' => 'datetime',
        ];
    }

    public function synchronizationRun(): BelongsTo
    {
        return $this->belongsTo(
            SynchronizationRun::class,
        );
    }
}
