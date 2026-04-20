<?php

namespace App\Models\SDE;

use Illuminate\Database\Eloquent\Model;

class MapSecondarySun extends Model
{
    protected $table = 'map_secondary_suns';

    protected $primaryKey = '_key';
    public $incrementing = false;
    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        '_key',
        'effectBeaconTypeID',
        'solarSystemID',
        'typeID',
        'position',
        'hash',
    ];

    protected $casts = [
        'position' => 'array',
    ];
}
