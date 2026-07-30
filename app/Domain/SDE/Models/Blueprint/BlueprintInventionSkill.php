<?php

namespace App\Domain\SDE\Models\Blueprint;

use App\Domain\SDE\Models\Type;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BlueprintInventionSkill extends Model
{
    public $table = 'sde.blueprints_invention_skills';

    public $timestamps = false;

    protected $fillable = [
        'typeID',
        'level',
        'blueprints_invention_id'
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
