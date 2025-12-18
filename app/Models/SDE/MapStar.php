<?php

namespace App\Models\SDE;

use Illuminate\Database\Eloquent\Model;

class MapStar extends Model
{
    protected $table = 'map_stars';
    protected $primaryKey = 'id';
    public $incrementing = false;
    public $timestamps = false;
    protected $guarded = [];

    protected $casts = [
        'radius' => 'double',
        'age' => 'double',
        'life' => 'double',
        'luminosity' => 'double',
        'temperature' => 'double',
    ];
}
