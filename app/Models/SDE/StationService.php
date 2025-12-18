<?php

namespace App\Models\SDE;

use Illuminate\Database\Eloquent\Model;

class StationService extends Model
{
    protected $table = 'station_services';
    protected $primaryKey = 'id';
    public $incrementing = false;
    public $timestamps = false;
    protected $guarded = [];

    protected $casts = [
        'serviceName' => 'array',
        'description' => 'array',
    ];
}
