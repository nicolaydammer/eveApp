<?php

namespace App\Models\SDE;

use Illuminate\Database\Eloquent\Model;

class PlanetResource extends Model
{
    protected $table = 'planet_resources';

    protected $primaryKey = '_key';

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        '_key',
        'power',
        'workforce',
        'reagent',
        'hash',
    ];

    protected $casts = [
        'power' => 'integer',
        'workforce' => 'integer',
        'reagent' => 'array',
    ];
}
