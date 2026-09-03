<?php

namespace App\Domain\SDE\Models;

use Illuminate\Database\Eloquent\Model;

class GraphicMaterialSet extends Model
{
    protected $table = 'sde.graphic_material_sets';

    protected $primaryKey = '_key';

    public $incrementing = false;

    public $timestamps = false;

    protected $keyType = 'int';

    protected $fillable = [
        '_key',
        'hash',
        'colorHull',
        'colorPrimary',
        'colorSecondary',
        'colorWindow',
        'custommaterial1',
        'custommaterial2',
        'description',
        'material1',
        'material2',
        'material3',
        'material4',
        'resPathInsert',
        'sofFactionName',
        'sofPatternName',
        'sofRaceHint',
    ];

    protected $casts = [
        '_key' => 'integer',
        'colorHull' => 'array',
        'colorPrimary' => 'array',
        'colorSecondary' => 'array',
        'colorWindow' => 'array',
    ];
}
