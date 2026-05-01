<?php

namespace App\Domain\Infrastructure\SDE\Models;

use Illuminate\Database\Eloquent\Model;

class Skin extends Model
{
    protected $table = 'sde.skins';

    protected $primaryKey = '_key';

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        '_key',
        'internalName',
        'skinMaterialID',
        'types',
        'allowCCPDevs',
        'skinDescription',
        'visibleSerenity',
        'visibleTranquility',
        'isStructureSkin',
        'hash',
    ];

    protected $casts = [
        '_key' => 'integer',
        'skinMaterialID' => 'integer',
        'types' => 'array',
        'allowCCPDevs' => 'boolean',
        'visibleSerenity' => 'boolean',
        'visibleTranquility' => 'boolean',
        'isStructureSkin' => 'boolean',
    ];
}
