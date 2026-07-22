<?php

namespace App\Domain\Market\External\EveRef\Models;

use Illuminate\Database\Eloquent\Model;

class MarketHistory extends Model
{
    protected $connection = 'everef';

    protected $table = 'market_history';

    public $timestamps = false;

    protected $primaryKey = null;

    public $incrementing = false;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'date' => 'date',

            'region_id' => 'integer',
            'type_id' => 'integer',

            'average' => 'decimal:2',
            'highest' => 'decimal:2',
            'lowest' => 'decimal:2',

            'volume' => 'integer',
            'order_count' => 'integer',

            'http_last_modified' => 'immutable_datetime',
        ];
    }

    public function save(array $options = []): bool
    {
        throw new \LogicException('EVERef models are read-only.');
    }

    public function delete(): ?bool
    {
        throw new \LogicException('EVERef models are read-only.');
    }
}
