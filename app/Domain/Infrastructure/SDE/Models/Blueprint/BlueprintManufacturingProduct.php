<?php

namespace App\Domain\Infrastructure\SDE\Models\Blueprint;

use App\Domain\Infrastructure\SDE\Models\Type;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BlueprintManufacturingProduct extends Model
{
    public $table = 'sde.blueprints_manufacturing_products';

    public $timestamps = false;

    protected $fillable = [
        'typeID',
        'quantity',
        'blueprints_manufacturing_id',
        'probability'
    ];

    public function manufacturing(): BelongsTo
    {
        return $this->belongsTo(BlueprintManufacturing::class, 'blueprints_manufacturing_id', 'id');
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(Type::class, 'typeID', '_key');
    }
}
