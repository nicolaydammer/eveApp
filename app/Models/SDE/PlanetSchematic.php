<?php

namespace App\Models\SDE;

use Illuminate\Database\Eloquent\Model;

class PlanetSchematic extends Model
{
    protected $table = 'planet_schematics';
    protected $primaryKey = 'id';
    public $incrementing = false;
    public $timestamps = false;
    protected $guarded = [];

    protected $casts = [
        'name' => 'array',
        'pins' => 'array',
        'types' => 'array',
    ];
}
