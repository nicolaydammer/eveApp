<?php

namespace App\Models\SDE;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MapRegion extends Model
{
    use HasFactory;

    protected $table = 'sde.map_regions';

    protected $primaryKey = '_key';

    public $incrementing = false;

    public $timestamps = false;

    protected $keyType = 'integer';

    protected $fillable = [
        '_key',
        'constellationIDs',
        'description',
        'factionID',
        'name',
        'nebulaID',
        'position',
        'wormholeClassID',
        'hash',
    ];

    protected $casts = [
        '_key' => 'integer',
        'constellationIDs' => 'array',
        'description' => 'array',
        'factionID' => 'integer',
        'name' => 'array',
        'nebulaID' => 'integer',
        'position' => 'array',
        'wormholeClassID' => 'integer',
    ];
}
