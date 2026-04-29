<?php

namespace App\Models\SDE;

use Illuminate\Database\Eloquent\Model;

class StationService extends Model
{
    protected $table = 'sde.station_services';

    protected $primaryKey = 'id';

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        '_key',
        'serviceName',
        'description',
        'hash',
    ];

    protected $casts = [
        'serviceName' => 'array',
        'description' => 'array',
    ];
}
