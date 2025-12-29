<?php

namespace App\Models\SDE;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    use HasFactory;

    protected $table = 'types';

    protected $primaryKey = '_key';

    public $incrementing = false;

    public $timestamps = false;

    protected $keyType = 'integer';

    protected $fillable = [
        '_key',
        'groupID',
        'metaGroupID',
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
}
