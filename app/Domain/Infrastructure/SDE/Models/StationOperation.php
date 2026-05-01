<?php

namespace App\Domain\Infrastructure\SDE\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StationOperation extends Model
{
    use HasFactory;

    protected $table = 'sde.station_operations';

    protected $primaryKey = '_key';

    public $incrementing = false;

    public $timestamps = false;

    protected $keyType = 'integer';

    protected $fillable = [
        '_key',
        'activityID',
        'operationName',
        'description',
        'border',
        'corridor',
        'fringe',
        'hub',
        'ratio',
        'manufacturingFactor',
        'researchFactor',
        'services',
        'stationTypes',
        'hash',
    ];

    protected $casts = [
        '_key' => 'integer',
        'activityID' => 'integer',
        'operationName' => 'array',
        'description' => 'array',
        'border' => 'float',
        'corridor' => 'float',
        'fringe' => 'float',
        'hub' => 'float',
        'ratio' => 'float',
        'manufacturingFactor' => 'float',
        'researchFactor' => 'float',
        'services' => 'array',
        'stationTypes' => 'array',
    ];
}
