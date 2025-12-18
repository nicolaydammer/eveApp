<?php

namespace App\Models\SDE;

use Illuminate\Database\Eloquent\Model;

class MapStargate extends Model
{
    protected $table = 'map_stargates';
    protected $primaryKey = 'id';
    public $incrementing = false;
    public $timestamps = false;
    protected $guarded = [];

    protected $casts = [
        'position_x' => 'double',
        'position_y' => 'double',
        'position_z' => 'double',
    ];
}
