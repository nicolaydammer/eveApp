<?php

namespace App\Domain\Infrastructure\SDE\Models\Blueprint;

use App\Domain\Infrastructure\SDE\Models\Type;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Blueprint extends Model
{
    protected $table = 'sde.blueprints';

    protected $primaryKey = '_key';

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        '_key',
        'blueprintTypeID',
        'maxProductionLimit',
        'copy_time',
        'research_time',
        'material_time',
        'hash',
    ];

    protected $hidden = [
        'hash'
    ];

    public function type(): BelongsTo
    {
        return $this->belongsTo(Type::class, 'blueprintTypeID', '_key');
    }

    public function invention(): HasOne
    {
        return $this->hasOne(BlueprintInvention::class, 'blueprintID', '_key');
    }

    public function manufacturing(): HasOne
    {
        return $this->hasOne(BlueprintManufacturing::class, 'blueprintID', '_key');
    }

    public function reaction(): HasOne
    {
        return $this->hasOne(BlueprintReaction::class, 'blueprintID', '_key');
    }
}
