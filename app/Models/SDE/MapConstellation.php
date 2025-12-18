<?php

namespace App\Models\SDE;

use Illuminate\Database\Eloquent\Model;

class MapConstellation extends Model
{
    protected $table = 'map_constellations';
    protected $primaryKey = 'id';
    public $incrementing = false;
    public $timestamps = false;
    protected $guarded = [];

    protected $casts = [
        'name' => 'array',
        'solarSystemIDs' => 'array',
        'position_x' => 'double',
        'position_y' => 'double',
        'position_z' => 'double',
    ];
}
