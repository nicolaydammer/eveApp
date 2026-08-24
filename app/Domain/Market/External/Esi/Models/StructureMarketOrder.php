<?php

namespace App\Domain\Market\External\Esi\Models;

use App\Domain\Synchronization\Models\SynchronizationRun;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StructureMarketOrder extends Model
{
    protected $table = 'market.structure_market_orders';

    protected $primaryKey = 'order_id';

    public $incrementing = false;

    protected $keyType = 'int';

    protected $fillable = [
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
    ];

    protected function casts(): array
    {
        return [
            'last_sync_run_id' => 'integer',
            'structure_id' => 'integer',
            'location_id' => 'integer',
            'type_id' => 'integer',
            'system_id' => 'integer',
            'is_buy_order' => 'boolean',
            'price' => 'decimal:2',
            'volume_total' => 'integer',
            'volume_remain' => 'integer',
            'min_volume' => 'integer',
            'duration' => 'integer',
            'issued' => 'datetime',
        ];
    }

    public function lastSyncRun(): BelongsTo
    {
        return $this->belongsTo(
            SynchronizationRun::class,
            'last_sync_run_id',
        );
    }
}
