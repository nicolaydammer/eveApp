<?php

namespace App\Domain\Infrastructure\SDE\Models\Blueprint;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BlueprintManufacturing extends Model
{
    public $table = 'sde.blueprints_manufacturing';

    public $timestamps = false;

    protected $fillable = [
        'blueprintID',
        'time'
    ];

    public function blueprint(): BelongsTo
    {
        return $this->belongsTo(Blueprint::class, 'blueprintID', '_key');
    }

    public function materials(): HasMany
    {
        return $this->hasMany(BlueprintManufacturingMaterial::class, 'blueprints_manufacturing_id', 'id');
    }

    public function products(): HasMany
    {
        return $this->hasMany(BlueprintManufacturingProduct::class, 'blueprints_manufacturing_id', 'id');
    }

    public function skills(): HasMany
    {
        return $this->hasMany(BlueprintManufacturingSkill::class, 'blueprints_manufacturing_id', 'id');
    }
}
