<?php

namespace App\Domain\SDE\Models;

use Illuminate\Database\Eloquent\Model;

class StationStandingsRestriction extends Model
{
    protected $table = 'sde.station_standings_restrictions';

    protected $primaryKey = '_key';

    public $incrementing = false;
    public $timestamps = false;

    protected $keyType = 'int';

    protected $fillable = [
        '_key',
        'services',
        'hash',
    ];

    protected function casts(): array
    {
        return [
            '_key' => 'integer',
            'services' => 'array',
        ];
    }
}
