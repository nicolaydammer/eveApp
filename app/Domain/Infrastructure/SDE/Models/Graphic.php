<?php

namespace App\Domain\Infrastructure\SDE\Models;

use Illuminate\Database\Eloquent\Model;

class Graphic extends Model
{
    protected $table = 'sde.graphics';

    protected $primaryKey = '_key';

    public $incrementing = false;

    public $timestamps = false;

    protected $keyType = 'int';

    protected $fillable = [
        '_key',
        'graphicFile',
        'sofMaterialSetID',
        'iconFolder',
        'sofFactionName',
        'sofHullName',
        'sofRaceName',
        'sofLayout',
        'hash',
    ];
}
