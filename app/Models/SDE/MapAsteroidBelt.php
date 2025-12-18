<?php

namespace App\Models\SDE;

use Illuminate\Database\Eloquent\Model;

class MapAsteroidBelt extends Model
{
    protected $table = 'map_asteroid_belts';
    protected $primaryKey = 'id';
    public $incrementing = false;
    public $timestamps = false;
    protected $guarded = [];

    protected $casts = [
        'locked' => 'boolean',
        'position_x' => 'double',
        'position_y' => 'double',
        'position_z' => 'double',
        'radius' => 'double',
        'density' => 'double',
        'eccentricity' => 'double',
        'escapeVelocity' => 'double',
        'massDust' => 'double',
        'massGas' => 'double',
        'orbitPeriod' => 'double',
        'orbitRadius' => 'double',
        'rotationRate' => 'double',
        'surfaceGravity' => 'double',
        'temperature' => 'double',
    ];
}
