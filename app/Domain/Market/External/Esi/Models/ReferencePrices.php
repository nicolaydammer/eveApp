<?php

namespace App\Domain\Market\External\Esi\Models;

use Illuminate\Database\Eloquent\Model;

class ReferencePrices extends Model
{
    protected $table = 'market.market_reference_prices';

    protected $fillable = [
        'type_id',
        'adjusted_price',
        'average_price'
    ];

    public $timestamps = false;

    public $primaryKey = 'type_id';

    public $incrementing = false;
}
