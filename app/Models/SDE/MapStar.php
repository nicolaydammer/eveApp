<?php

namespace App\Models\SDE;

use Illuminate\Database\Eloquent\Model;

class MapStar extends Model
{
    protected $table = 'map_stars';

    protected $primaryKey = '_key';

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        '_key',
        'radius',
        'solarSystemID',
        'statistics',
        'typeID',
        'hash',
    ];

    protected $casts = [
        'statistics' => 'array',
    ];
}
