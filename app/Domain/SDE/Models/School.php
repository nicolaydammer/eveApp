<?php

namespace App\Domain\SDE\Models;

use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    protected $table = 'sde.schools';

    protected $primaryKey = '_key';

    public $incrementing = false;
    public $timestamps = false;

    protected $keyType = 'int';

    protected $fillable = [
        '_key',
        'careerAgents',
        'careerID',
        'characterDescription',
        'corporationID',
        'description',
        'iconID',
        'isStarterSpaceSchool',
        'name',
        'raceID',
        'startingStations',
        'title',
        'hash',
    ];

    protected function casts(): array
    {
        return [
            '_key' => 'integer',
            'careerAgents' => 'array',
            'careerID' => 'integer',
            'characterDescription' => 'array',
            'corporationID' => 'integer',
            'description' => 'array',
            'iconID' => 'integer',
            'isStarterSpaceSchool' => 'boolean',
            'name' => 'array',
            'raceID' => 'integer',
            'startingStations' => 'array',
            'title' => 'array',
        ];
    }
}
