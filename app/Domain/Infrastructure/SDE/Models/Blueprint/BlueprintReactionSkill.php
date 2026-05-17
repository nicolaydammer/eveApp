<?php

namespace App\Domain\Infrastructure\SDE\Models\Blueprint;

use App\Domain\Infrastructure\SDE\Models\Type;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BlueprintReactionSkill extends Model
{
    public $table = 'sde.blueprints_reaction_skills';

    public $timestamps = false;

    protected $fillable = [
        'typeID',
        'level',
        'blueprints_reaction_id'
    ];

    public function reaction(): BelongsTo
    {
        return $this->belongsTo(BlueprintReaction::class, 'blueprints_reaction_id', 'id');
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(Type::class, 'typeID', '_key');
    }
}
