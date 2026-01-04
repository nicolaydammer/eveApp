<?php

namespace App\Models\SDE;

use Illuminate\Database\Eloquent\Model;

class PlanetSchematic extends Model
{
    protected $table = 'planet_schematics';

    protected $primaryKey = '_key';

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        '_key',
        'cycleTime',
        'name',
        'pins',
        'types',
        'hash',
    ];

    protected $casts = [
        'name' => 'array',
        'pins' => 'array',
        'types' => 'array',
    ];
}
