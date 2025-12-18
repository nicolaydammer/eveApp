<?php

namespace App\Models\SDE;

use Illuminate\Database\Eloquent\Model;

class Graphic extends Model
{
    protected $table = 'graphics';

    protected $primaryKey = '_key';

    public $incrementing = false;

    public $timestamps = false;

    protected $keyType = 'int';

    protected $fillable = [
        '_key',
        'graphicFile',
        'iconFolder',
        'sofFactionName',
        'sofHullName',
        'sofRaceName',
    ];
}
