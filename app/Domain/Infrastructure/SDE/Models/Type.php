<?php

namespace App\Domain\Infrastructure\SDE\Models;

use App\Domain\Infrastructure\SDE\Models\Blueprint\Blueprint;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Type extends Model
{
    use HasFactory;

    protected $table = 'sde.types';

    protected $primaryKey = '_key';

    public $incrementing = false;

    public $timestamps = false;

    protected $keyType = 'integer';

    protected $hidden = ['hash'];

    protected $fillable = [
        '_key',
        'groupID',
        'metaGroupID',
        'metaLevel',
        'factionID',
        'name',
        'description',
        'variationParentTypeID',
        'mass',
        'portionSize',
        'published',
        'volume',
        'radius',
        'graphicID',
        'iconID',
        'soundID',
        'raceID',
        'basePrice',
        'capacity',
        'marketGroupID',
        'hash',
    ];

    protected $casts = [
        '_key' => 'integer',
        'groupID' => 'integer',
        'name' => 'array',
        'description' => 'array',
        'mass' => 'float',
        'portionSize' => 'integer',
        'published' => 'boolean',
        'volume' => 'float',
        'radius' => 'float',
        'graphicID' => 'integer',
        'iconID' => 'integer',
        'soundID' => 'integer',
        'raceID' => 'integer',
        'basePrice' => 'float',
        'capacity' => 'float',
        'marketGroupID' => 'integer',
    ];

    public function blueprint(): HasOne
    {
        return $this->hasOne(Blueprint::class, 'blueprintTypeID', '_key');
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class, 'groupID', '_key');
    }
}
