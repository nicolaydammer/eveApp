<?php

namespace App\Domain\Infrastructure\SDE\Models\Blueprint;

use App\Domain\Infrastructure\SDE\Models\Type;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BlueprintInventionProduct extends Model
{
    public $table = 'sde.blueprints_invention_products';

    public $timestamps = false;

    protected $fillable = [
        'blueprints_invention_id',
        'typeID',
        'quantity',
        'probability'
    ];

    public function invention(): BelongsTo
    {
        return $this->belongsTo(BlueprintInvention::class, 'blueprints_invention_id', 'id');
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(Type::class, 'typeID', '_key');
    }
}
