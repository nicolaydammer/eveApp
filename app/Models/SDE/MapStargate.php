<?php

namespace App\Models\SDE;

use Illuminate\Database\Eloquent\Model;

class MapStargate extends Model
{
    protected $table = 'map_stargates';

    protected $primaryKey = 'id';

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        '_key',
        'destinationSolarSystemID',
        'destinationStargateID',
        'destination',
        'position',
        'solarSystemID',
        'typeID',
        'hash',
    ];

    protected $casts = [
        'position' => 'array',
    ];
}
