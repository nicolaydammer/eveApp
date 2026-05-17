<?php

namespace App\Domain\Infrastructure\SDE\Models\Blueprint;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BlueprintInvention extends Model
{
    public $table = 'sde.blueprints_invention';

    public $timestamps = false;

    protected $fillable = [
        'blueprintID',
        'time'
    ];

    public function blueprint(): BelongsTo
    {
        return $this->belongsTo(BlueprintInvention::class, 'blueprintID', '_key');
    }

    public function materials(): HasMany
    {
        return $this->hasMany(BlueprintInventionMaterial::class, 'blueprints_invention_id', 'id');
    }

    public function products(): HasMany
    {
        return $this->hasMany(BlueprintInventionProduct::class, 'blueprints_invention_id', 'id');
    }

    public function skills(): HasMany
    {
        return $this->hasMany(BlueprintInventionSkill::class, 'blueprints_invention_id', 'id');
    }
}
