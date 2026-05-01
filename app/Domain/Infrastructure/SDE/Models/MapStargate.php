<?php

namespace App\Domain\Infrastructure\SDE\Models;

use Illuminate\Database\Eloquent\Model;

class MapStargate extends Model
{
    protected $table = 'sde.map_stargates';

    protected $primaryKey = '_key';

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
