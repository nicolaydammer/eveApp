<?php

namespace App\Models\SDE;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DogmaAttribute extends Model
{
    use HasFactory;

    protected $table = 'dogma_attributes';

    protected $primaryKey = '_key';

    public $incrementing = false;

    public $timestamps = false;

    protected $keyType = 'integer';

    protected $fillable = [
        '_key',
        'attributeCategoryID',
        'dataType',
        'defaultValue',
        'description',
        'name',
        'published',
        'stackable',
        'displayName',
        'displayWhenZero',
        'highIsGood',
        'iconID',
        'tooltipDescription',
        'tooltipTitle',
        'unitID',
        'hash',
    ];

    protected $casts = [
        '_key' => 'integer',
        'attributeCategoryID' => 'integer',
        'dataType' => 'integer',
        'defaultValue' => 'float',
        'published' => 'boolean',
        'stackable' => 'boolean',
        'displayName' => 'array',
        'displayWhenZero' => 'boolean',
        'highIsGood' => 'boolean',
        'iconID' => 'integer',
        'tooltipDescription' => 'array',
        'tooltipTitle' => 'array',
        'unitID' => 'integer',
    ];
}
