<?php

namespace App\Domain\Infrastructure\SDE\Models\Blueprint;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BlueprintReaction extends Model
{
    public $table = 'sde.blueprints_reaction';

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
        return $this->hasMany(BlueprintReactionMaterial::class, 'blueprints_reaction_id', 'id');
    }

    public function products(): HasMany
    {
        return $this->hasMany(BlueprintReactionProduct::class, 'blueprints_reaction_id', 'id');
    }

    public function skills(): HasMany
    {
        return $this->hasMany(BlueprintReactionSkill::class, 'blueprints_reaction_id', 'id');
    }
}
