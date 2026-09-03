<?php

namespace App\Domain\SDE\Models;

use Illuminate\Database\Eloquent\Model;

class SkinrComponent extends Model
{
    protected $table = 'sde.skinr_components';

    protected $primaryKey = '_key';

    public $incrementing = false;

    public $timestamps = false;

    protected $keyType = 'int';

    protected $fillable = [
        '_key',
        'hash',
        'associatedTypeIds',
        'category',
        'finish',
        'iconFile',
        'name',
        'projectionTypeU',
        'projectionTypeV',
        'published',
        'rarity',
        'resourceFile',
        'sequenceBinder',
    ];

    protected $casts = [
        '_key' => 'integer',
        'associatedTypeIds' => 'array',
        'category' => 'integer',
        'published' => 'boolean',
        'rarity' => 'integer',
        'name' => 'array',
        'sequenceBinder' => 'array',
    ];
}
